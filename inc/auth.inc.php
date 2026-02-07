<?php
// Håndterer innlogging, utlogging og tilgangskontroll

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/functions.inc.php';


function start_sesjon()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/**
 * Henter innlogget bruker fra $_SESSION (eller null hvis ingen).
 * Vi lagrer en liten "pakke" med data om brukeren i sesjonen.
 */
function hent_innlogget_bruker(): ?array
{
    start_sesjon();
    return $_SESSION['bruker'] ?? null;
}


function er_logget_inn(): bool
{
    return hent_innlogget_bruker() !== null;
}

/**
 * Krever at bruker er logget inn.
 * Hvis ikke – sendes til login-side.
 */
function krever_innlogging()
{
    if (!er_logget_inn()) {
        redirect('/login.php');
    }
}


function krever_rolle(string $rolle)
{
    $bruker = hent_innlogget_bruker();

    if (!$bruker) {
        redirect('/login.php');
    }

    if (($bruker['rolle'] ?? '') !== $rolle) {
        echo "Du har ikke tilgang til denne siden.";
        exit;
    }
}


function forsok_innlogging(string $epost, string $passord): array
{
    global $pdo;
    start_sesjon();

    // 1) Hent bruker på e-post
    $sql = "SELECT * FROM brukere WHERE epost = :epost";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':epost' => $epost]);
    $bruker = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bruker) {
        return [false, "Feil e-post eller passord."];
    }

    // 2) Sjekk om brukeren er låst pga. for mange forsøk
    if (!empty($bruker['låst_til']) && $bruker['låst_til'] !== null) {
        $nå = new DateTimeImmutable();
        $låstTil = new DateTimeImmutable($bruker['låst_til']);

        if ($låstTil > $nå) {
            return [false, "Kontoen er midlertidig låst pga. for mange feilede innlogginger. Prøv igjen senere."];
        }
    }

    // 3) Sjekk passordet mot passordhash
    if (!password_verify($passord, $bruker['passordhash'])) {

        $nyFeil = (int)$bruker['feilede_logginn'] + 1;

        if ($nyFeil >= 3) {
            $sql = "UPDATE brukere 
                    SET feilede_logginn = :antall, låst_til = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                    WHERE id = :id";
        } else {
            $sql = "UPDATE brukere 
                    SET feilede_logginn = :antall
                    WHERE id = :id";
        }

        $oppdater = $pdo->prepare($sql);
        $oppdater->execute([
            ':antall' => $nyFeil,
            ':id'     => $bruker['id'],
        ]);

        return [false, "Feil e-post eller passord."];
    }

    // 4) Passord er riktig -> nullstill feilede forsøk og låsing
    $sql = "UPDATE brukere 
            SET feilede_logginn = 0, låst_til = NULL
            WHERE id = :id";
    $oppdater = $pdo->prepare($sql);
    $oppdater->execute([':id' => $bruker['id']]);

    // 5) Lagre "innlogget bruker" i sesjonen (bare det vi trenger)
    $_SESSION['bruker'] = [
        'id'      => $bruker['id'],
        'rolle'   => $bruker['rolle'],
        'fornavn' => $bruker['fornavn'],
        'etternavn' => $bruker['etternavn'],
        'epost'   => $bruker['epost'],
    ];

    return [true, "Innlogging vellykket."];
}


function logg_ut()
{
    start_sesjon();
    $_SESSION = [];
    session_destroy();

    redirect('/login.php');
}

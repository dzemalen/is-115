<?php
// Registrering av nye brukere med rollen "søker".

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';

// Hvis man allerede er logget inn, er det ikke vits å registrere ny bruker.
if (er_logget_inn()) {
    redirect('/index.php');
}

// Standardverdier til feltene.
$fornavn   = '';
$etternavn = '';
$epost     = '';
$feil      = [];
$okMelding = '';

// Når skjemaet sendes inn.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fornavn   = trim($_POST['fornavn']   ?? '');
    $etternavn = trim($_POST['etternavn'] ?? '');
    $epost     = trim($_POST['epost']     ?? '');
    $passord   = $_POST['passord']        ?? '';
    $passord2  = $_POST['passord2']       ?? '';

    // Enkel validering av felter.
    if ($fornavn === '') {
        $feil[] = "Fornavn må fylles ut.";
    }

    if ($etternavn === '') {
        $feil[] = "Etternavn må fylles ut.";
    }

    if ($epost === '') {
        $feil[] = "E-post må fylles ut.";
    } elseif (!is_valid_email($epost)) {
        $feil[] = "E-postadressen er ikke gyldig.";
    }

    // Passordvalidering
    if ($passord === '') {
        $feil[] = "Passord må fylles ut.";
    } else {
        $passordFeil = passord_feil($passord);
        if (!empty($passordFeil)) {
            $feil = array_merge($feil, $passordFeil);
        }
    }

    // Sjekk at gjentatt passord er likt.
    if ($passord !== $passord2) {
        $feil[] = "Passordene er ikke like.";
    }


    if (empty($feil)) {
        try {
            // Sjekk om e-posten allerede finnes.
            $sqlSjekk = "SELECT id FROM brukere WHERE epost = :epost";
            $stmtSjekk = $pdo->prepare($sqlSjekk);
            $stmtSjekk->execute([':epost' => $epost]);
            $finnes = $stmtSjekk->fetch(PDO::FETCH_ASSOC);

            if ($finnes) {
                $feil[] = "Det finnes allerede en bruker med denne e-postadressen.";
            } else {
                // Setter inn ny bruker med rollen 'søker'.
                $sql = "INSERT INTO brukere (rolle, fornavn, etternavn, epost, passordhash)
                        VALUES ('søker', :fornavn, :etternavn, :epost, :passordhash)";

                $stmt = $pdo->prepare($sql);
                $ok = $stmt->execute([
                    ':fornavn'     => $fornavn,
                    ':etternavn'   => $etternavn,
                    ':epost'       => $epost,
                    ':passordhash' => password_hash($passord, PASSWORD_DEFAULT),
                ]);

                if ($ok) {
                    $okMelding = "Bruker opprettet. Du kan nå logge inn.";
                    $fornavn = $etternavn = $epost = '';
                } else {
                    $feil[] = "Klarte ikke å opprette bruker. Prøv igjen.";
                }
            }

        } catch (PDOException $e) {
            $feil[] = "Databasefeil ved registrering.";
        }
    }
}

?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Registrer ny bruker</title>
</head>
<body>

    <h1>Registrer ny bruker</h1>

    <p>
        <a href="login.php">← Til innlogging</a>
    </p>

    <?php if ($okMelding !== ''): ?>
        <p style="color: green;"><?= k($okMelding) ?></p>
    <?php endif; ?>

    <?php if (!empty($feil)): ?>
        <ul style="color: red;">
            <?php foreach ($feil as $melding): ?>
                <li><?= k($melding) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <p>
            <label>
                Fornavn<br>
                <input type="text" name="fornavn" value="<?= k($fornavn) ?>" required>
            </label>
        </p>

        <p>
            <label>
                Etternavn<br>
                <input type="text" name="etternavn" value="<?= k($etternavn) ?>" required>
            </label>
        </p>

        <p>
            <label>
                E-post<br>
                <input type="email" name="epost" value="<?= k($epost) ?>" required>
            </label>
        </p>

        <p>
            <label>
                Passord<br>
                <input type="password" name="passord" required>
            </label>
        </p>

        <p>
            <label>
                Gjenta passord<br>
                <input type="password" name="passord2" required>
            </label>
        </p>

        <p>
            <button type="submit">Registrer bruker</button>
        </p>
    </form>

</body>
</html>

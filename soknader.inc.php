<?php
// Funksjoner for å jobbe med søknader

require_once __DIR__ . '/../config/db.inc.php';


function opprett_soknad(int $stillingId, int $sokerId, string $melding): bool
{
    global $pdo;

    $sql = "INSERT INTO soknader (stilling_id, soker_id, melding)
            VALUES (:stilling_id, :soker_id, :melding)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':stilling_id' => $stillingId,
        ':soker_id'    => $sokerId,
        ':melding'     => $melding,
    ]);
}

// Henter alle søknader for en bestemt søker.
 
function hent_soknader_for_soker(int $sokerId): array
{
    global $pdo;

    $sql = "SELECT so.*,
                   st.tittel,
                   st.sted
            FROM soknader AS so
            JOIN stillinger AS st ON so.stilling_id = st.id
            WHERE so.soker_id = :soker_id
            ORDER BY so.opprettet DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':soker_id' => $sokerId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Henter alle søknader til en stilling (for arbeidsgiver).
function hent_soknader_for_stilling(int $stillingId): array
{
    global $pdo;

    $sql = "SELECT so.*,
                   b.fornavn,
                   b.etternavn,
                   b.epost
            FROM soknader AS so
            JOIN brukere AS b ON so.soker_id = b.id
            WHERE so.stilling_id = :stilling_id
            ORDER BY so.opprettet DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':stilling_id' => $stillingId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Oppdaterer status på én søknad.
function oppdater_soknadsstatus(int $soknadId, string $nyStatus): bool
{
    global $pdo;

    $gyldigeStatuser = ['mottatt', 'vurdering', 'avslått', 'tilbud'];

    if (!in_array($nyStatus, $gyldigeStatuser, true)) {
        return false;
    }

    $sql = "UPDATE soknader
            SET status = :status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':status' => $nyStatus,
        ':id'     => $soknadId,
    ]);
}

<?php
// Hjelpefunksjoner for brukere

require_once __DIR__ . '/../config/db.inc.php';

// Henter brukerdata fra databasen basert på bruker-ID
function hent_bruker_fra_db(int $id): ?array
{
    global $pdo;

    $sql = "SELECT *
            FROM brukere
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $rad = $stmt->fetch(PDO::FETCH_ASSOC);

    return $rad ?: null;
}


function oppdater_bruker_profil(
    int $id,
    ?string $telefon,
    ?string $studiested,
    ?string $studieprogram,
    ?string $semester,
    ?string $cvTekst
): bool {
    global $pdo;

    $sql = "UPDATE brukere
            SET
                telefon = :telefon,
                studiested = :studiested,
                studieprogram = :studieprogram,
                semester = :semester,
                cv_tekst = :cv_tekst
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':telefon'      => $telefon !== '' ? $telefon : null,
        ':studiested'   => $studiested !== '' ? $studiested : null,
        ':studieprogram'=> $studieprogram !== '' ? $studieprogram : null,
        ':semester'     => $semester !== '' ? $semester : null,
        ':cv_tekst'     => $cvTekst !== '' ? $cvTekst : null,
        ':id'           => $id,
    ]);
}

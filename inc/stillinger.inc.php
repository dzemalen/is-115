<?php
// Funksjoner for å jobbe med stillinger (jobbutlysninger)

require_once __DIR__ . '/../config/db.inc.php';


function opprett_stilling(int $arbeidsgiverId, string $tittel, string $beskrivelse, string $sted): bool
{
    global $pdo;

    $sql = "INSERT INTO stillinger (arbeidsgiver_id, tittel, beskrivelse, sted)
            VALUES (:arbeidsgiver_id, :tittel, :beskrivelse, :sted)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':arbeidsgiver_id' => $arbeidsgiverId,
        ':tittel'          => $tittel,
        ':beskrivelse'     => $beskrivelse,
        ':sted'            => $sted,
    ]);
}

//Henter alle aktive stillinger (til forsiden).
function hent_alle_stillinger(): array
{
    global $pdo;

    $sql = "SELECT s.*,
                   b.fornavn,
                   b.etternavn,
                   b.epost
            FROM stillinger s
            JOIN brukere b ON s.arbeidsgiver_id = b.id
            WHERE s.aktiv = 1
            ORDER BY s.opprettet DESC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function hent_stilling(int $id): ?array
{
    global $pdo;

    $sql = "SELECT s.*,
                   b.fornavn,
                   b.etternavn,
                   b.epost
            FROM stillinger s
            JOIN brukere b ON s.arbeidsgiver_id = b.id
            WHERE s.id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $stilling = $stmt->fetch(PDO::FETCH_ASSOC);

    return $stilling ?: null;
}


function hent_stillinger_for_arbeidsgiver(int $arbeidsgiverId): array
{
    global $pdo;

    $sql = "SELECT *
            FROM stillinger
            WHERE arbeidsgiver_id = :arbeidsgiver_id
            ORDER BY opprettet DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':arbeidsgiver_id' => $arbeidsgiverId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

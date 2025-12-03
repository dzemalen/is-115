<?php
// Viser alle søknader for innlogget søker

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/soknader.inc.php';

krever_innlogging();
$bruker = hent_innlogget_bruker();

// Sjekker at dette faktisk er en søker
if ($bruker['rolle'] !== 'søker') {
    echo "Bare brukere med rollen «søker» har denne siden.";
    exit;
}

// Henter alle søknader for denne brukeren
$soknader = hent_soknader_for_soker((int)$bruker['id']);


?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Mine søknader</title>
</head>
<body>

    <h1>Mine søknader</h1>
    <p><a href="index.php">← Til forsiden</a></p>

    <?php if (empty($soknader)): ?>
        <p>Du har ikke sendt inn noen søknader ennå.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($soknader as $s): ?>
                <li>
                    <strong><?= k($s['tittel']) ?></strong>
                    (<?= k($s['sted']) ?>)<br>
                    Sendt: <?= k($s['opprettet']) ?><br>
                    Status: <?= k($s['status']) ?><br>
                    Søknadstekst: <?= nl2br(k($s['melding'])) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>

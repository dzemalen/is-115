<?php
// Viser alle stillinger som tilhører innlogget arbeidsgiver

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/stillinger.inc.php';

krever_innlogging();
$bruker = hent_innlogget_bruker();

// Bare arbeidsgivere skal ha tilgang hit
if ($bruker['rolle'] !== 'arbeidsgiver') {
    echo "Bare arbeidsgivere har tilgang til denne siden.";
    exit;
}

// Hent alle stillinger som denne arbeidsgiveren har opprettet
$mineStillinger = hent_stillinger_for_arbeidsgiver((int)$bruker['id']);


?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Mine stillinger</title>
</head>
<body>

    <h1>Mine stillinger</h1>

    <p>
        <a href="index.php">← Til forsiden</a> |
        <a href="ny_stilling.php">Ny stilling</a>
    </p>

    <?php if (empty($mineStillinger)): ?>
        <p>Du har ikke opprettet noen stillinger ennå.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($mineStillinger as $s): ?>
                <li>
                    <strong><?= k($s['tittel']) ?></strong>
                    (<?= k($s['sted']) ?>)<br>
                    Opprettet: <?= k($s['opprettet']) ?><br>

                    <!-- Lenke til offentlig visning av stillingen -->
                    <a href="stilling.php?id=<?= (int)$s['id'] ?>">Vis stilling</a>

                    <!-- Ny lenke: se søknader til denne stillingen -->
                    | <a href="stilling_soknader.php?stilling_id=<?= (int)$s['id'] ?>">
                        Se søknader
                      </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>

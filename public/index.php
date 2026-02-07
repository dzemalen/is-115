<?php
require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/stillinger.inc.php';

$bruker = hent_innlogget_bruker();

// Hent alle stillinger til visning
$stillinger = hent_alle_stillinger();
?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Forside - Søknadssystem</title>
</head>
<body>

    <h1>Forside</h1>

    <?php if ($bruker): ?>
        <p>
            Du er logget inn som
            <strong><?= k($bruker['fornavn'] . ' ' . $bruker['etternavn']) ?></strong>
            (rolle: <?= k($bruker['rolle']) ?>)
        </p>
        <p>
            <a href="min_profil.php">Min profil</a> |
            <a href="/logg_ut.php">Logg ut</a>
        </p>
    <?php else: ?>
        <p>Du er ikke logget inn.</p>
        <p><a href="/login.php">Gå til innlogging</a></p>
    <?php endif; ?>

    <?php if ($bruker): ?>
        <p>
            <?php if ($bruker['rolle'] === 'arbeidsgiver'): ?>
                <a href="mine_stillinger.php">Mine stillinger</a> |
                <a href="ny_stilling.php">Ny stilling</a>
            <?php elseif ($bruker['rolle'] === 'søker'): ?>
                <a href="mine_soknader.php">Mine søknader</a>
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <hr>

    <h2>Ledige stillinger</h2>

    <?php if (empty($stillinger)): ?>
        <p>Det finnes ingen registrerte stillinger ennå.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($stillinger as $s): ?>
                <li>
                    <strong><?= k($s['tittel']) ?></strong>
                    - <?= k($s['sted']) ?><br>
                    Arbeidsgiver:
                    <?= k($s['fornavn'] . ' ' . $s['etternavn']) ?>
                    (<?= k($s['epost']) ?>)<br>
                    <a href="stilling.php?id=<?= (int)$s['id'] ?>">Se mer / søk</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>

<?php

require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/stillinger.inc.php';

start_sesjon();
krever_innlogging();
krever_rolle('arbeidsgiver');

$feil = [];
$tittel = '';
$sted = '';
$beskrivelse = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tittel      = trim($_POST['tittel'] ?? '');
    $sted        = trim($_POST['sted'] ?? '');
    $beskrivelse = trim($_POST['beskrivelse'] ?? '');

    // Enkel validering
    if ($tittel === '') {
        $feil[] = "Tittel må fylles ut.";
    }

    if ($sted === '') {
        $feil[] = "Sted må fylles ut.";
    }

    if ($beskrivelse === '') {
        $feil[] = "Beskrivelse må fylles ut.";
    }

    // Hvis ingen feil så lagres i databasen
    if (empty($feil)) {
        $bruker = hent_innlogget_bruker();

        $ok = opprett_stilling(
            (int)$bruker['id'],
            $tittel,
            $beskrivelse,
            $sted
        );

        if ($ok) {
            redirect('/soknadssystem/public/index.php');
        } else {
            $feil[] = "Klarte ikke å lagre stillingen. Prøv igjen.";
        }
    }
}


?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Ny stilling</title>
</head>
<body>
    <h1>Ny stilling</h1>

    <p>
        <a href="index.php">← Til forsiden</a>
    </p>

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
                Tittel<br>
                <input type="text" name="tittel" value="<?= k($tittel) ?>" required>
            </label>
        </p>

        <p>
            <label>
                Sted<br>
                <input type="text" name="sted" value="<?= k($sted) ?>" required>
            </label>
        </p>

        <p>
            <label>
                Beskrivelse<br>
                <textarea name="beskrivelse" rows="6" cols="60" required><?= k($beskrivelse) ?></textarea>
            </label>
        </p>

        <p>
            <button type="submit">Lagre stilling</button>
        </p>
    </form>
</body>
</html>

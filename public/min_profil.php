<?php
// Side for at innlogget bruker kan se og endre profilinfo

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/brukere.inc.php';

start_sesjon();
krever_innlogging();

$innlogget = hent_innlogget_bruker();
$brukerId  = (int)$innlogget['id'];

// Hent ferske data fra databasen
$rad = hent_bruker_fra_db($brukerId);

if (!$rad) {
    echo "Fant ikke brukeren i databasen.";
    exit;
}

$feil = [];
$lagret = false;

$telefon      = $rad['telefon'] ?? '';
$studiested   = $rad['studiested'] ?? '';
$studieprogram= $rad['studieprogram'] ?? '';
$semester     = $rad['semester'] ?? '';
$cvTekst      = $rad['cv_tekst'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $telefon       = trim($_POST['telefon'] ?? '');
    $studiested    = trim($_POST['studiested'] ?? '');
    $studieprogram = trim($_POST['studieprogram'] ?? '');
    $semester      = trim($_POST['semester'] ?? '');
    $cvTekst       = trim($_POST['cv_tekst'] ?? '');

    // Enkel sjekk av telefon hvis den er fylt ut.
    if ($telefon !== '' && !valider_mobil($telefon)) {
        $feil[] = "Telefonnummer er ikke gyldig.";
    }

    if (empty($feil)) {
        $ok = oppdater_bruker_profil(
            $brukerId,
            $telefon,
            $studiested,
            $studieprogram,
            $semester,
            $cvTekst
        );

        if ($ok) {
            $lagret = true;

            $_SESSION['bruker']['telefon']      = $telefon;
            $_SESSION['bruker']['studiested']   = $studiested;
            $_SESSION['bruker']['studieprogram']= $studieprogram;
            $_SESSION['bruker']['semester']     = $semester;

        } else {
            $feil[] = "Klarte ikke å lagre endringene. Prøv igjen.";
        }
    }
}


?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Min profil</title>
</head>
<body>

    <h1>Min profil</h1>

    <p>
        <a href="index.php">← Til forsiden</a>
    </p>

    <p>
        Innlogget som
        <strong><?= k($innlogget['fornavn'] . ' ' . $innlogget['etternavn']) ?></strong>
        (rolle: <?= k($innlogget['rolle']) ?>)
    </p>

    <?php if ($lagret): ?>
        <p style="color: green;">Endringene er lagret.</p>
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
                Telefon (valgfritt)<br>
                <input type="text" name="telefon" value="<?= k($telefon) ?>">
            </label>
        </p>

        <p>
            <label>
                Studiested (f.eks. UiA, OsloMet …)<br>
                <input type="text" name="studiested" value="<?= k($studiested) ?>">
            </label>
        </p>

        <p>
            <label>
                Studieprogram (f.eks. IT og informasjonssystemer)<br>
                <input type="text" name="studieprogram" value="<?= k($studieprogram) ?>">
            </label>
        </p>

        <p>
            <label>
                Semester (f.eks. 2. semester bachelor)<br>
                <input type="text" name="semester" value="<?= k($semester) ?>">
            </label>
        </p>

        <p>
            <label>
                Kort CV / erfaring (tekst)<br>
                <textarea name="cv_tekst" rows="6" cols="60"><?= k($cvTekst) ?></textarea>
            </label>
        </p>

        <p>
            <button type="submit">Lagre profil</button>
        </p>
    </form>

</body>
</html>

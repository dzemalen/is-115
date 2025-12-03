<?php
// Viser en stilling og lar søkere sende inn søknad

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/stillinger.inc.php';
require_once __DIR__ . '/../inc/soknader.inc.php';

// Henter stilling-id 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Henter stillingen fra databasen
$stilling = hent_stilling($id);

if (!$stilling) {
    http_response_code(404);
    echo "Stillingen ble ikke funnet.";
    exit;
}

$bruker = hent_innlogget_bruker();

$feil = [];
$melding = "";

// Hvis skjemaet er sendt inn (POST), forsøk å lagre søknad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$bruker) {
        redirect('/soknadssystem/public/login.php');
    }

    if ($bruker['rolle'] !== 'søker') {
        echo "Bare brukere med rollen «søker» kan sende inn søknader.";
        exit;
    }

    $melding = trim($_POST['melding'] ?? '');

    if ($melding === '') {
        $feil[] = "Søknadstekst kan ikke være tom.";
    }

    if (empty($feil)) {
        $ok = opprett_soknad(
            (int)$stilling['id'],
            (int)$bruker['id'],
            $melding
        );

        if ($ok) {
            redirect('/soknadssystem/public/mine_soknader.php');
        } else {
            $feil[] = "Klarte ikke å lagre søknaden. Prøv igjen.";
        }
    }
}


?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title><?= k($stilling['tittel']) ?></title>
</head>
<body>

    <p><a href="index.php">← Til forsiden</a></p>

    <h1><?= k($stilling['tittel']) ?></h1>
    <p><strong>Sted:</strong> <?= k($stilling['sted']) ?></p>
    <p>
        <strong>Arbeidsgiver:</strong>
        <?= k($stilling['fornavn'] . ' ' . $stilling['etternavn']) ?>
        (<?= k($stilling['epost']) ?>)
    </p>

    <h2>Beskrivelse</h2>
    <p><?= nl2br(k($stilling['beskrivelse'])) ?></p>

    <hr>

    <h2>Søk på denne stillingen</h2>

    <?php if (!$bruker): ?>
        <p>Du må være logget inn som søker for å sende inn en søknad.</p>
        <p><a href="login.php">Logg inn</a></p>

    <?php elseif ($bruker['rolle'] !== 'søker'): ?>
        <p>Kun brukere med rollen <strong>søker</strong> kan sende inn søknader.</p>

    <?php else: ?>

        <?php if (!empty($feil)): ?>
            <ul style="color:red;">
                <?php foreach ($feil as $meldingFeil): ?>
                    <li><?= k($meldingFeil) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <p>
                <label>
                    Søknadstekst<br>
                    <textarea name="melding" rows="6" cols="60" required><?= k($melding) ?></textarea>
                </label>
            </p>
            <p>
                <button type="submit">Send søknad</button>
            </p>
        </form>

    <?php endif; ?>

</body>
</html>

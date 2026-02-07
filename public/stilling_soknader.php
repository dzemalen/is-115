<?php
// Viser alle søknader til en bestemt stilling (for arbeidsgiver)

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';
require_once __DIR__ . '/../inc/stillinger.inc.php';
require_once __DIR__ . '/../inc/soknader.inc.php';

krever_innlogging();
$bruker = hent_innlogget_bruker();

// Bare arbeidsgivere skal ha tilgang
if ($bruker['rolle'] !== 'arbeidsgiver') {
    echo "Bare arbeidsgivere har tilgang til denne siden.";
    exit;
}

// Henter stillings-ID 
$stillingId = isset($_GET['stilling_id']) ? (int)$_GET['stilling_id'] : 0;

// Hent stillingen
$stilling = hent_stilling($stillingId);

if (!$stilling) {
    echo "Stillingen ble ikke funnet.";
    exit;
}

// Sjekk at denne stillingen faktisk tilhører innlogget arbeidsgiver
if ((int)$stilling['arbeidsgiver_id'] !== (int)$bruker['id']) {
    echo "Du har ikke tilgang til søknadene til denne stillingen.";
    exit;
}

$feilmelding = '';
$okMelding   = '';

// Håndter endring av status (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $soknadId = isset($_POST['soknad_id']) ? (int)$_POST['soknad_id'] : 0;
    $nyStatus = $_POST['status'] ?? '';

    $gyldigeStatuser = ['mottatt', 'vurdering', 'avslått', 'tilbud'];

    if ($soknadId <= 0 || !in_array($nyStatus, $gyldigeStatuser, true)) {
        $feilmelding = "Ugyldig input for oppdatering av status.";
    } else {
        $ok = oppdater_soknadsstatus($soknadId, $nyStatus);

        if ($ok) {
            $okMelding = "Status oppdatert.";
        } else {
            $feilmelding = "Klarte ikke å oppdatere status.";
        }
    }
}

// Hent alle søknader til denne stillingen
$soknader = hent_soknader_for_stilling($stillingId);


?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Søknader til <?= k($stilling['tittel']) ?></title>
</head>
<body>

    <h1>Søknader til: <?= k($stilling['tittel']) ?></h1>
    <p>
        <a href="mine_stillinger.php">← Tilbake til mine stillinger</a> |
        <a href="stilling.php?id=<?= (int)$stilling['id'] ?>">Vis stillingsannonsen</a>
    </p>

    <?php if ($okMelding !== ''): ?>
        <p style="color: green;"><?= k($okMelding) ?></p>
    <?php endif; ?>

    <?php if ($feilmelding !== ''): ?>
        <p style="color: red;"><?= k($feilmelding) ?></p>
    <?php endif; ?>

    <?php if (empty($soknader)): ?>
        <p>Ingen har søkt på denne stillingen ennå.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($soknader as $so): ?>
                <li style="margin-bottom: 1em;">
                    <strong>Søker:</strong>
                    <?= k($so['fornavn'] . ' ' . $so['etternavn']) ?>
                    (<?= k($so['epost']) ?>)<br>

                    <strong>Sendt:</strong> <?= k($so['opprettet']) ?><br>
                    <strong>Status:</strong> <?= k($so['status']) ?><br>

                    <strong>Søknadstekst:</strong><br>
                    <?= nl2br(k($so['melding'])) ?><br><br>

                    <!-- Lite skjema for å endre status på denne søknaden -->
                    <form method="post" style="margin-top: 0.5em;">
                        <input type="hidden" name="soknad_id" value="<?= (int)$so['id'] ?>">

                        <label>
                            Endre status:
                            <select name="status">
                                <option value="mottatt"   <?= $so['status'] === 'mottatt'   ? 'selected' : '' ?>>Mottatt</option>
                                <option value="vurdering" <?= $so['status'] === 'vurdering' ? 'selected' : '' ?>>Under vurdering</option>
                                <option value="avslått"   <?= $so['status'] === 'avslått'   ? 'selected' : '' ?>>Avslått</option>
                                <option value="tilbud"    <?= $so['status'] === 'tilbud'    ? 'selected' : '' ?>>Tilbud</option>
                            </select>
                        </label>

                        <button type="submit">Lagre</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>

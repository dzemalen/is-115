<?php
// Enkel innloggingsside for søknadssystemet

require_once __DIR__ . '/../config/db.inc.php';
require_once __DIR__ . '/../inc/functions.inc.php';
require_once __DIR__ . '/../inc/auth.inc.php';

// Hvis bruker allerede er logget inn, send til forsiden
if (er_logget_inn()) {
    redirect('/index.php');
}

// Verdier til skjema / feilmelding
$epost = '';
$feilmelding = '';

// Håndter POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $epost   = trim($_POST['epost'] ?? '');
    $passord = $_POST['passord'] ?? '';

    if ($epost === '' || $passord === '') {
        $feilmelding = "Både e-post og passord må fylles ut.";
    } else {
        [$ok, $melding] = forsok_innlogging($epost, $passord);

        if ($ok) {
            redirect('/index.php');
        } else {
            $feilmelding = $melding;
        }
    }
}
?>
<!doctype html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <title>Logg inn</title>
</head>
<body>

    <h1>Logg inn</h1>

    <?php if ($feilmelding !== ''): ?>
        <p style="color:red;">
            <?= k($feilmelding) ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <p>
            <label>
                E-post:<br>
                <input type="email" name="epost" value="<?= k($epost) ?>" required>
            </label>
        </p>

        <p>
            <label>
                Passord:<br>
                <input type="password" name="passord" required>
            </label>
        </p>

        <p>
            <button type="submit">Logg inn</button>
        </p>
    </form>

    <p>
        Har du ikke bruker? 
        <a href="registrer.php">Registrer deg her</a>.
    </p>

</body>
</html>

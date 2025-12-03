<?php
// Felles funksjoner som brukes i flere deler av systemet.

// Viser feilmeldinger under utvikling.
error_reporting(E_ALL);
ini_set('display_errors', 1);


function k($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Enkel opprydding av innsendt tekst før man lagrer eller validerer.
function vask($data) {
    $data = $data ?? '';
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    return $data;
}


function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

// Sjekk av e-postadresse ved registrering.
function is_valid_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validerer mobilnummeret
function valider_mobil(string $mobil): bool {

    $mobil = str_replace(' ', '', $mobil);

    if (strlen($mobil) != 8) {
        return false;
    }

    for ($i = 0; $i < 8; $i++) {
        if ($mobil[$i] < '0' || $mobil[$i] > '9') {
            return false;
        }
    }

    return true;
}

// Sjekk av passord ved registrering.
function passord_feil(string $passord): array {
    $feil = [];

    if (strlen($passord) < 9) {
        $feil[] = "Passordet må være minst 9 tegn langt.";
    }

    $harStorBokstav = false;
    $antallTall     = 0;
    $harSpesial     = false;

    for ($i = 0; $i < strlen($passord); $i++) {
        $tegn = $passord[$i];

        if ($tegn >= 'A' && $tegn <= 'Z') {
            $harStorBokstav = true;
        } elseif ($tegn >= '0' && $tegn <= '9') {
            $antallTall++;
        } elseif (!(ctype_alpha($tegn))) {
            $harSpesial = true;
        }
    }

    if (!$harStorBokstav) {
        $feil[] = "Passordet må ha minst én stor bokstav.";
    }
    if ($antallTall < 2) {
        $feil[] = "Passordet må inneholde minst to tall.";
    }
    if (!$harSpesial) {
        $feil[] = "Passordet må ha minst ett spesialtegn.";
    }

    return $feil;
}

//sjekke om passordet oppfyller alle kravene
function is_valid_password(string $pwd): bool {
    return count(passord_feil($pwd)) === 0;
}

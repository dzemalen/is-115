# Søknadssystem – mini jobbsøknadsplattform

Et lite PHP-prosjekt som lar arbeidsgivere publisere stillinger og la søkere sende inn søknader. Fokus er enkel flyt, tydelig rollebasert tilgang og et rent fundament som er lett å demo‑kjøre lokalt.

## Overview (30 sekunder)
- **To roller:** søker og arbeidsgiver.
- **Arbeidsgiver** oppretter stillinger og ser innsendte søknader.
- **Søker** registrerer seg, søker på stillinger og følger status.
- **Rollebasert tilgang** med enkel sesjonsbasert auth.

## Features
- Innlogging/registrering med passordpolicy og lås etter flere feilforsøk.
- Opprette og liste stillinger.
- Søke på stillinger og se egne søknader.
- Arbeidsgiver kan se søknader per stilling og oppdatere status.

## Tech stack
- **PHP 8.1+** (server‑rendered HTML)
- **MySQL** (PDO)
- **PHPUnit** (tester)
- **PHP‑CS‑Fixer** (formatter)
- **GitHub Actions** (CI)

## Run lokalt (under 5 minutter)
> Forutsetter PHP 8.1+ og MySQL lokalt.

1) **Klon repo og installer verktøy**
```bash
composer install
```

2) **Konfigurer miljø**
```bash
cp .env.example .env
```
Oppdater `.env` ved behov.

3) **Lag database + schema**
```bash
mysql -u root -p -e "CREATE DATABASE soknadssystem CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root -p soknadssystem < docs/schema.sql
```

4) **Start appen**
```bash
php -S localhost:8000 -t public
```
Åpne: http://localhost:8000

5) **Demo‑data**
Registrer en arbeidsgiver og en søker via `/registrer.php` for å teste flyt.

## Kvalitetssignaler
- **Lint:** `composer lint`
- **Format check:** `composer format:check`
- **Tester:** `composer test`

## Architecture (kort)
- **public/**: entrypoints (sider)
- **inc/**: domene‑ og hjelpefunksjoner
- **config/**: database/konfig
- **docs/**: schema og dokumentasjon
- **tests/**: PHPUnit‑tester

Flyten er klassisk server‑rendered PHP: public‑side → inc‑funksjoner → PDO‑spørringer mot MySQL.

## Screenshots
> Placeholder – legg inn skjermbilder når UI er stylet.

## Uferdig / neste steg
- Enhetlig styling (CSS) og komponentbibliotek.
- E‑postvarsler ved ny søknad.
- Enkel søk/filtrering på stillinger.

## Folderstruktur
```
config/
inc/
public/
tests/
docs/
```

## CI
GitHub Actions kjører lint, formatter‑sjekk og tester på push/PR.

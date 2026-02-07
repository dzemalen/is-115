# Søknadssystem (studentprosjekt)

Kortversjon: Dette er et enkelt PHP-basert søknadssystem med roller for arbeidsgiver og søker. Prosjektet viser innlogging, registrering, stillingsannonser og søknader. Målet med denne README-en er å gjøre prosjektet forståelig på under 30 sekunder og gi tydelige steg for lokal kjøring.

## Overview
- **Hva:** Klassisk CRUD-basert søknadssystem med innlogging og roller.
- **Hvem:** Søker kan se/stille spørsmål og søke på stillinger. Arbeidsgiver kan opprette og administrere stillinger.
- **Hvorfor:** Viser grunnleggende webutvikling, PHP, MySQL og enkel tilgangsstyring.

## Features
- Innlogging og registrering
- Rollebaserte visninger (arbeidsgiver vs. søker)
- Liste over ledige stillinger
- Søknadsflyt

## Tech stack
- **PHP** (server-side logikk)
- **MySQL** (lagring av brukere, stillinger og søknader)
- **HTML** (enkle visninger)

## Architecture (kort)
- **`/public`**: Web-aksessible PHP-sider (f.eks. `index.php`, `login.php`).
- **`/inc`**: Felles hjelpefunksjoner, auth og domene-logikk.
- **`/config`**: Database-tilkobling.

> Merk: Repoet slik det ligger nå mangler disse mappene. Filene ligger i rotmappen, men koden forventer at de er plassert i `/public`, `/inc` og `/config`. Dette må rettes før prosjektet kan kjøres.

## Run instructions (lokalt, < 5 min om mulig)
### Forutsetninger
- PHP 8.1+ (eller nyere)
- MySQL 8+

### Status: **kan ikke kjøres helt uten ekstra arbeid**
Prosjektet kan **ikke** kjøres «out-of-the-box» fordi:
1. **Mappestruktur mangler** (koden forventer `/public`, `/inc`, `/config`).
2. **Databaseskjema mangler** (ingen `*.sql`-fil i repoet).

### Slik kommer du nærmest mulig i gang
1. Opprett følgende mapper i repoet og flytt filene dit:
   - `public/` (alle `.php`-sider som vises i nettleser)
   - `inc/` (alle `*.inc.php`-filer unntatt `db.inc.php`)
   - `config/` (flytt `db.inc.php` hit)
2. Opprett en MySQL-database (f.eks. `soknadssystem`).
3. Oppdater `config/db.inc.php` med riktige database-verdier.
4. Start PHPs innebygde webserver:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Åpne `http://localhost:8000` i nettleseren.

> **For å komme helt i mål trengs databasen** (tabeller og testdata). Hvis du finner `*.sql`-filen et annet sted i prosjektet (eller hos oppgavelærer), importer den i MySQL.

## .env
Prosjektet bruker ikke `.env`-fil i dag. Database-innstillinger settes i `config/db.inc.php`. Hvis det ønskes `.env`-støtte senere må koden oppdateres.

## Screenshots (placeholder)
- [ ] Legg til skjermbilde av forsiden
- [ ] Legg til skjermbilde av innlogging
- [ ] Legg til skjermbilde av «Ny stilling»-skjema

## Kvalitetssignaler
Installer verktøy først:
```bash
composer install
```

### Lint
```bash
composer run lint
```

### Formatter
```bash
composer run format
```

### Tester
```bash
composer run test
```

## Tester (kritiske deler)
Det er lagt inn grunnleggende PHPUnit-tester for sentrale valideringsfunksjoner (e-post, passord og mobilnummer).

## Intervju-oppsummering (kort)
**Hva ble forbedret:**
- Dokumentasjon som forklarer prosjektet, arkitektur og hvordan man kommer i gang.
- Innført enkle kvalitetsrutiner (lint, formatter, tester og CI).
- Lagt til grunnleggende tester for valideringslogikk.

**Hvordan forklare i intervju:**
- «Jeg gjorde prosjektet mer arbeidsgiver-vennlig ved å dokumentere arkitektur, forventet mappestruktur og konkrete steg for kjøring.»
- «Jeg la inn CI som kjører lint og tester for å gi raske signaler om kodekvalitet.»
- «Jeg valgte å teste valideringsfunksjoner fordi de er små, kritiske og lett å verifisere.»

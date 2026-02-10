# Foreslått struktur for GitHub Wiki

Denne Wiki-strukturen er laget for å utdype tekniske detaljer uten å gjenta README ordrett.

## 1) Overview
- Formål med systemet og målgruppe
- Kort domenemodell (søker, arbeidsgiver, stilling, søknad)
- Lenker til sentrale filer

## 2) Setup Guide
- Forutsetninger (PHP, MySQL, webserver)
- Forventet mappestruktur (`config/`, `inc/`, `public/`)
- Opprettelse av database og nødvendige tabeller
- Vanlige oppstartsproblemer (path-feil, DB-tilkobling, sessions)

## 3) Architecture
- Request-flyt fra sidefiler til `*.inc.php`
- Ansvarsdeling:
  - `auth.inc.php` (autentisering/roller)
  - `stillinger.inc.php` (stillinger)
  - `soknader.inc.php` (søknader)
  - `brukere.inc.php` (profil)
- Hvor input valideres og hvordan escaping håndteres

## 4) Database Notes
- Tabellroller og relasjoner
- Felt som påvirker forretningslogikk (f.eks. `rolle`, `aktiv`, `status`, `låst_til`)
- Statusflyt for søknader
- Forslag til indekser (faglig notat)

## 5) Security & Reliability Notes
- Passordhashing og verifisering
- Innloggingsbegrensning/lås
- Begrensninger i dagens tilgangskontroll
- Hva som bør forbedres ved produksjonssetting

## 6) Testing Approach
- Manuelle testscenarier for hver rolle
- Kritiske flows:
  - registrering/innlogging
  - opprette stilling
  - sende søknad
  - oppdatere søknadsstatus
- Forslag til enkel testplan for regresjon

## 7) Future Improvements
- `.env`-støtte og konfigurasjonsstrategi
- Bedre mappestruktur og autoloading
- UI-forbedringer og universell utforming
- Logging, feilhåndtering og observability

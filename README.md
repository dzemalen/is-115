# Søknadssystem (PHP + MySQL)

Et enkelt webbasert søknadssystem der **søkere** kan registrere seg, oppdatere profil og sende søknader, mens **arbeidsgivere** kan publisere stillinger og håndtere innkomne søknader. Prosjektet er laget som et læringsprosjekt i klassisk PHP med tydelig rollebasert flyt og databaseintegrasjon.

## Kort fortalt
- **Hva:** Jobbportal/søknadssystem med to roller (søker og arbeidsgiver).
- **Hvorfor:** Demonstrere grunnleggende fullstack-ferdigheter i PHP (autentisering, validering, database, CRUD-flyt).
- **For hvem:** Studenter/juniorutviklere som ønsker et konkret, kjørbart porteføljeprosjekt.

## Hovedfunksjoner
- Innlogging med sesjonshåndtering og enkel kontolås etter gjentatte feilforsøk.
- Registrering av nye brukere (rolle: søker) med passordkrav.
- Visning av aktive stillinger på forsiden.
- Opprettelse av stillinger for arbeidsgiver.
- Innsending av søknad på stilling for søker.
- Oversikt over egne søknader for søker.
- Oversikt over egne stillinger og mottatte søknader for arbeidsgiver.
- Oppdatering av søknadsstatus (`mottatt`, `vurdering`, `avslått`, `tilbud`).
- Enkel profilside med kontakt-/studieinformasjon og CV-tekst.

## Teknologistack
- **Backend:** PHP (prosedyrestil/funksjonsbasert struktur)
- **Database:** MySQL via PDO
- **Autentisering:** PHP-sesjoner + passordhashing (`password_hash` / `password_verify`)
- **Frontend:** Server-rendered HTML (ingen ekstern frontend-rammeverk)

## Prosjektstruktur
> Merk: Filene i dette repoet ligger i én mappe, men kodebasen forventer opprinnelig en struktur med `public/`, `inc/` og `config/`.

```text
.
├── index.php                # Forside med stillingsliste
├── login.php                # Innlogging
├── registrer.php            # Brukerregistrering (søker)
├── logg_ut.php              # Utlogging
├── stilling.php             # Detaljside for stilling + søknadsskjema
├── mine_soknader.php        # Søkers søknadshistorikk
├── mine_stillinger.php      # Arbeidsgivers stillinger
├── stilling_soknader.php    # Arbeidsgivers oversikt over søknader
├── ny_stilling.php          # Opprett ny stilling
├── min_profil.php           # Rediger profilinformasjon
├── auth.inc.php             # Sesjon, innlogging, tilgangskontroll
├── functions.inc.php        # Felles hjelpefunksjoner og validering
├── stillinger.inc.php       # Databaseoperasjoner for stillinger
├── soknader.inc.php         # Databaseoperasjoner for søknader
├── brukere.inc.php          # Databaseoperasjoner for brukerprofil
└── db.inc.php               # PDO-oppsett og DB-tilkobling
```

## Lokal kjøring

### 1) Forutsetninger
- PHP 8.x (med PDO MySQL aktivert)
- MySQL / MariaDB
- En lokal webserver (f.eks. Apache i XAMPP/LAMP, eller `php -S`)

### 2) Database
`db.inc.php` bruker følgende standardverdier:
- Host: `localhost`
- Bruker: `root`
- Passord: tom streng
- Database: `soknadssystem`

Det betyr at du må opprette en lokal database med nødvendig tabellstruktur (`brukere`, `stillinger`, `soknader`) før applikasjonen fungerer fullt.

### 3) Katalogstruktur og paths
Koden bruker `require_once`-stier som forventer denne layouten:

```text
soknadssystem/
├── config/db.inc.php
├── inc/*.inc.php
└── public/*.php
```

Samtidig ligger filene i dette repoet per nå flatt i rotmappen. For lokal kjøring må du enten:
- speile forventet mappestruktur lokalt, eller
- justere oppsettet i ditt lokale miljø slik at include-paths matcher.

> Dette er en kjent forutsetning i prosjektet og ikke en funksjonell bugfix i koden.

### 4) Miljøvariabler
Prosjektet bruker **ikke** `.env` i nåværende versjon. DB-innstillinger er hardkodet i `db.inc.php`.

Dersom du skal deploye prosjektet, bør du i praksis bruke miljøvariabler for:
- database host
- database user
- database password
- database name

(Dette er en anbefaling for produksjon, ikke implementert her for å unngå funksjonsendring.)

## Kjente begrensninger og antakelser
- Ingen migrations/seeding følger med i repoet.
- Ingen CSS-rammeverk eller responsivt designlag er inkludert.
- Tilgangskontroll er enkel og basert på sesjon + rollefelt.
- Feilmeldinger vises direkte i UI, og applikasjonen er tydelig utviklingsnær.
- Paths i `require_once` og redirects antar `/soknadssystem/public/...`.

## Arkitektur og design (kort)
Applikasjonen følger en lettvekts, funksjonsbasert PHP-struktur:
- **Sider (`*.php`)** håndterer request/response og rendrer HTML.
- **`*.inc.php`** kapsler databaseoperasjoner og autentiseringslogikk.
- **PDO + prepared statements** brukes for databasespørringer.
- **Sesjon** brukes til å lagre innlogget bruker og rolle.

Designet er bevisst enkelt for å gjøre flyt og ansvar lett å forstå i undervisnings-/portfolio-kontekst.

## Screenshots
> Legg gjerne inn faktiske skjermbilder her for å gjøre repoet mer attraktivt visuelt.

- `docs/screenshots/forside.png` *(placeholder)*
- `docs/screenshots/stilling-detalj.png` *(placeholder)*
- `docs/screenshots/mine-soknader.png` *(placeholder)*
- `docs/screenshots/arbeidsgiver-soknader.png` *(placeholder)*

## Hva dette prosjektet demonstrerer for arbeidsgivere
- Praktisk forståelse av autentisering, autorisasjon og sesjonshåndtering.
- Databaseorientert utvikling med SQL, relasjoner og CRUD-flyt.
- Input-validering og grunnleggende sikkerhetsgrep (escaping, passordhashing).
- Rollebasert brukeropplevelse (søker vs. arbeidsgiver).
- Evne til å strukturere en liten fullstack-applikasjon med tydelig ansvar per modul.

## Forslag til GitHub Wiki-struktur
Se forslag i [`docs/WIKI_STRUCTURE.md`](docs/WIKI_STRUCTURE.md) for en mer utdypende teknisk Wiki som supplerer README.

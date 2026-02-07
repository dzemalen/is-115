-- Minimal database schema for søknadssystemet

CREATE TABLE brukere (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rolle ENUM('søker', 'arbeidsgiver') NOT NULL,
    fornavn VARCHAR(100) NOT NULL,
    etternavn VARCHAR(100) NOT NULL,
    epost VARCHAR(190) NOT NULL UNIQUE,
    passordhash VARCHAR(255) NOT NULL,
    telefon VARCHAR(20) NULL,
    studiested VARCHAR(150) NULL,
    studieprogram VARCHAR(150) NULL,
    semester VARCHAR(20) NULL,
    cv_tekst TEXT NULL,
    feilede_logginn INT NOT NULL DEFAULT 0,
    låst_til DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE stillinger (
    id INT AUTO_INCREMENT PRIMARY KEY,
    arbeidsgiver_id INT NOT NULL,
    tittel VARCHAR(200) NOT NULL,
    beskrivelse TEXT NOT NULL,
    sted VARCHAR(150) NOT NULL,
    aktiv TINYINT(1) NOT NULL DEFAULT 1,
    opprettet DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stillinger_bruker
        FOREIGN KEY (arbeidsgiver_id) REFERENCES brukere(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE soknader (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stilling_id INT NOT NULL,
    soker_id INT NOT NULL,
    melding TEXT NOT NULL,
    status ENUM('mottatt', 'vurdering', 'avslått', 'tilbud') NOT NULL DEFAULT 'mottatt',
    opprettet DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_soknader_stilling
        FOREIGN KEY (stilling_id) REFERENCES stillinger(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_soknader_bruker
        FOREIGN KEY (soker_id) REFERENCES brukere(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

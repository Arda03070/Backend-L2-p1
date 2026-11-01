# Database Specificatie Tabel

Database: `breezedemo`

Deze specificatie beschrijft de zes specificatietabellen van het magazijn van Jamin, inclusief relaties en systeemvelden.

Systeemvelden op iedere tabel:
- `IsActief` BIT NOT NULL DEFAULT 1
- `Opmerking` VARCHAR(255) NULL
- `DatumAangemaakt` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
- `DatumGewijzigd` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

Winegums (`Product.Id = 10`) heeft `IsActief = 0`. Quality Street (`Leverancier.Id = 6`) is toegevoegd en heeft nog geen leveringen.

Het create-script `database/create_script.sql` maakt de tabellen, foreign keys, testdata en stored procedures aan.

## Product

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| Id | INT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van het product |
| Naam | VARCHAR(255) | Nee |  |  | Naam van het product |
| Barcode | CHAR(13) | Nee |  |  | Barcode van het product |
| IsActief | BIT | Nee |  | DEFAULT 1 | Of het product nog wordt geproduceerd |
| Opmerking | VARCHAR(255) | Ja |  |  | Vrije toelichting |
| DatumAangemaakt | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) | Aanmaakmoment |
| DatumGewijzigd | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6) | Wijzigingsmoment |

## Magazijn

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| Id | INT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van het magazijnrecord |
| ProductId | INT UNSIGNED | Nee | FK |  | Verwijzing naar Product.Id |
| VerpakkingsEenheidInKilogram | DECIMAL(5,2) | Nee |  |  | Verpakkingseenheid in kilogram |
| AantalAanwezig | INT UNSIGNED | Ja |  |  | Aantal stuks op voorraad (NULL = geen voorraad) |
| IsActief | BIT | Nee |  | DEFAULT 1 | Of het record actief is |
| Opmerking | VARCHAR(255) | Ja |  |  | Vrije toelichting |
| DatumAangemaakt | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) | Aanmaakmoment |
| DatumGewijzigd | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6) | Wijzigingsmoment |

**Relatie:** Magazijn.ProductId → Product.Id

## Allergeen

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| Id | INT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van het allergeen |
| Naam | VARCHAR(255) | Nee |  |  | Naam van het allergeen |
| Omschrijving | VARCHAR(255) | Nee |  |  | Uitleg over het allergeen |
| IsActief | BIT | Nee |  | DEFAULT 1 | Of het record actief is |
| Opmerking | VARCHAR(255) | Ja |  |  | Vrije toelichting |
| DatumAangemaakt | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) | Aanmaakmoment |
| DatumGewijzigd | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6) | Wijzigingsmoment |

## ProductPerAllergeen

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| Id | INT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van de koppeling |
| ProductId | INT UNSIGNED | Nee | FK |  | Verwijzing naar Product.Id |
| AllergeenId | INT UNSIGNED | Nee | FK |  | Verwijzing naar Allergeen.Id |
| IsActief | BIT | Nee |  | DEFAULT 1 | Of het record actief is |
| Opmerking | VARCHAR(255) | Ja |  |  | Vrije toelichting |
| DatumAangemaakt | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) | Aanmaakmoment |
| DatumGewijzigd | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6) | Wijzigingsmoment |

**Relaties:**
- ProductPerAllergeen.ProductId → Product.Id
- ProductPerAllergeen.AllergeenId → Allergeen.Id

## Leverancier

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| Id | INT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van de leverancier |
| Naam | VARCHAR(255) | Nee |  |  | Naam van de leverancier |
| ContactPersoon | VARCHAR(255) | Nee |  |  | Naam van de contactpersoon |
| LeverancierNummer | VARCHAR(20) | Nee |  |  | Uniek leveranciernummer |
| Mobiel | VARCHAR(20) | Nee |  |  | Mobiel nummer van de contactpersoon |
| IsActief | BIT | Nee |  | DEFAULT 1 | Of het record actief is |
| Opmerking | VARCHAR(255) | Ja |  |  | Vrije toelichting |
| DatumAangemaakt | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) | Aanmaakmoment |
| DatumGewijzigd | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6) | Wijzigingsmoment |

## ProductPerLeverancier

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| Id | INT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van de levering |
| LeverancierId | INT UNSIGNED | Nee | FK |  | Verwijzing naar Leverancier.Id |
| ProductId | INT UNSIGNED | Nee | FK |  | Verwijzing naar Product.Id |
| DatumLevering | DATE | Nee |  |  | Datum waarop geleverd is |
| Aantal | INT UNSIGNED | Nee |  |  | Geleverd aantal |
| DatumEerstVolgendeLevering | DATE | Ja |  |  | Verwachte datum van de volgende levering |
| IsActief | BIT | Nee |  | DEFAULT 1 | Of het record actief is |
| Opmerking | VARCHAR(255) | Ja |  |  | Vrije toelichting |
| DatumAangemaakt | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) | Aanmaakmoment |
| DatumGewijzigd | DATETIME(6) | Nee |  | DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6) | Wijzigingsmoment |

**Relaties:**
- ProductPerLeverancier.LeverancierId → Leverancier.Id
- ProductPerLeverancier.ProductId → Product.Id

## Stored procedures

De applicatie gebruikt PDO om deze procedures aan te roepen:

- `sp_GetLeveranciersOverzicht`
- `sp_GetLeverancierById`
- `sp_GetGeleverdeProducten`
- `sp_GetProductVoorLevering`
- `sp_AddProductLevering`

## users

| Kolom | Datatype | Null | Key | Extra | Omschrijving |
| --- | --- | --- | --- | --- | --- |
| id | BIGINT UNSIGNED | Nee | PK | AUTO_INCREMENT | Unieke sleutel van de gebruiker |
| name | VARCHAR(255) | Nee |  |  | Naam van de gebruiker |
| email | VARCHAR(255) | Nee | UNIQUE |  | E-mailadres voor inloggen |
| email_verified_at | TIMESTAMP | Ja |  |  | Moment van e-mailverificatie |
| password | VARCHAR(255) | Nee |  |  | Gehashed wachtwoord |
| role | VARCHAR(255) | Nee |  | Default: magazijn_medewerker | Rol van de gebruiker |
| remember_token | VARCHAR(100) | Ja |  |  | Token voor "onthoud mij" |
| created_at | TIMESTAMP | Ja |  |  | Aanmaakdatum |
| updated_at | TIMESTAMP | Ja |  |  | Wijzigingsdatum |

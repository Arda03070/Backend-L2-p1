-- Step: 01
-- Goal: Create a new database breezedemo
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            13-09-2026      Arda ozkaya              New
-- 02            29-10-2026      Arda ozkaya              Systeemvelden, Quality Street, stored procedures
-- **********************************************************************************/

DROP DATABASE IF EXISTS `breezedemo`;

CREATE DATABASE IF NOT EXISTS `breezedemo`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `breezedemo`;

SET FOREIGN_KEY_CHECKS = 0;


-- Step: 02
-- Goal: Create table Product
-- **********************************************************************************

DROP TABLE IF EXISTS Product;

CREATE TABLE IF NOT EXISTS Product
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(255)                    NOT NULL
   ,Barcode             CHAR(13)                        NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(255)                        NULL
   ,DatumAangemaakt     DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
   ,DatumGewijzigd      DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

   ,CONSTRAINT      PK_Product_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


INSERT INTO Product (Id, Naam, Barcode, IsActief)
VALUES
     (1, 'Mintnopjes', '8719587231278', 1)
    ,(2, 'Schoolkrijt', '8719587326713', 1)
    ,(3, 'Honingdrop', '8719587327836', 1)
    ,(4, 'Zure Beren', '8719587321441', 1)
    ,(5, 'Cola Flesjes', '8719587321237', 1)
    ,(6, 'Turtles', '8719587322245', 1)
    ,(7, 'Witte Muizen', '8719587328256', 1)
    ,(8, 'Reuzen Slangen', '8719587325641', 1)
    ,(9, 'Zoute Rijen', '8719587322739', 1)
    ,(10, 'Winegums', '8719587327527', 0)
    ,(11, 'Drop Munten', '8719587322345', 1)
    ,(12, 'Kruis Drop', '8719587322265', 1)
    ,(13, 'Zoute Ruitjes', '8719587323256', 1);


-- Step: 03
-- Goal: Create table Allergeen
-- **********************************************************************************

DROP TABLE IF EXISTS Allergeen;

CREATE TABLE IF NOT EXISTS Allergeen
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(255)                    NOT NULL
   ,Omschrijving        VARCHAR(255)                    NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(255)                        NULL
   ,DatumAangemaakt     DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
   ,DatumGewijzigd      DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

   ,CONSTRAINT      PK_Allergeen_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


INSERT INTO Allergeen (Id, Naam, Omschrijving)
VALUES
     (1, 'Gluten', 'Dit product bevat gluten')
    ,(2, 'Gelatine', 'Dit product bevat gelatine')
    ,(3, 'AZO-Kleurstof', 'Dit product bevat AZO-kleurstoffen')
    ,(4, 'Lactose', 'Dit product bevat lactose')
    ,(5, 'Soja', 'Dit product bevat soja');


-- Step: 04
-- Goal: Create table Leverancier
-- **********************************************************************************

DROP TABLE IF EXISTS Leverancier;

CREATE TABLE IF NOT EXISTS Leverancier
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(255)                    NOT NULL
   ,ContactPersoon      VARCHAR(255)                    NOT NULL
   ,LeverancierNummer   VARCHAR(20)                     NOT NULL
   ,Mobiel              VARCHAR(20)                     NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(255)                        NULL
   ,DatumAangemaakt     DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
   ,DatumGewijzigd      DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

   ,CONSTRAINT      PK_Leverancier_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


INSERT INTO Leverancier (Id, Naam, ContactPersoon, LeverancierNummer, Mobiel)
VALUES
     (1, 'Venco', 'Bert van Linge', 'L1029384719', '06-28493827')
    ,(2, 'Astra Sweets', 'Jasper del Monte', 'L1029284315', '06-39398734')
    ,(3, 'Haribo', 'Sven Stalman', 'L1029324748', '06-24383291')
    ,(4, 'Basset', 'Joyce Stelterberg', 'L1023845773', '06-48293823')
    ,(5, 'De Bron', 'Remco Veenstra', 'L1023857736', '06-34291234')
    ,(6, 'Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456');


-- Step: 05
-- Goal: Create table Magazijn
-- **********************************************************************************

DROP TABLE IF EXISTS Magazijn;

CREATE TABLE IF NOT EXISTS Magazijn
(
    Id                              INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,ProductId                       INT             UNSIGNED        NOT NULL
   ,VerpakkingsEenheidInKilogram     DECIMAL(5, 2)                   NOT NULL
   ,AantalAanwezig                  INT             UNSIGNED            NULL    DEFAULT NULL
   ,IsActief                        BIT                             NOT NULL    DEFAULT 1
   ,Opmerking                       VARCHAR(255)                        NULL
   ,DatumAangemaakt                 DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
   ,DatumGewijzigd                  DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

   ,CONSTRAINT      PK_Magazijn_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_Magazijn_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB;


INSERT INTO Magazijn (Id, ProductId, VerpakkingsEenheidInKilogram, AantalAanwezig)
VALUES
     (1, 1, 5.00, 453)
    ,(2, 2, 2.50, 400)
    ,(3, 3, 5.00, 1)
    ,(4, 4, 1.00, 800)
    ,(5, 5, 3.00, 234)
    ,(6, 6, 2.00, 345)
    ,(7, 7, 1.00, 795)
    ,(8, 8, 10.00, 233)
    ,(9, 9, 2.50, 123)
    ,(10, 10, 3.00, NULL)
    ,(11, 11, 2.00, 367)
    ,(12, 12, 1.00, 467)
    ,(13, 13, 5.00, 20);


-- Step: 06
-- Goal: Create table ProductPerAllergeen
-- **********************************************************************************

DROP TABLE IF EXISTS ProductPerAllergeen;

CREATE TABLE IF NOT EXISTS ProductPerAllergeen
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,ProductId           INT             UNSIGNED        NOT NULL
   ,AllergeenId         INT             UNSIGNED        NOT NULL
   ,IsActief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(255)                        NULL
   ,DatumAangemaakt     DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
   ,DatumGewijzigd      DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

   ,CONSTRAINT      PK_ProductPerAllergeen_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_ProductPerAllergeen_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
   ,CONSTRAINT      FK_ProductPerAllergeen_AllergeenId_Allergeen_Id  FOREIGN KEY (AllergeenId) REFERENCES Allergeen(Id)
) ENGINE=InnoDB;


INSERT INTO ProductPerAllergeen (Id, ProductId, AllergeenId)
VALUES
     (1, 1, 2)
    ,(2, 1, 1)
    ,(3, 1, 3)
    ,(4, 3, 4)
    ,(5, 6, 5)
    ,(6, 9, 2)
    ,(7, 9, 5)
    ,(8, 10, 2)
    ,(9, 12, 4)
    ,(10, 13, 1)
    ,(11, 13, 4)
    ,(12, 13, 5);


-- Step: 07
-- Goal: Create table ProductPerLeverancier
-- **********************************************************************************

DROP TABLE IF EXISTS ProductPerLeverancier;

CREATE TABLE IF NOT EXISTS ProductPerLeverancier
(
    Id                              INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,LeverancierId                   INT             UNSIGNED        NOT NULL
   ,ProductId                       INT             UNSIGNED        NOT NULL
   ,DatumLevering                   DATE                            NOT NULL
   ,Aantal                          INT             UNSIGNED        NOT NULL
   ,DatumEerstVolgendeLevering      DATE                                NULL    DEFAULT NULL
   ,IsActief                        BIT                             NOT NULL    DEFAULT 1
   ,Opmerking                       VARCHAR(255)                        NULL
   ,DatumAangemaakt                 DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
   ,DatumGewijzigd                  DATETIME(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)

   ,CONSTRAINT      PK_ProductPerLeverancier_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_ProductPerLeverancier_LeverancierId_Leverancier_Id  FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id)
   ,CONSTRAINT      FK_ProductPerLeverancier_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB;


INSERT INTO ProductPerLeverancier
(
     Id
    ,LeverancierId
    ,ProductId
    ,DatumLevering
    ,Aantal
    ,DatumEerstVolgendeLevering
)
VALUES
     (1, 1, 1, '2024-11-09', 23, '2024-11-16')
    ,(2, 1, 1, '2024-11-18', 21, '2024-11-25')
    ,(3, 1, 2, '2024-11-09', 12, '2024-11-16')
    ,(4, 1, 3, '2024-11-10', 11, '2024-11-17')
    ,(5, 2, 4, '2024-11-14', 16, '2024-11-21')
    ,(6, 2, 4, '2024-11-21', 23, '2024-11-28')
    ,(7, 2, 5, '2024-11-14', 45, '2024-11-21')
    ,(8, 2, 6, '2024-11-14', 30, '2024-11-21')
    ,(9, 3, 7, '2024-11-12', 12, '2024-11-19')
    ,(10, 3, 7, '2024-11-19', 23, '2024-11-26')
    ,(11, 3, 8, '2024-11-10', 12, '2024-11-17')
    ,(12, 3, 9, '2024-11-11', 1, '2024-11-18')
    ,(13, 4, 10, '2024-11-16', 24, '2024-11-30')
    ,(14, 5, 11, '2024-11-10', 47, '2024-11-17')
    ,(15, 5, 11, '2024-11-19', 60, '2024-11-26')
    ,(16, 5, 12, '2024-11-11', 45, NULL)
    ,(17, 5, 13, '2024-11-12', 23, NULL);


SET FOREIGN_KEY_CHECKS = 1;


-- Step: 08
-- Goal: Stored procedures for leverancieroverzicht and productleveringen
-- **********************************************************************************

DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht;
DROP PROCEDURE IF EXISTS sp_GetLeverancierById;
DROP PROCEDURE IF EXISTS sp_GetGeleverdeProducten;
DROP PROCEDURE IF EXISTS sp_GetProductVoorLevering;
DROP PROCEDURE IF EXISTS sp_AddProductLevering;


DELIMITER $$

CREATE PROCEDURE sp_GetLeveranciersOverzicht()
BEGIN
    SELECT
         l.Id
        ,l.Naam
        ,l.ContactPersoon
        ,l.LeverancierNummer
        ,l.Mobiel
        ,COUNT(DISTINCT ppl.ProductId) AS AantalProducten
    FROM Leverancier AS l
    LEFT JOIN ProductPerLeverancier AS ppl
        ON ppl.LeverancierId = l.Id
    GROUP BY
         l.Id
        ,l.Naam
        ,l.ContactPersoon
        ,l.LeverancierNummer
        ,l.Mobiel
    ORDER BY
         AantalProducten DESC
        ,l.Naam ASC;
END$$


CREATE PROCEDURE sp_GetLeverancierById(IN pLeverancierId INT UNSIGNED)
BEGIN
    SELECT
         Id
        ,Naam
        ,ContactPersoon
        ,LeverancierNummer
        ,Mobiel
    FROM Leverancier
    WHERE Id = pLeverancierId;
END$$


CREATE PROCEDURE sp_GetGeleverdeProducten(IN pLeverancierId INT UNSIGNED)
BEGIN
    SELECT
         p.Id AS ProductId
        ,p.Naam AS ProductNaam
        ,m.AantalAanwezig
        ,MAX(ppl.DatumLevering) AS DatumLaatsteLevering
    FROM ProductPerLeverancier AS ppl
    INNER JOIN Product AS p
        ON p.Id = ppl.ProductId
    LEFT JOIN Magazijn AS m
        ON m.ProductId = p.Id
    WHERE ppl.LeverancierId = pLeverancierId
    GROUP BY
         p.Id
        ,p.Naam
        ,m.AantalAanwezig
    ORDER BY m.AantalAanwezig DESC;
END$$


CREATE PROCEDURE sp_GetProductVoorLevering(
    IN pLeverancierId INT UNSIGNED,
    IN pProductId INT UNSIGNED
)
BEGIN
    SELECT
         l.Id AS LeverancierId
        ,l.Naam AS LeverancierNaam
        ,l.ContactPersoon
        ,l.LeverancierNummer
        ,l.Mobiel
        ,p.Id AS ProductId
        ,p.Naam AS ProductNaam
        ,CAST(p.IsActief AS UNSIGNED) AS ProductIsActief
    FROM Leverancier AS l
    INNER JOIN Product AS p
        ON p.Id = pProductId
    WHERE l.Id = pLeverancierId;
END$$


CREATE PROCEDURE sp_AddProductLevering(
    IN pLeverancierId INT UNSIGNED,
    IN pProductId INT UNSIGNED,
    IN pAantal INT UNSIGNED,
    IN pDatumEerstVolgendeLevering DATE
)
BEGIN
    DECLARE vIsActief TINYINT(1) DEFAULT 1;
    DECLARE vProductNaam VARCHAR(255);
    DECLARE vLeverancierNaam VARCHAR(255);

    SELECT
         CAST(p.IsActief AS UNSIGNED)
        ,p.Naam
        ,l.Naam
    INTO
         vIsActief
        ,vProductNaam
        ,vLeverancierNaam
    FROM Product AS p
    INNER JOIN Leverancier AS l
        ON l.Id = pLeverancierId
    WHERE p.Id = pProductId;

    IF vIsActief = 0 THEN
        SELECT
             'inactive' AS Status
            ,CONCAT(
                'Het product ',
                vProductNaam,
                ' van de leverancier ',
                vLeverancierNaam,
                ' wordt niet meer geproduceerd'
             ) AS Message;
    ELSE
        INSERT INTO ProductPerLeverancier
        (
             LeverancierId
            ,ProductId
            ,DatumLevering
            ,Aantal
            ,DatumEerstVolgendeLevering
            ,IsActief
            ,Opmerking
            ,DatumAangemaakt
            ,DatumGewijzigd
        )
        VALUES
        (
             pLeverancierId
            ,pProductId
            ,CURDATE()
            ,pAantal
            ,pDatumEerstVolgendeLevering
            ,1
            ,NULL
            ,NOW(6)
            ,NOW(6)
        );

        UPDATE Magazijn
        SET
             AantalAanwezig = IFNULL(AantalAanwezig, 0) + pAantal
            ,DatumGewijzigd = NOW(6)
        WHERE ProductId = pProductId;

        SELECT
             'ok' AS Status
            ,NULL AS Message;
    END IF;
END$$

DELIMITER ;

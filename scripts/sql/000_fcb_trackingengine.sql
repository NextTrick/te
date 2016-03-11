-- MySQL Script generated by MySQL Workbench
-- 03/11/16 15:57:17
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema fcb_trackingengine
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `fcb_carrier_carrier`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_carrier_carrier` ;

CREATE TABLE IF NOT EXISTS `fcb_carrier_carrier` (
  `carrierId` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `creationDate` DATETIME NOT NULL,
  `alias` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`carrierId`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_service_service`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_service_service` ;

CREATE TABLE IF NOT EXISTS `fcb_service_service` (
  `serviceId` INT NOT NULL AUTO_INCREMENT,
  `endpoint` VARCHAR(256) NOT NULL,
  `creationDate` DATETIME NOT NULL,
  `editionDate` DATETIME NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active:1, Inactive:0',
  PRIMARY KEY (`serviceId`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_apikey_apikey`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_apikey_apikey` ;

CREATE TABLE IF NOT EXISTS `fcb_apikey_apikey` (
  `apikeyId` INT NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(250) NOT NULL,
  `creationDate` DATETIME NOT NULL,
  `profileId` INT NOT NULL,
  PRIMARY KEY (`apikeyId`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_statistic_service_apikey`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_statistic_service_apikey` ;

CREATE TABLE IF NOT EXISTS `fcb_statistic_service_apikey` (
  `serviceApikeyId` INT NOT NULL AUTO_INCREMENT,
  `apikeyId` INT NOT NULL,
  `serviceId` INT NOT NULL,
  `counter` INT NOT NULL DEFAULT 0,
  `creationDate` DATETIME NOT NULL,
  PRIMARY KEY (`serviceApikeyId`),
  INDEX `fk_Fcb_Ts_EndPointApikey_FcbTs_Ts_ApiKey_idx` (`apikeyId` ASC),
  INDEX `fk_Fcb_Ts_ServiceApikey_Fcb_Ts_Service1_idx` (`serviceId` ASC),
  CONSTRAINT `fk_Fcb_Ts_EndPointApikey_FcbTs_Ts_ApiKey`
    FOREIGN KEY (`apikeyId`)
    REFERENCES `fcb_apikey_apikey` (`apikeyId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Fcb_Ts_ServiceApikey_Fcb_Ts_Service1`
    FOREIGN KEY (`serviceId`)
    REFERENCES `fcb_service_service` (`serviceId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_service_multitracking`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_service_multitracking` ;

CREATE TABLE IF NOT EXISTS `fcb_service_multitracking` (
  `multitrackingId` INT NOT NULL AUTO_INCREMENT,
  `trackingKeys` TEXT NULL,
  `apikeyId` INT NOT NULL,
  `creationDate` DATETIME NOT NULL,
  `edtionDate` DATETIME NULL,
  `token` VARCHAR(250) NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: active, 0: inactive',
  PRIMARY KEY (`multitrackingId`),
  INDEX `fk_fcb_service_multitracking_fcb_apikey_apikey1_idx` (`apikeyId` ASC),
  CONSTRAINT `fk_fcb_service_multitracking_fcb_apikey_apikey1`
    FOREIGN KEY (`apikeyId`)
    REFERENCES `fcb_apikey_apikey` (`apikeyId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_search_search`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_search_search` ;

CREATE TABLE IF NOT EXISTS `fcb_search_search` (
  `searchId` INT NOT NULL AUTO_INCREMENT,
  `carrierId` INT NULL,
  `serviceApikeyId` INT NOT NULL,
  `multitrackingId` INT NULL,
  `trackingKey` VARCHAR(250) NULL,
  `creationDate` DATETIME NOT NULL,
  `ip` VARCHAR(16) NULL COMMENT 'Search creator IP',
  PRIMARY KEY (`searchId`),
  INDEX `fk_fcb_search_search_fcb_carrier_carrier1_idx` (`carrierId` ASC),
  INDEX `fk_fcb_search_search_fcb_statistic_service_apikey1_idx` (`serviceApikeyId` ASC),
  INDEX `fk_fcb_search_search_fcb_service_multitracking1_idx` (`multitrackingId` ASC),
  CONSTRAINT `fk_fcb_search_search_fcb_carrier_carrier1`
    FOREIGN KEY (`carrierId`)
    REFERENCES `fcb_carrier_carrier` (`carrierId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fcb_search_search_fcb_statistic_service_apikey1`
    FOREIGN KEY (`serviceApikeyId`)
    REFERENCES `fcb_statistic_service_apikey` (`serviceApikeyId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fcb_search_search_fcb_service_multitracking1`
    FOREIGN KEY (`multitrackingId`)
    REFERENCES `fcb_service_multitracking` (`multitrackingId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_search_track`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_search_track` ;

CREATE TABLE IF NOT EXISTS `fcb_search_track` (
  `trackId` INT NOT NULL AUTO_INCREMENT,
  `searchId` INT NOT NULL,
  `carrierId` INT NOT NULL,
  `trackingKey` VARCHAR(250) NOT NULL,
  `creationDate` DATETIME NOT NULL,
  `editionDate` DATETIME NULL,
  `statusCreationDateTime` DATETIME NULL,
  `statusCode` VARCHAR(4) NULL,
  `statusDescription` VARCHAR(250) NULL,
  `statusLocStateOrProvinceCode` VARCHAR(4) NULL,
  `statusLocCountryCode` VARCHAR(4) NULL,
  `statusLocCountryName` VARCHAR(45) NULL,
  `track` TEXT NULL,
  PRIMARY KEY (`trackId`),
  INDEX `fk_fcb_search_track_fcb_carrier_carrier1_idx` (`carrierId` ASC),
  INDEX `fk_fcb_search_track_fcb_search_search1_idx` (`searchId` ASC),
  CONSTRAINT `fk_fcb_search_track_fcb_carrier_carrier1`
    FOREIGN KEY (`carrierId`)
    REFERENCES `fcb_carrier_carrier` (`carrierId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fcb_search_track_fcb_search_search1`
    FOREIGN KEY (`searchId`)
    REFERENCES `fcb_search_search` (`searchId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fcb_carrier_request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fcb_carrier_request` ;

CREATE TABLE IF NOT EXISTS `fcb_carrier_request` (
  `requestId` INT NOT NULL AUTO_INCREMENT,
  `searchId` INT NOT NULL,
  `request` TEXT NOT NULL,
  `response` TEXT NULL,
  `creationDate` DATETIME NOT NULL,
  PRIMARY KEY (`requestId`),
  INDEX `fk_fcb_carrier_request_fcb_search_search1_idx` (`searchId` ASC),
  CONSTRAINT `fk_fcb_carrier_request_fcb_search_search1`
    FOREIGN KEY (`searchId`)
    REFERENCES `fcb_search_search` (`searchId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

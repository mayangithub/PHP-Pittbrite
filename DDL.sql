SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `yam14` DEFAULT CHARACTER SET latin1 ;
USE `yam14` ;


-- -----------------------------------------------------
-- Table `yam14`.`F_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_user` (
  `u_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `date_created` TIMESTAMP NOT NULL DEFAULT NOW(),
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`u_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_organizer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_organizer` (
  `o_id` INT NOT NULL AUTO_INCREMENT COMMENT '		',
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL,
  `u_id` INT NOT NULL,
  PRIMARY KEY (`o_id`),
  INDEX `u_id_idx` (`u_id` ASC),
  CONSTRAINT `user_id`
    FOREIGN KEY (`u_id`)
    REFERENCES `yam14`.`F_user` (`u_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_venue`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_venue` (
  `v_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(225) NOT NULL,
  `address` VARCHAR(225) NOT NULL,
  `address_2` VARCHAR(225) NULL,
  `city` VARCHAR(45) NOT NULL,
  `state` VARCHAR(45) NOT NULL DEFAULT 'PA',
  `postal_code` INT(5) NULL,
  `country` VARCHAR(45) NOT NULL DEFAULT 'United States',
  `u_id` INT NOT NULL,
  PRIMARY KEY (`v_id`),
  INDEX `user_idx` (`u_id` ASC),
  CONSTRAINT `user`
    FOREIGN KEY (`u_id`)
    REFERENCES `yam14`.`F_user` (`u_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_category` (
  `c_id` INT NOT NULL AUTO_INCREMENT,
  `c_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`c_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_topic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_topic` (
  `tag_id` INT NOT NULL AUTO_INCREMENT,
  `tag_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`tag_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_event` (
  `e_id` BIGINT NOT NULL AUTO_INCREMENT,
  `e_title` VARCHAR(225) NOT NULL,
  `e_description` TEXT NULL,
  `c_id` INT NULL,
  `tag_id` INT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `created` DATETIME NOT NULL,
  `modified` TIMESTAMP NOT NULL DEFAULT NOW(),
  `capacity` INT NOT NULL DEFAULT 0,
  `num_attendee_rows` INT NOT NULL DEFAULT 0,
  `status` ENUM('live','started','ended') NOT NULL,
  `venue_id` INT NOT NULL,
  `organizer_id` INT NOT NULL,
  PRIMARY KEY (`e_id`),
  INDEX `organizer_id_idx` (`organizer_id` ASC),
  INDEX `tag_idx` (`tag_id` ASC),
  INDEX `cat_idx` (`c_id` ASC),
  INDEX `venue_idx` (`venue_id` ASC),
  CONSTRAINT `organizer`
    FOREIGN KEY (`organizer_id`)
    REFERENCES `yam14`.`F_organizer` (`o_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `venue`
    FOREIGN KEY (`venue_id`)
    REFERENCES `yam14`.`F_venue` (`v_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `cat`
    FOREIGN KEY (`c_id`)
    REFERENCES `yam14`.`F_category` (`c_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `tag`
    FOREIGN KEY (`tag_id`)
    REFERENCES `yam14`.`F_topic` (`tag_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_ticket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_ticket` (
  `t_id` INT NOT NULL AUTO_INCREMENT,
  `e_id` BIGINT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL DEFAULT 'Not specified.',
  `price` DECIMAL(2) NOT NULL DEFAULT 0,
  `quantity_available` INT NOT NULL,
  `quantity_sold` VARCHAR(45) NULL DEFAULT 0,
  PRIMARY KEY (`t_id`, `e_id`),
  INDEX `e_id_idx` (`e_id` ASC),
  CONSTRAINT `evt_id`
    FOREIGN KEY (`e_id`)
    REFERENCES `yam14`.`F_event` (`e_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `yam14`.`F_registration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yam14`.`F_registration` (
  `r_id` BIGINT NOT NULL AUTO_INCREMENT,
  `u_id` INT NOT NULL,
  `e_id` BIGINT NOT NULL,
  `t_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(2) NOT NULL DEFAULT 0,
  `total` DECIMAL(2) NOT NULL DEFAULT 0,
  `reg_time` TIMESTAMP NOT NULL DEFAULT now(),
  PRIMARY KEY (`r_id`),
  INDEX `userid_idx` (`u_id` ASC),
  INDEX `event_idx` (`e_id` ASC),
  INDEX `ticket_idx` (`t_id` ASC),
  CONSTRAINT `userid`
    FOREIGN KEY (`u_id`)
    REFERENCES `yam14`.`F_user` (`u_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `event`
    FOREIGN KEY (`e_id`)
    REFERENCES `yam14`.`F_event` (`e_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ticket`
    FOREIGN KEY (`t_id`)
    REFERENCES `yam14`.`F_ticket` (`t_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- comics_serie
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comics_serie`;

CREATE TABLE `comics_serie`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- comics_issue
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comics_issue`;

CREATE TABLE `comics_issue`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `serie_id` INTEGER NOT NULL,
    `pub_date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `comics_issue_FI_1` (`serie_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;


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
    `cv_id` VARCHAR(10),
    `cv_url` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `comics_serie_U_1` (`cv_id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- comics_issue
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comics_issue`;

CREATE TABLE `comics_issue`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `issue_number` VARCHAR(10),
    `serie_id` INTEGER NOT NULL,
    `pub_date` DATE NOT NULL,
    `cv_id` VARCHAR(10),
    `cv_url` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `comics_issue_FI_1` (`serie_id`),
    CONSTRAINT `comics_issue_FK_1`
        FOREIGN KEY (`serie_id`)
        REFERENCES `comics_serie` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- comics_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comics_user`;

CREATE TABLE `comics_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `auth_key` VARCHAR(32),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- comics_user_serie
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comics_user_serie`;

CREATE TABLE `comics_user_serie`
(
    `user_id` INTEGER NOT NULL,
    `serie_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`serie_id`),
    INDEX `comics_user_serie_FI_2` (`serie_id`),
    CONSTRAINT `comics_user_serie_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `comics_user` (`id`),
    CONSTRAINT `comics_user_serie_FK_2`
        FOREIGN KEY (`serie_id`)
        REFERENCES `comics_serie` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;

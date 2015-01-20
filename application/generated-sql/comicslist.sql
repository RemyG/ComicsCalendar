
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
    `added_on` DATETIME DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    UNIQUE INDEX `comics_serie_u_5ec686` (`cv_id`)
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
    INDEX `comics_issue_fi_cce4fc` (`serie_id`),
    CONSTRAINT `comics_issue_fk_cce4fc`
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
    `last_seen_on` DATETIME DEFAULT '0000-00-00 00:00:00',
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
    INDEX `comics_user_serie_fi_cce4fc` (`serie_id`),
    CONSTRAINT `comics_user_serie_fk_652b9c`
        FOREIGN KEY (`user_id`)
        REFERENCES `comics_user` (`id`),
    CONSTRAINT `comics_user_serie_fk_cce4fc`
        FOREIGN KEY (`serie_id`)
        REFERENCES `comics_serie` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- comics_user_issue
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comics_user_issue`;

CREATE TABLE `comics_user_issue`
(
    `user_id` INTEGER NOT NULL,
    `issue_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`issue_id`),
    INDEX `comics_user_issue_fi_ce1159` (`issue_id`),
    CONSTRAINT `comics_user_issue_fk_652b9c`
        FOREIGN KEY (`user_id`)
        REFERENCES `comics_user` (`id`),
    CONSTRAINT `comics_user_issue_fk_ce1159`
        FOREIGN KEY (`issue_id`)
        REFERENCES `comics_issue` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;

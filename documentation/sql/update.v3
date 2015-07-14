#Add version table, INOPIAE 2015-07-14
CREATE TABLE IF NOT EXISTS `dbversion` (
    `dbversion_id` bigint(20) NOT NULL AUTO_INCREMENT,
    `dbversion` int(11) NOT NULL,
    `install_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`dbversion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


#insert infromation into dbversion, INOPIAE 2015-07-14
INSERT INTO `dbversion` (`dbversion`, `install_date`) VALUES (0, '2014-11-26');
INSERT INTO `dbversion` (`dbversion`, `install_date`) VALUES (1, '2014-11-26');
INSERT INTO `dbversion` (`dbversion`, `install_date`) VALUES (2, '2015-02-15');


#add indices and foreign keys to tables, INOPIAE 2015-07-14
ALTER TABLE `cacertuser` CHANGE COLUMN `deleted_by` `deleted_by` INT(11) NULL DEFAULT NULL ;
UPDATE `cacertuser` SET `deleted_by` = NULL WHERE `deleted_by` = 0;

ALTER TABLE `cacertuser` ADD INDEX `created_by` (`created_by` ASC);
ALTER TABLE `cacertuser` ADD INDEX `deleted_by` (`deleted_by` ASC);

ALTER TABLE `cacertuser` ADD CONSTRAINT `FK_created_by`
    FOREIGN KEY (`created_by`)
    REFERENCES `coauditor` (`coauditor_id`);
ALTER TABLE `cacertuser` ADD CONSTRAINT `FK_deleted_by`
    FOREIGN KEY (`deleted_by`)
    REFERENCES `coauditor` (`coauditor_id`);


ALTER TABLE `coaudit_refdata` ADD INDEX `session_id` (`coaudit_session_id` ASC);
ALTER TABLE `coaudit_refdata` ADD CONSTRAINT `FK_session_id`
    FOREIGN KEY (`coaudit_session_id`)
    REFERENCES `coauditsession` (`session_id`);


ALTER TABLE `coauditor` ADD INDEX `email` (`email`(255) ASC);
ALTER TABLE `coauditor` ADD INDEX `created_by` (`created_by` ASC);
ALTER TABLE `coauditor` ADD INDEX `last_change_by` (`last_change_by` ASC);

#Not implented as it causes an error
/* ALTER TABLE `coauditor` ADD CONSTRAINT `FK_created_by`
    FOREIGN KEY (`created_by`)
    REFERENCES `coauditor` (`coauditor_id`);
ALTER TABLE `coauditor` ADD CONSTRAINT `FK_last_changed_by`
    FOREIGN KEY (`last_change_by`)
    REFERENCES `coauditor` (`coauditor_id`); */


ALTER TABLE `result` CHANGE COLUMN `deleted_by` `deleted_by` INT(11) NULL DEFAULT NULL ;
UPDATE `result` SET `deleted_by` = NULL WHERE `deleted_by` = 0;

ALTER TABLE `result` ADD INDEX `deleted_by` (`deleted_by` ASC);

ALTER TABLE `result` ADD CONSTRAINT `result_ibfk_6`
    FOREIGN KEY (`deleted_by`)
    REFERENCES `coauditor` (`coauditor_id`);


ALTER TABLE `session_topics` ADD INDEX `topic_no` (`topic_no` ASC);

#insert infromation about this update into dbversion, INOPIAE 2015-07-14
INSERT INTO `dbversion` (`dbversion`) VALUES (3);

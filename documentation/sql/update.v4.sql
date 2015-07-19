#Alter default timestamp settings, INOPIAE 2015-07-19
ALTER TABLE `cacertuser`
CHANGE COLUMN `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE .`coauditor`
CHANGE COLUMN `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE .`result`
CHANGE COLUMN `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE .`dbversion`
CHANGE COLUMN `install_date` `install_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

#insert infromation about this update into dbversion, INOPIAE 2015-07-19
INSERT INTO `dbversion` (`dbversion`) VALUES (4);

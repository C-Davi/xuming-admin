ALTER TABLE yueche_user add COLUMN token VARCHAR(100) not null DEFAULT '' comment 'token' after pass;
ALTER TABLE `yueche_user` ADD `time_out` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `token`;
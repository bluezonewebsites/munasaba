ALTER TABLE `user` ADD `activation_code` INT(10) NULL AFTER `pass_v`;
ALTER TABLE `user` ADD `active_notification` TINYINT(1) NOT NULL DEFAULT '1' AFTER `activation_code`;

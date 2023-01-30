ALTER TABLE `user` ADD `activation_code` INT(10) NULL AFTER `pass_v`;
ALTER TABLE `user` ADD `active_notification` TINYINT(1) NOT NULL DEFAULT '1' AFTER `activation_code`;
ALTER TABLE `user` CHANGE `pic` `pic` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';
ALTER TABLE `likes_on_rates` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT NULL;

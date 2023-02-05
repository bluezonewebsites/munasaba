ALTER TABLE `user` ADD `activation_code` INT(10) NULL AFTER `pass_v`;
ALTER TABLE `user` ADD `active_notification` TINYINT(1) NOT NULL DEFAULT '1' AFTER `activation_code`;
ALTER TABLE `user` CHANGE `pic` `pic` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';
ALTER TABLE `likes_on_rates` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `comment_reports` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT NULL;


ALTER TABLE `questions_reports` ADD `created_at` TIMESTAMP NULL AFTER `rdate`, ADD `updated_at` TIMESTAMP NULL AFTER `created_at`, ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `chat_reports` ADD `created_at` TIMESTAMP NULL AFTER `rdate`, ADD `updated_at` TIMESTAMP NULL AFTER `created_at`;
ALTER TABLE `rooms` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;



ALTER TABLE `comment_reports` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `reply_reports` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT NULL;

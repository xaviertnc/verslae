ALTER TABLE `devices`
CHANGE `id` `id` int NOT NULL AUTO_INCREMENT FIRST,
CHANGE `user_id` `user_id` smallint unsigned NOT NULL AFTER `id`,
CHANGE `type_id` `type_id` smallint unsigned NOT NULL AFTER `user_id`,
CHANGE `deviceno` `deviceno` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `note`,
CHANGE `bank` `bank` varchar(15) COLLATE 'utf8_general_ci' NULL AFTER `email`,
CHANGE `utility_id` `utility_id` smallint unsigned NOT NULL AFTER `loan`,
CHANGE `created_by` `created_by` smallint unsigned NULL AFTER `email_notify`,
CHANGE `updated_by` `updated_by` smallint unsigned NULL AFTER `created_by`,
CHANGE `deleted_by` `deleted_by` smallint unsigned NULL AFTER `updated_by`,
COMMENT='Reduce field sizes to save space.'
REMOVE PARTITIONING;

ALTER TABLE `devices`
CHANGE `created_at` `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `deleted_by`,
CHANGE `updated_at` `updated_at` datetime NULL AFTER `created_at`,
COMMENT='Fix potential future MySql version issues with "00-00-00 00:00" default timestamp values'
REMOVE PARTITIONING;

ALTER TABLE `devices`
DROP `meterno`,
COMMENT='Remove redundant field after discontinuation of the original MrPrepaid app (aka Tshwane app)'
REMOVE PARTITIONING;

ALTER TABLE `devices`
ADD `credit` float(8,2) NOT NULL DEFAULT '0' AFTER `deviceno`,
COMMENT='Needed once we start using auto-payment system, but also in general needed'
REMOVE PARTITIONING;

ALTER TABLE `notifications`
RENAME TO `sms`,
COMMENT=''
REMOVE PARTITIONING;

ALTER TABLE `sms`
CHANGE `device_id` `source_id` varchar(32) COLLATE 'utf8_general_ci' NOT NULL AFTER `id`,
CHANGE `from` `from` varchar(12) COLLATE 'utf8_general_ci' NOT NULL AFTER `message`,
CHANGE `sent_to` `sent_to` varchar(12) COLLATE 'utf8_general_ci' NOT NULL AFTER `from`,
DROP `logged`,
COMMENT=''
REMOVE PARTITIONING;

ALTER TABLE `payments`
CHANGE `transaction_id` `transaction_id` int(10) unsigned NULL COMMENT 'payment assigned if not NULL' AFTER `id`,
CHANGE `reference` `reference` varchar(255) COLLATE 'utf8_general_ci' NULL COMMENT 'sms message or deviceno' AFTER `amount`,
ADD `reference_from` varchar(12) COLLATE 'utf8_general_ci' NULL COMMENT 'username or cell number' AFTER `reference`,
ADD `note` text COLLATE 'utf8_general_ci' NULL AFTER `reference_from`,
COMMENT=''
REMOVE PARTITIONING;

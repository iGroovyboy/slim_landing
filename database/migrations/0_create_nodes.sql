CREATE TABLE `nodes` (
    `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
    `key` TINYTEXT,
    `value` LONGTEXT,
    `type` VARCHAR(64) NOT NULL,
    `parent_id` BIGINT,
    `order` BIGINT,
    PRIMARY KEY `id`
) ENGINE=MyISAM;

CREATE TABLE `nodes` (
    `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
    `key` TINYTEXT,
    `value` LONGTEXT,
    `type` VARCHAR(64) NOT NULL,
    `parent_key` BIGINT,
    `order` BIGINT,
    PRIMARY KEY `id`,
    CONSTRAINT uc_key_parent UNIQUE(key,parent_key)
) ENGINE=MyISAM;

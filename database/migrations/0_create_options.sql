CREATE TABLE `options` (
     `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
     `name` TINYTEXT,
     `value` LONGTEXT,
     PRIMARY KEY `id`,
) ENGINE=InnoDB;

INSERT INTO options (id, name, value) VALUES (1, 'debug', '1');
INSERT INTO options (id, name, value) VALUES (2, 'theme', 'unfold');
INSERT INTO options (id, name, value) VALUES (3, 'theme_dev', '1');
INSERT INTO options (id, name, value) VALUES (4, 'useDateForUploadsDir', '1');
INSERT INTO options (id, name, value) VALUES (5, 'allowedFileExtensions', '["jpg","png","webp","gif","pdf","pptx","xlsx","docx","doc","xls","ppt","mp3","ogg","mpeg"]');
INSERT INTO options (id, name, value) VALUES (6, 'cache_enabled', '1');
INSERT INTO options (id, name, value) VALUES (7, 'cache_expires', '604800');
INSERT INTO options (id, name, value) VALUES (8, 'cache_twig_enabled', '0');

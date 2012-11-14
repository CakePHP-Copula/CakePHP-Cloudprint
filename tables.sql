CREATE TABLE IF NOT EXISTS
    `tokens` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `user_id` int(11) unsigned NOT NULL,
    `access_token` text NOT NULL,
    `modified` datetime DEFAULT NULL,
    `refresh_token` text NOT NULL,
    PRIMARY KEY (`id`),
   UNIQUE KEY (`user_id`));

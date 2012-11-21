CREATE TABLE IF NOT EXISTS
    `tokens` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `user_id` int(11) unsigned NOT NULL,
    `api` varchar(255) NOT NULL,
    `access_token` text NOT NULL,
    `modified` datetime DEFAULT NULL,
    `refresh_token` text NOT NULL,
    PRIMARY KEY (`id`),
   UNIQUE KEY (`user_id`));

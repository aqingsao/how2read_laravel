CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` tinyint(1) NOT NULL DEFAULT 0, -- 来源，0:PC、1:微信
  `name` varchar(64),
  `nickname` varchar(64),
  `email` varchar(64),
  `remember_token` varchar(128),
  `openid` varchar(32),
  `gender` tinyint(1),
  `headimgurl` varchar(256),
  `country` varchar(64),
  `province` varchar(64),
  `city` varchar(64),
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE users
  ADD CONSTRAINT uc_users_name UNIQUE (name);

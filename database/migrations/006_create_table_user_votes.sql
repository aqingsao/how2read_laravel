CREATE TABLE `user_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `voted_count` int(11) NOT NULL DEFAULT 0,
  `correct_count` int(11) NOT NULL DEFAULT 0,
  `rate` smallint(5) unsigned NOT NULL DEFAULT 0,    -- 单位是万分比
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE user_votes
  ADD CONSTRAINT fk_user_votes_user_id FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

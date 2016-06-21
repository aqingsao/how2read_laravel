CREATE TABLE `user_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `correct_count` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE user_votes
  ADD CONSTRAINT fk_user_votes_user_id FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE user_votes
  ADD CONSTRAINT fk_user_votes_issue_id FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`);

ALTER TABLE user_votes
  ADD CONSTRAINT uc_user_votes_user_id_issue_id UNIQUE (user_id, issue_id);

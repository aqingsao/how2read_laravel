CREATE TABLE `question_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choice_id` int(11) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE question_votes
  ADD CONSTRAINT fk_question_votes_issue_id FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`);
ALTER TABLE question_votes
  ADD CONSTRAINT fk_question_votes_question_id FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);
ALTER TABLE question_votes
  ADD CONSTRAINT fk_question_votes_choice_id FOREIGN KEY (`choice_id`) REFERENCES `choices` (`id`);
ALTER TABLE question_votes
  ADD CONSTRAINT fk_question_votes_user_id FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

CREATE TABLE `question_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE question_tags
  ADD CONSTRAINT fk_question_tags_question_id FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);
ALTER TABLE question_tags
  ADD CONSTRAINT fk_question_tags_tag_id FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
ALTER TABLE question_tags
  ADD CONSTRAINT uc_question_tags_question_id_tag_id UNIQUE (question_id, tag_id);

CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL,         -- 0：未发布；1：已发布
  `user_id` int(11),                    -- 哪个用户提交的单词
  `issue_id` int(11),                   -- 第几期
  `source_type` tinyint(1) NOT NULL DEFAULT 0,    -- 正确读音来源，0: 标准发音，1：官网，2：wiki
  `source_url` varchar(256),    -- 正确读音的网址
  `remark` varchar(256),    -- 备注
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE questions
  ADD CONSTRAINT uc_questions_name UNIQUE (name);
ALTER TABLE questions
  ADD CONSTRAINT fk_questions_user_id_users_id FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE questions
  ADD CONSTRAINT fk_questions_issue_id_issues_id FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`);
ALTER TABLE questions
  ADD INDEX idx_questions_name(name);
  

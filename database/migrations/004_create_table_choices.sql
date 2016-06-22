CREATE TABLE `choices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_ipa` varchar(128),
  `name_alias` varchar(128),
  `name_cn` varchar(128),
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0,    -- 正确读音来源，0: 英语标准发音，1：官网，2：wiki
  `url` varchar(256),    -- 正确读音的网址
  `audio_url` varchar(256),    -- 正确读音的音频地址
  `description` varchar(256),    -- 备注
  `question_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE choices
  ADD CONSTRAINT uc_questions_name UNIQUE (name);
ALTER TABLE choices
  ADD CONSTRAINT fk_choices_question_id_questions_id FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

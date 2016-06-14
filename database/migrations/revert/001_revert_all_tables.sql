ALTER TABLE user_votes
  DROP FOREIGN KEY fk_user_votes_user_id;
DROP table user_votes;

ALTER TABLE question_votes
  DROP FOREIGN KEY fk_question_votes_question_id;
ALTER TABLE question_votes
  DROP FOREIGN KEY fk_question_votes_choice_id;
ALTER TABLE question_votes
  DROP FOREIGN KEY fk_question_votes_user_id;
DROP table question_votes;

ALTER TABLE choices
  DROP FOREIGN KEY fk_choices_question_id_questions_id;
DROP table choices;

ALTER TABLE questions
  DROP FOREIGN KEY uc_questions_name UNIQUE (name);
ALTER TABLE questions
  DROP FOREIGN KEY fk_questions_user_id_users_id;
ALTER TABLE questions
  DROP FOREIGN KEY fk_questions_issue_id_issues_id;
DROP table questions;

DROP table password_resets;

DROP table issues;

DROP table users;  

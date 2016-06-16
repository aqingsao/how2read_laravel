insert into users(id, name, email, password, created_at, updated_at) values
  (1, 'aqingsir', 'aqingsir@163.com', '123456', '2016-06-12 12:00:00', '2016-06-12 12:00:00');

insert into issues(id, description, created_at) values
  (1, '一', '2016-06-12 12:00:00'),
  (2, '二', '2016-06-12 12:00:00');

insert into questions(id, name, description, status, issue_id, created_at, updated_at) values
  (1, 'Nginx', 'a web/reverse proxy server', 0, 1, '2016-06-12 12:00:00', '2016-06-12 12:00:00'),
  (2, 'Cache', 'cache', 0, 1, '2016-06-12 12:00:00','2016-06-12 12:00:00');

insert into choices(id, name, name1, is_correct, question_id, created_at, updated_at) values
(1, 'Engine-X', '恩贞-埃克斯', 1, 1, '2016-06-12 12:00:00', '2016-06-12 12:00:00'), 
  (2, 'Engine-ks', '恩贞-克斯', 0, 1, '2016-06-12 12:00:00', '2016-06-12 12:00:00'),
  (3, 'Engine-s', '恩贞-斯', 0, 1, '2016-06-12 12:00:00', '2016-06-12 12:00:00'),
  (4, 'kaichi', '开吃', 0, 2, '2016-06-12 12:00:00', '2016-06-12 12:00:00'), 
  (5, 'kaishi', '开始', 1, 2, '2016-06-12 12:00:00', '2016-06-12 12:00:00');

CREATE TABLE `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,   -- 0：未发布；1：已发布
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
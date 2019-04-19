DROP TABLE IF EXISTS `discount_rules`;
DROP TABLE IF EXISTS `holidays_list`;
DROP TABLE IF EXISTS `clients_cards_operations`;
DROP TABLE IF EXISTS `clients_cards`;
DROP TABLE IF EXISTS `users`;

# Дамп таблицы users
# ------------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы clients_cards
# ------------------------------------------------------------
CREATE TABLE `clients_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `discount_rate` decimal(10,2) NOT NULL DEFAULT '1.00',
  `card_number` varchar(255) NOT NULL,
  `discount_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `buy_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_number` (`card_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы clients_cards_operations
# ------------------------------------------------------------
CREATE TABLE `clients_cards_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `buy_sum` decimal(10,2) DEFAULT NULL,
  `percent_change` decimal(10,2) DEFAULT NULL,
  `status_change` varchar(255) DEFAULT NULL,
  `issued_by` int(11) DEFAULT NULL,
  `issue_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operated_by` int(11) NOT NULL,
  `operation_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operation_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`),
  KEY `issued_by` (`issued_by`),
  KEY `operated_by` (`operated_by`),
  FOREIGN KEY (`card_id`) REFERENCES `clients_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`operated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы discount_rules
# ------------------------------------------------------------
CREATE TABLE `discount_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checksum` decimal(10,0) NOT NULL,
  `bonus_rate` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы holidays_list
# ------------------------------------------------------------
CREATE TABLE `holidays_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(255) NOT NULL,
  `holiday_date` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `login`, `pass_hash`, `position`)
VALUES
	(1,'master','$2y$10$mHbwkMMpBihFygjtemZV0u9vx5VBljg5XOtx2K2et7LIbP/xJvOji','god'),
	(2,'kiko','kloi','0'),
	(12,'test','$2y$10$7qCLJ/C9YVT7zqpczUbeOOm8yNu.DOf5A0vbMDv/zvUXcuWUoEhrq','0'),
	(17,'123','$2y$10$LN8XjpMuC3b850pdayHmC.B7k3ddDl5d/JMiYeIKN94Arj9HFWozy','lol'),
	(18,'111','$2y$10$ZvXx2dbukzTqBv5mnskmNO5AO6Dh.VJ2hGPMLtQdZWXz416Bh8LTi','clown'),
	(19,'ooo','$2y$10$7B/TY5Rt2Rc2ZnUyGwLocerbRoiay/poPxmqDn7pki6ndj9PpHIRW','clown'),
	(20,'petya','$2y$10$zycZEUBxs9jCp1.ZQkMc8OPFVeMXVGw2TQVXWUQzhn9MKU1bTFn6K','lolo');

INSERT INTO `clients_cards` (`id`, `name`, `surname`, `gender`, `phone`, `birthday`, `discount_rate`, `card_number`, `discount_total`, `buy_total`, `status`)
VALUES
	(50,'Илья','Putin','m','89788546525','1996-04-29',15.00,'454211',4200.00,24000.00,1);

INSERT INTO `clients_cards_operations` (`id`, `card_id`, `buy_sum`, `percent_change`, `status_change`, `issued_by`, `issue_datetime`, `operated_by`, `operation_datetime`, `operation_type`)
VALUES
	(2,50,NULL,7.50,'1',1,'2019-04-18 18:55:20',1,'2019-04-18 18:55:20','Создание карты'),
	(59,50,4000.00,7.50,NULL,1,'2019-04-18 21:36:16',1,'2019-04-18 21:36:16','Покупка'),
	(60,50,4000.00,7.50,NULL,1,'2019-04-18 21:38:01',1,'2019-04-18 21:38:01','Покупка'),
	(61,50,4000.00,10.00,NULL,1,'2019-04-18 21:38:05',1,'2019-04-18 21:38:05','Покупка'),
	(62,50,4000.00,20.00,NULL,1,'2019-04-18 21:38:48',1,'2019-04-18 21:38:48','Покупка в праздничный день: 18 апреля'),
	(63,50,4000.00,30.00,NULL,1,'2019-04-18 21:39:34',1,'2019-04-18 21:39:34','Покупка в праздничный день: 18 апреля'),
	(64,50,4000.00,30.00,NULL,1,'2019-04-18 21:40:33',1,'2019-04-18 21:40:33','Покупка в праздничный день: 18 апреля');

INSERT INTO `discount_rules` (`id`, `checksum`, `bonus_rate`)
VALUES
	(1,10000,10),
	(2,20000,15);

INSERT INTO `holidays_list` (`id`, `holiday_name`, `holiday_date`)
VALUES
	(1,'8 марта','08-03'),
	(2,'18 апреля','18-04');



DROP TABLE IF EXISTS holidays_list;
DROP TABLE IF EXISTS discount_rates;
DROP TABLE IF EXISTS discount_rules;
DROP TABLE IF EXISTS cards_operations;
DROP TABLE IF EXISTS cards;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;

# Дамп таблицы users
# ------------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `position` tinyint(255) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы clients
# ------------------------------------------------------------
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы cards
# ------------------------------------------------------------
CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `discount_rate_id` int(11) NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `discount_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `buy_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_number` (`card_number`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы cards_operations
# ------------------------------------------------------------
CREATE TABLE `cards_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `buy_sum` decimal(10,0) DEFAULT NULL,
  `bonus_in` decimal(10,0) DEFAULT NULL,
  `bonus_out` decimal(10,0) DEFAULT NULL,
  `percent_change` decimal(10,0) DEFAULT NULL,
  `status_change` varchar(255) DEFAULT NULL,
  `issued_by` int(11) NOT NULL,
  `issue_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operated_by` int(11) NOT NULL,
  `operation_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`),
  KEY `issued_by` (`issued_by`),
  KEY `operated_by` (`operated_by`),
  CONSTRAINT `cards_operations_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cards_operations_ibfk_2` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cards_operations_ibfk_3` FOREIGN KEY (`operated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы discount_rules
# ------------------------------------------------------------
CREATE TABLE `discount_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checksum` decimal(10,0) NOT NULL,
  `bonus_rate` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы discount_rates
# ------------------------------------------------------------
CREATE TABLE `discount_rates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_rules_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Дамп таблицы holidays_list
# ------------------------------------------------------------
CREATE TABLE `holidays_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(255) NOT NULL,
  `holiday_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `login`, `pass_hash`, `position`)
VALUES
	(1,'arik','$2y$10$Kj3x2Ce/CqZoK13.amGU/.aTj3vlAQM4IsZeYPy7w2V7fOol/RJFq',0),
	(2,'kiko','kloi',0),
	(12,'test','$2y$10$7qCLJ/C9YVT7zqpczUbeOOm8yNu.DOf5A0vbMDv/zvUXcuWUoEhrq',0);

	INSERT INTO `clients` (`id`, `name`, `surname`, `phone`, `birthday`)
VALUES
	(1,'Иван','Абрамов','8978116838','2000-02-16'),
	(2,'Олег','Равоенов','89782003020','2010-04-06'),
	(3,'Игорь','Алоев','89786662255','2009-04-21'),
	(4,'Яков','Сидоров','89786663536','2001-04-18');
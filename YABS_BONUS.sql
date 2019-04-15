DROP TABLE IF EXISTS holidays_list;
DROP TABLE IF EXISTS bonus_rules;
DROP TABLE IF EXISTS cards_operations;
DROP TABLE IF EXISTS cards;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;

-- --------------------------------------------------------
--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL UNIQUE,
  `pass_hash` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL UNIQUE,
  `birthday` date NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cards`
--

CREATE TABLE `cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `card_number` varchar(255) UNIQUE NOT NULL ,
  `client_id` int NOT NULL,
  `bonus_rate` decimal NOT NULL,
  `bonus_total` decimal DEFAULT NULL,
  `buy_total` decimal DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cards_operations`
--

CREATE TABLE `cards_operations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `card_id` int NOT NULL,
  `buy_sum` decimal DEFAULT NULL,
  `bonus_in` decimal DEFAULT NULL,
  `bonus_out` decimal DEFAULT NULL,
  `percent_change` decimal DEFAULT NULL,
  `status_change` varchar(255) DEFAULT NULL,
  `issued_by` int NOT NULL,
  `issue_datetime` timestamp DEFAULT CURRENT_TIMESTAMP,
  `operated_by` int NOT NULL,
  `operation_datetime` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (operated_by) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `holidays_list`
--

CREATE TABLE `holidays_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(255) NOT NULL,
  `holiday_date` date NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bonus_rules`
--

CREATE TABLE `bonus_rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `checksum` decimal NOT NULL,
  `bonus_rate` decimal NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--
-- INSERT INTO `users` (`id`, `login`, `pass_hash`, `name`, `position`) VALUES
-- (1, 'arik', 'lolik', 'Гриша', 'Манагер'),
-- (2, 'kiko', 'kloi', 'Олег', 'Директор');
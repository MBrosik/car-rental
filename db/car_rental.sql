-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 22 Lis 2021, 17:46
-- Wersja serwera: 10.4.20-MariaDB
-- Wersja PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `car_rental`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `brands`
--

INSERT INTO `brands` (`id`, `brand`) VALUES
(2, 'Bentley'),
(5, 'Bugatti'),
(6, 'Fiat'),
(3, 'Ford'),
(4, 'McLaren'),
(1, 'Skoda');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `model` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `img` text COLLATE utf8_polish_ci NOT NULL,
  `price_per_day` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `cars`
--

INSERT INTO `cars` (`id`, `brand_id`, `model`, `img`, `price_per_day`) VALUES
(1, 1, 'Fabia', 'https://ocdn.eu/pulscms-transforms/1/jrHk9kpTURBXy83MTg3ZmU0ZjU4ODg3OTVlNDY0YzU3NjRjMWU3NzcyZS5qcGeSlQMAI80EAM0CQJMFzQH0zQEYgqEwAaExAA', 250),
(2, 2, 'Continental GT Speed', 'https://ireland.apollo.olxcdn.com/v1/files/eyJmbiI6Im92NjNkeGQ0NnNvcTEtT1RPTU9UT1BMIiwidyI6W3siZm4iOiJ3ZzRnbnFwNnkxZi1PVE9NT1RPUEwiLCJzIjoiMTYiLCJwIjoiMTAsLTEwIiwiYSI6IjAifV19.tCDe4Pn89R4rT0dxkdKx9362pTSEyh46oySQ6RlPfHY/image;s=1080x720', 1000),
(3, 3, 'Mustang TG', 'https://globalelitecar.pl/wp-content/uploads/2020/03/ford-mustang-01.jpg', 1500),
(4, 4, 'Artura', 'https://www.auto-data.net/images/f78/McLaren-Artura_1.jpg', 24000),
(5, 5, 'Veyron', 'https://d-mf.ppstatic.pl/art/5s/r7/ej3438kk444gwc4w8ksgs/veyron.1200.jpg', 2000),
(6, 6, '126p (Maluch)', 'https://d-mf.ppstatic.pl/art/74/h3/zxf8luskg40s4kcoss8ok/51a3bc305e6e2-p.1200.jpg', 0.5),
(7, 6, 'Seicento', 'https://upload.wikimedia.org/wikipedia/commons/c/c6/1999_Fiat_Seicento_Mia_Front.jpg', 15000);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `reserve_time` datetime NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Wyzwalacze `reservation`
--
DELIMITER $$
CREATE TRIGGER `MOVE TO ARCHIV` BEFORE DELETE ON `reservation` FOR EACH ROW INSERT INTO 
reservation_archive(user_id, car_id, reserve_time, start_time, end_time, status_id)
VALUES(OLD.user_id, OLD.car_id, OLD.reserve_time, OLD.start_time, OLD.end_time,
       IF(OLD.status_id=2, 3, 4))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reservation_archive`
--

CREATE TABLE `reservation_archive` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `reserve_time` datetime NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `status_enum`
--

CREATE TABLE `status_enum` (
  `id` int(11) NOT NULL,
  `status` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `status_enum`
--

INSERT INTO `status_enum` (`id`, `status`) VALUES
(3, 'canceled'),
(4, 'ended'),
(1, 'reserved'),
(2, 'waiting');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(30) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `user_type_id` int(11) NOT NULL DEFAULT 3,
  `activated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `user_type_id`, `activated`) VALUES
(1, '123', '??Fr?pU?<6?oz?', 2, 1),
(2, 'admin', '?????z???9 ?B?V', 1, 1),
(4, 'asd', '? ?z??L/3?3?l??u', 3, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `type` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_types`
--

INSERT INTO `user_types` (`id`, `type`) VALUES
(1, 'admin'),
(2, 'moderator'),
(3, 'user');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `variables`
--

CREATE TABLE `variables` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `value` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `variables`
--

INSERT INTO `variables` (`id`, `name`, `value`) VALUES
(1, 'time_difference', 0),
(2, 'date_now', 1637599602);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand` (`brand`);

--
-- Indeksy dla tabeli `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indeksy dla tabeli `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `status` (`status_id`);

--
-- Indeksy dla tabeli `reservation_archive`
--
ALTER TABLE `reservation_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `status` (`status_id`);

--
-- Indeksy dla tabeli `status_enum`
--
ALTER TABLE `status_enum`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indeksy dla tabeli `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- Indeksy dla tabeli `variables`
--
ALTER TABLE `variables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT dla tabeli `reservation_archive`
--
ALTER TABLE `reservation_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT dla tabeli `status_enum`
--
ALTER TABLE `status_enum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `variables`
--
ALTER TABLE `variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Ograniczenia dla tabeli `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`),
  ADD CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status_enum` (`id`);

--
-- Ograniczenia dla tabeli `reservation_archive`
--
ALTER TABLE `reservation_archive`
  ADD CONSTRAINT `reservation_archive_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservation_archive_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`),
  ADD CONSTRAINT `reservation_archive_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status_enum` (`id`);

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`);

DELIMITER $$
--
-- Zdarzenia
--
CREATE DEFINER=`root`@`localhost` EVENT `edit_date` ON SCHEDULE EVERY 5 SECOND STARTS '2021-11-12 21:54:32' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
		SET @time = now() + INTERVAL (SELECT value FROM variables WHERE name = 'time_difference') DAY;
		UPDATE variables SET value=UNIX_TIMESTAMP(@time) WHERE name='date_now';
	END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

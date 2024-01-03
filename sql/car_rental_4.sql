-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2024 at 03:44 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `car_id` int(11) NOT NULL,
  `image` varchar(250) DEFAULT NULL,
  `plate_number` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `insurance_number` varchar(20) DEFAULT NULL,
  `insurance_type` varchar(15) DEFAULT NULL,
  `rental_price` double NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_price` double NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`car_id`, `image`, `plate_number`, `model`, `brand`, `color`, `capacity`, `insurance_number`, `insurance_type`, `rental_price`, `purchase_date`, `purchase_price`, `created_date`) VALUES
(2, '6590f21b9ba6e.jpg', 'TSR21168', 'C40 Rechearge', 'Volvo', 'Fjord Blue', 5, 'INF409080', 'full', 89.99, '2023-10-10', 13000, '2023-12-30 05:29:20'),
(3, '6590f3ccc4106.jpg', 'TSR21168', '2016 MKX', 'Lincoln', 'Gray', 5, 'INF409083', 'full', 99.99, '2019-02-08', 16000, '2023-12-30 05:32:50'),
(4, '6590f1996aff0.jpg', 'GN-431-HC', '2024 Model S', 'Tesla', 'Sapphire Blue', 5, 'INF009123', 'full', 99.99, '2023-10-10', 13000, '2023-12-30 08:03:01'),
(5, '6590f2df84c50.jpg', 'AXP 706', '2024 XLE Crown', 'Toyota', 'Supersonic Red', 5, 'INF409081', 'full', 89.99, '2023-10-10', 13000, '2023-12-31 04:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `carmaintenance`
--

CREATE TABLE `carmaintenance` (
  `maintenance_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carmaintenance`
--

INSERT INTO `carmaintenance` (`maintenance_id`, `car_id`, `description`, `start_date`, `end_date`, `cost`, `created_date`) VALUES
(1, 2, 'Gear box repair. Polish the body.', '2023-12-27', '2024-01-03', 534, '2023-12-30 08:51:17'),
(6, 4, 'Oil change', '2023-12-28', '2023-12-28', 50, '2024-01-02 14:51:16'),
(7, 2, 'replace battery', '2023-12-07', '2023-12-07', 500, '2024-01-02 14:53:10'),
(8, 2, 'brake fluid exchange', '2023-12-28', '2023-12-28', 73.5, '2024-01-02 14:53:54'),
(9, 3, 'break replacement', '2023-12-29', '2024-01-01', 500, '2024-01-02 14:54:38'),
(10, 5, 'Polishing', '2023-12-06', '2023-12-07', 40.89, '2024-01-02 14:55:08');

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rental_start_date` date NOT NULL,
  `rental_end_date` date NOT NULL,
  `rental_price` double NOT NULL,
  `amount` double NOT NULL,
  `request_status` varchar(15) NOT NULL DEFAULT 'pending',
  `request_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`rental_id`, `user_id`, `car_id`, `rental_start_date`, `rental_end_date`, `rental_price`, `amount`, `request_status`, `request_date`) VALUES
(1, 4, 4, '2023-12-29', '2023-12-29', 99.99, 299.97, 'C', '2023-12-30 08:47:15'),
(2, 3, 2, '2024-01-04', '2024-01-11', 89.99, 629.93, 'P', '2023-12-31 09:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `roadtax`
--

CREATE TABLE `roadtax` (
  `road_tax_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roadtax`
--

INSERT INTO `roadtax` (`road_tax_id`, `car_id`, `amount`, `payment_date`) VALUES
(1, 2, 500, '2023-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `test_id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `DateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `user_type` varchar(10) NOT NULL DEFAULT 'user',
  `photo` varchar(250) DEFAULT NULL,
  `passport_number` varchar(20) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `user_type`, `photo`, `passport_number`, `contact_number`) VALUES
(1, 'Administrator', 'admin@email.com', '96e79218965eb72c92a549dd5a330112', 'staff', '659564108096e.jpg', '', ''),
(2, 'Scott Dean', 'sd@email.com', '96e79218965eb72c92a549dd5a330112', 'staff', '659562d84891d.jpg', '', '0988789978'),
(3, 'Ashley Cooper', 'ac@email.com', '96e79218965eb72c92a549dd5a330112', 'user', '65956298446f6.jpeg', 'MA123456', '123245465'),
(4, 'Emma Hicks', 'eh@email.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', '6595611b8383b.jpg', '', ''),
(5, 'William Snowflake', 'ws@gmail.com', '96e79218965eb72c92a549dd5a330112', 'user', '65956207643c4.jpg', '', ''),
(8, 'Jack Ma', 'jm@gmail.com', '96e79218965eb72c92a549dd5a330112', 'staff', 'jm-gmail-com.jpg', 'CH998801', '987654321');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `carmaintenance`
--
ALTER TABLE `carmaintenance`
  ADD PRIMARY KEY (`maintenance_id`),
  ADD KEY `maintenance_id` (`maintenance_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `rental_id` (`rental_id`),
  ADD KEY `user_id` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `roadtax`
--
ALTER TABLE `roadtax`
  ADD PRIMARY KEY (`road_tax_id`),
  ADD KEY `road_tax_id` (`road_tax_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `carmaintenance`
--
ALTER TABLE `carmaintenance`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roadtax`
--
ALTER TABLE `roadtax`
  MODIFY `road_tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carmaintenance`
--
ALTER TABLE `carmaintenance`
  ADD CONSTRAINT `carmaintenance_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `rental_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `roadtax`
--
ALTER TABLE `roadtax`
  ADD CONSTRAINT `roadtax_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2023 at 06:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

CREATE DATABASE IF NOT EXISTS `serverside`;
USE `serverside`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serverside`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) UNSIGNED NOT NULL,
  `MushroomID` int(11) UNSIGNED NOT NULL,
  `UserID` int(11) UNSIGNED DEFAULT NULL,
  `CommentText` text NOT NULL,
  `CommentTimestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `is_hidden` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `MushroomID`, `UserID`, `CommentText`, `CommentTimestamp`, `is_hidden`) VALUES
(2, 16, 2, 'Can I be hidden?', '2023-08-01 20:45:16', 0),
(4, 16, 2, 'You can eat this one', '2023-08-08 23:15:04', 0),
(5, 16, 2, 'These have large caps.', '2023-08-08 23:16:28', 0),
(6, 16, 2, 'I wonder if I\'ll redirect again', '2023-08-08 23:24:23', 0),
(11, 38, NULL, 'Test comment testcase3', '2023-08-24 13:43:44', 0),
(12, 16, NULL, 'This comment should be removed.', '2023-08-24 13:51:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `LocationID` int(11) UNSIGNED NOT NULL,
  `MushroomID` int(11) UNSIGNED DEFAULT NULL,
  `Locality` varchar(255) DEFAULT NULL,
  `Habitat` varchar(255) DEFAULT NULL,
  `Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`LocationID`, `MushroomID`, `Locality`, `Habitat`, `Date`) VALUES
(16, 16, 'Northern Hemisphere', 'Mycorrhizal with trees, often found in woodland areas', '2023-07-20'),
(17, 17, 'Northern Hemisphere', 'Mycorrhizal with various trees', '2023-07-20'),
(18, 18, 'Worldwide', 'Saprophytic, often found on dead wood or decaying plant matter', '2023-07-20'),
(19, 19, 'North America, Europe', 'Saprophytic, often found on decaying coniferous wood', '2023-07-20'),
(20, 20, 'Worldwide', 'Parasitic on corn plants', '2023-07-20'),
(21, 21, 'Worldwide', 'Parasitic on wheat plants', '2023-07-20'),
(22, 22, 'Worldwide', 'Saprophytic, often found in woodland areas or disturbed soil', '2023-07-20'),
(23, 23, 'North America', 'Saprophytic, often found on soil or decaying wood', '2023-07-20'),
(24, 24, 'Worldwide', 'Saprophytic, often found on decaying wood', '2023-07-20'),
(26, 26, 'Europe', 'Parasitic on poplar trees', '2023-07-20'),
(27, 27, 'Worldwide', 'Saprophytic, often found on decaying plant material or indoors as a common mold', '2023-07-20'),
(31, 31, 'North America, Europe', 'Saprophytic, often found in woodland areas or disturbed soil', '2023-07-20'),
(32, 32, 'Europe', 'Mycorrhizal with various tree species, often found in coniferous forests', '2023-07-20'),
(33, 33, 'Common in North America, including parts of the United States and Canada.', 'Saprophytic, often found on dead wood or decaying plant matter', '2023-08-15'),
(34, 34, 'North America, widespread in temperate regions', 'Saprophytic, often found in lawns, meadows, and grassy areas', '2023-08-15'),
(38, 38, '', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `mushroom_details`
--

CREATE TABLE `mushroom_details` (
  `MushroomID` int(11) UNSIGNED NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `UserID` int(11) UNSIGNED DEFAULT NULL,
  `Class` varchar(255) DEFAULT NULL,
  `Cap` varchar(255) DEFAULT NULL,
  `Gills` varchar(255) DEFAULT NULL,
  `SporePrint` varchar(255) DEFAULT NULL,
  `Stalk` varchar(255) DEFAULT NULL,
  `Flesh` varchar(255) DEFAULT NULL,
  `Odour` varchar(255) DEFAULT NULL,
  `Taste` varchar(255) DEFAULT NULL,
  `FieldIdentification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mushroom_details`
--

INSERT INTO `mushroom_details` (`MushroomID`, `Name`, `UserID`, `Class`, `Cap`, `Gills`, `SporePrint`, `Stalk`, `Flesh`, `Odour`, `Taste`, `FieldIdentification`) VALUES
(16, 'Boletus edulis', 1, 'Agaricomycetes', 'Brown', 'Absent, replaced by a spongy surface', 'Olive brown', 'Thick and solid', 'Creamy white', 'Sweet, nutty', 'Mild, nutty', 'Large, edible mushroom with a brown cap and spongy surface.'),
(17, 'Amanita muscaria', 1, 'Agaricomycetes', 'Bright red with white spots', 'White', 'White', 'White', 'White', 'Sweet, nutty', 'Bitterlike', 'Distinctive appearance with bright red cap and white spots'),
(18, 'Tremella fuciformis', 3, 'Tremellomycetes', 'Gelatinous, jelly-like, and yellow', 'Absent', 'White to pale yellow', 'Absent or indistinct', 'Gelatinous', 'Slightly sweet', 'Mild, slightly sweet', 'Gelatinous mushroom with a yellowish appearance'),
(19, 'Dacrymyces chrysospermus', 3, 'Dacrymycetes', 'Gelatinous, orange-yellow', 'Absent', 'Yellow', 'Absent or indistinct', 'Gelatinous', 'Not distinctive', 'Mild', 'Jelly-like mushroom with an orange-yellow color'),
(20, 'Ustilago maydis', 3, 'Ustilaginomycetes', 'Black, smut-like mass', 'Absent', 'Black', 'Absent or indistinct', 'Black, powdery mass', ' Not distinctive', 'Not applicable (non-edible)', 'Smut fungus on corn (maize) plants'),
(21, 'Tilletia caries', 3, 'Exobasidiomycetes', 'Blackish-brown, smut-like mass', 'Absent', 'Black', 'Absent or indistinct', 'Blackish-brown, powdery mass', 'Not distinctive', 'Not applicable (non-edible)', 'Smut fungus on wheat plants'),
(22, 'Morchella esculenta', 3, 'Pezizomycetes', 'Distinctive honeycomb-like structure, yellowish-brown', 'Absent', 'Cream to yellow', 'Whitish, hollow', 'Pale, fragile', 'Earthy, nutty', 'Nutty, rich', 'Unique and highly sought-after mushroom with a distinctive cap structure.'),
(23, 'Geopora cooperi', 3, 'Pezizomycetes', 'Cup-shaped, brown', 'Absent', 'Brown', 'Absent or very short', 'Thin and delicate', 'Earthy, musty', 'Not distinctive (inedible)', 'Small cup-shaped mushroom with a brown cap'),
(24, 'Xylaria polymorpha', 2, 'Sordariomycetes', 'Black, club-shaped or branching', 'Absent', 'Black', 'Black, club-shaped or branching', 'White, firm', 'Not distinctive', 'Not applicable (non-edible)', 'Club-shaped or branching mushroom with a black appearance'),
(26, 'Mycosphaerella populorum', 2, 'Dothideomycetes', 'Dark brown to black, disc-like', 'Absent', 'Brown to black', 'Absent or very short', 'Thin and delicate', 'Not distinctive', 'Not applicable (non-edible)', ' Dark brown to black disc-like fungus on leaves'),
(27, 'Cladosporium herbarum', 2, 'Dothideomycetes', 'Dark green to black, disc-like', 'Absent', 'Brown to black', 'Absent or very short', 'Thin and delicate', 'Not distinctive', 'Not applicable (non-edible)', 'Dark green to black disc-like fungus on plant material'),
(31, 'Morchella elata', 2, 'Dothideomycetes', 'Distinctive honeycomb-like structure, dark gray to black', 'Absent', 'Cream to yellow', 'Whitish, hollow', 'Pale, fragile', 'Earthy, nutty', 'Nutty, rich', 'Unique and highly prized mushroom with a dark gray to black honeycomb cap'),
(32, 'Sarcosphaera coronaria', 2, 'Pezizomycetes', 'Cup-shaped, dark brown to black', 'Absent', 'White to pale cream', 'Absent or very short', 'Thin and delicate', 'Earthy, slightly sweet', 'Not applicable (inedible)', 'Cup-shaped mushroom with a dark brown to black cap'),
(33, 'Omphalotus illudens', NULL, 'Agaricomycetes', 'Convex to flat', 'Adnate to slightly decurrent', 'White to pale cream', 'Central, cylindrical', 'Firm and fibrous', 'Mushroomy', 'Not applicable (non-edible)', 'The bright orange color, the presence of gills that often appear forked or irregular, and its growth on wood are characteristic features.'),
(34, 'Chlorophyllum Molybdites', 3, 'Agaricomycetes', 'Convex to flat, pale green with darker scales', 'Adnate to slightly decurrent, white to pale green', 'White to pale yellow', 'Central, cylindrical, often with a swollen base', 'Firm and white', 'Mild, somewhat mushroomy', 'Not applicable (non-edible, toxic)', 'Pale green cap with darker scales, characteristic white gills, and swollen stalk base'),
(38, 'Entoloma hochstetteri', NULL, 'Agaricomycetes', 'Convex to flat, blue-gray with radial streaks', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `PhotoID` int(11) UNSIGNED NOT NULL,
  `MushroomID` int(11) UNSIGNED DEFAULT NULL,
  `Photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`PhotoID`, `MushroomID`, `Photo`) VALUES
(11, 16, 'uploads/images/64b9905b824f7_Boletusedulis.jpg'),
(12, 17, 'uploads/images/64b994a052c7d_mushroom11.jpg'),
(13, 18, 'uploads/images/64ba16125177c_Tremella_fuciformis.jpg'),
(14, 19, 'uploads/images/64ba16af0401d_Dacrymyces_chrysospermus.jpg'),
(15, 20, 'uploads/images/64ba1773d8d03_Ustilago_maydis.jpg'),
(16, 21, 'uploads/images/64ba18218251c_Tilletia_caries.jpg'),
(18, 23, 'uploads/images/64ba194c66413_Geopora_cooperi.jpg'),
(19, 24, 'uploads/images/64baab537e005_Xylaria polymorpha.jpg'),
(21, 26, 'uploads/images/64baac40e96e1_Mycosphaerella populorum.jpg'),
(22, 27, 'uploads/images/64baaca8236df_Cladosporium herbarum.jpg'),
(24, 31, 'uploads/images/Morchella elata.jpg'),
(33, 32, 'uploads/images/64d45b50e7b32_Sarcosphaera-coronaria.jpg'),
(34, 33, 'uploads/images/64de6e72b0908_Omphalotus illudens.jpg'),
(35, 34, 'uploads/images/64e512384bb87_Chlorophyllum Molybdites.jpg'),
(39, 22, 'uploads/images/64e51d3a8e723_Morchella esculenta.jpg'),
(40, 38, 'uploads/images/64e7a4bddfada_Entoloma hochstetteri.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `substrates`
--

CREATE TABLE `substrates` (
  `SubstrateID` int(11) UNSIGNED NOT NULL,
  `MushroomID` int(11) UNSIGNED DEFAULT NULL,
  `SubstrateType` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `substrates`
--

INSERT INTO `substrates` (`SubstrateID`, `MushroomID`, `SubstrateType`) VALUES
(16, 16, 'Soil'),
(17, 17, 'Soil'),
(18, 18, 'Wood'),
(19, 19, 'Wood'),
(20, 20, 'Corn plant tissues'),
(21, 21, 'Wheat plant tissues'),
(22, 22, 'Soil'),
(23, 23, 'Soil or wood'),
(24, 24, 'Wood'),
(26, 26, 'Plant tissues'),
(27, 27, 'Plant tissues or decaying organic matter'),
(31, 31, 'Soil'),
(32, 32, 'Soil'),
(33, 33, 'Wood'),
(34, 34, 'Soil, often found in grassy areas or lawns'),
(38, 38, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) UNSIGNED NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('Field Researcher','Administrator') NOT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Role`, `Email`) VALUES
(1, 'pman', '$2y$10$WtsJI/vQcVdBu9wwHOwiruFHFChUZeYjppodVif002l71tv5VYAhW', 'Field Researcher', 'pman@gmail.edu'),
(2, 'serveruser', '$2y$10$QaY2wP4VcL0zGC.xqYCEZupDfkVCwluhCDCF0fDKTX2gHwhtEq/ZG', 'Administrator', NULL),
(3, 'auri', '$2y$10$2yS0qZbXQ9SRSyvt4pu/.u5EEeBsjlivGi1LMXWmINCAuP0hGzGBW', 'Field Researcher', 'auri44@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `MushroomID` (`MushroomID`),
  ADD KEY `comments_ibfk_2` (`UserID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`LocationID`),
  ADD KEY `MushroomID` (`MushroomID`);

--
-- Indexes for table `mushroom_details`
--
ALTER TABLE `mushroom_details`
  ADD PRIMARY KEY (`MushroomID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`PhotoID`),
  ADD KEY `MushroomID` (`MushroomID`);

--
-- Indexes for table `substrates`
--
ALTER TABLE `substrates`
  ADD PRIMARY KEY (`SubstrateID`),
  ADD KEY `MushroomID` (`MushroomID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `LocationID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `mushroom_details`
--
ALTER TABLE `mushroom_details`
  MODIFY `MushroomID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `PhotoID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `substrates`
--
ALTER TABLE `substrates`
  MODIFY `SubstrateID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`MushroomID`) REFERENCES `mushroom_details` (`MushroomID`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE SET NULL;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`MushroomID`) REFERENCES `mushroom_details` (`MushroomID`);

--
-- Constraints for table `mushroom_details`
--
ALTER TABLE `mushroom_details`
  ADD CONSTRAINT `mushroom_details_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`MushroomID`) REFERENCES `mushroom_details` (`MushroomID`);

--
-- Constraints for table `substrates`
--
ALTER TABLE `substrates`
  ADD CONSTRAINT `substrates_ibfk_1` FOREIGN KEY (`MushroomID`) REFERENCES `mushroom_details` (`MushroomID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

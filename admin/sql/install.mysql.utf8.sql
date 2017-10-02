-- DD SocialShare table SQL script
-- This will install all the the tables to run DD GMAPS LOCATIONS

--
-- Table structure for table `#__dd_socialshare`
--

CREATE TABLE IF NOT EXISTS `#__dd_socialshare` (
  `content_id` int(11) NOT NULL,
  `facebook` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `twitter` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for table `#__dd_gmaps_locations`
--
ALTER TABLE `#__dd_socialshare`
 ADD UNIQUE KEY `content_id` (`content_id`);

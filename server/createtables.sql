SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `active_nodes`
--

CREATE TABLE `active_nodes` (
  `id` bigint(20) NOT NULL,
  `ip` binary(16) NOT NULL,
  `active` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `website_ip` enum('Yes','No') NOT NULL DEFAULT 'No',
  `website_hostname` enum('Yes','No') NOT NULL DEFAULT 'No',
  `website_hostname_https` enum('Yes','No') NOT NULL DEFAULT 'No',
  `last_online` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_online` datetime NOT NULL DEFAULT current_timestamp(),
  `last_checked` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `active_nodes_stats`
--

CREATE TABLE `active_nodes_stats` (
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` enum('90%','99%','Total peers','Unique IPs in last 10 days') NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `active_nodes_stats_temp`
--

CREATE TABLE `active_nodes_stats_temp` (
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` enum('90%','99%','Total peers','Unique IPs in last 10 days') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_nodes`
--
ALTER TABLE `active_nodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip` (`ip`) USING BTREE;

--
-- Indexes for table `active_nodes_stats`
--
ALTER TABLE `active_nodes_stats`
  ADD PRIMARY KEY (`type`,`date`);

--
-- Indexes for table `active_nodes_stats_temp`
--
ALTER TABLE `active_nodes_stats_temp`
  ADD PRIMARY KEY (`type`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_nodes`
--
ALTER TABLE `active_nodes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

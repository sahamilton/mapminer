-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2016 at 12:04 AM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petshakes`
--

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `action` text COLLATE utf8_unicode_ci NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `sequence` int(11) NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `title`, `content`, `action`, `section_id`, `sequence`, `page_id`, `created_at`) VALUES
(1, 'The Festival', '<p>In the spring of 2015 a man had a vision. He saw the cast of The Taming of the Shrew and knew it had to be done. From next to nothing, a Shakespeare company was formed. The dedication and determination of a few theater geeks, and support from a generous community, launched the Petaluma Shakespeare Festival.</p><p>\r\nOur first show was a huge success. Despite the ramshakle stage and the rented costumes, the actors doubling as front house managers and marketing gurus, the performance was top notch. A few patrons claimed it was a good as Oregon, or Marin! Armed with the encouragement and inspiration of an appreciative audience, the players have decided to continue the pursuit of a legitimate Festival in Petaluma.</p>', '', 2, 0, 1, '2016-01-30 18:22:11'),
(2, 'About the Petaluma Shakespeare Festival', 'Beginning in the summer of 2013 a group of local actors, scholars, dilatants, and the like converged once a month at Aqus Café to present free readings of Shakespeare’s plays. By July of 2015 our “cry of players” took it to the next level and mounted a full blown production of "The Taming of the Shrew" which was presented at the Foundry Wharf on the banks the scenic Petaluma River, and since then a new event, the Petaluma Shakepeare Festival"  has been added to Petaluma’s family of local attractions.\n \nOur first show was a huge success. Despite the ramshakle stage and the rented costumes, the actors doubling as front house managers and marketing gurus, the performance was top notch. A few patrons claimed it was as good as Oregon, or Marin! Armed with the encouragement and inspiration of an appreciative audience, the players have decided to continue the pursuit of a legitimate Festival in Petaluma. \n \nA production group was formed to grow and develop a Shakespeare Festival in Petaluma. Below are the key members. ', '', 2, 0, 2, '2016-01-30 20:08:42'),
(3, 'Season', '<p>\r\nCras eget velit convallis, feugiat risus non, ultricies ante. Pellentesque pharetra sed leo non porttitor. Nunc porttitor quis justo id eleifend. In id felis at sem viverra fringilla a ac felis. Cras accumsan bibendum odio sed molestie. Ut tincidunt sodales justo in consectetur. Proin sed felis massa. Donec tempor faucibus sem quis dapibus. Nam blandit dolor non dolor vehicula consectetur. Praesent vulputate interdum scelerisque.\r\n</p><p>\r\nEtiam tempus nulla dolor. Quisque tincidunt odio a enim pharetra, a volutpat purus scelerisque. Nam ut magna bibendum, placerat nisi in, lacinia velit. Fusce id nisi congue, tempus urna et, facilisis orci. Sed sit amet purus pretium tellus sagittis posuere. In sed odio ante. Sed finibus elementum purus nec blandit. Nullam rutrum posuere efficitur. Aliquam erat volutpat. Nulla nec massa eu ex eleifend consequa.</p>', '', 2, 0, 3, '2016-04-06 17:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'main', 'Top Navigation', '2016-04-19 01:12:03', '2016-04-19 01:12:03'),
(2, 'foot', 'Footer', '2016-04-18 19:23:20', '0000-00-00 00:00:00'),
(3, 'company', 'Company', '2016-04-18 19:41:55', '0000-00-00 00:00:00'),
(4, 'support', 'Support', '2016-04-18 19:41:55', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `menu_page`
--

CREATE TABLE `menu_page` (
  `menu_id` int(10) UNSIGNED NOT NULL,
  `page_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu_page`
--

INSERT INTO `menu_page` (`menu_id`, `page_id`) VALUES
(1, 2),
(1, 3),
(2, 2),
(2, 17),
(3, 2),
(4, 18);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_01_30_010000_create_pages_table', 2),
('2016_01_30_010200_create_sections_table', 2),
('2016_01_30_180542_create_content_table', 2),
('2016_02_21_180247_create_quotes_table', 3),
('2016_02_21_225811_create_page_section_pivot_table', 4),
('2016_04_18_165309_create_roles_table', 5),
('2016_04_18_174806_create_menus_table', 6),
('2016_04_18_180124_create_menu_page_pivot_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `publishFrom` date NOT NULL,
  `publishTo` date NOT NULL,
  `onNav` tinyint(1) NOT NULL,
  `systemPage` tinyint(1) NOT NULL,
  `noExpire` tinyint(1) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `publishFrom`, `publishTo`, `onNav`, `systemPage`, `noExpire`, `lft`, `rgt`, `seq`, `parent_id`, `depth`, `created_at`, `updated_at`) VALUES
(1, 'Home', '/', '2016-01-01', '2020-01-01', 1, 1, 1, 3, 22, 0, NULL, NULL, '2016-04-18 20:51:16', '2016-04-18 20:51:16'),
(2, 'About', 'about', '2016-01-01', '2020-01-01', 1, 1, 1, 8, 11, 2, 1, 1, '2016-04-18 21:06:26', '2016-04-18 21:06:26'),
(3, 'Our Season', 'season', '2016-01-01', '2016-12-31', 1, 0, 0, 4, 5, 1, 1, 1, '2016-04-17 20:01:10', '2016-04-17 20:01:10'),
(4, 'Board', 'team', '2016-01-01', '2020-01-01', 1, 1, 1, 9, 10, 0, 2, 2, '2016-04-17 20:01:10', '2016-04-17 20:01:10'),
(5, 'Readings', 'readings', '2016-01-01', '2026-01-01', 0, 0, 0, 6, 7, 0, 1, 1, '2016-04-18 17:38:30', '2016-04-18 17:38:30'),
(17, 'Support', 'support', '2016-04-18', '2016-04-18', 0, 0, 0, 18, 21, 0, 1, 1, '2016-04-18 20:51:16', '2016-04-18 20:51:16'),
(18, 'Donate', 'donate', '2016-04-18', '2016-04-30', 0, 0, 0, 19, 20, 0, 17, 2, '2016-04-18 20:51:17', '2016-04-18 20:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `page_section`
--

CREATE TABLE `page_section` (
  `page_id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page_section`
--

INSERT INTO `page_section` (`page_id`, `section_id`, `text`) VALUES
(2, 2, '<p>In the spring of 2015 a man had a vision. He saw the cast of The Taming of the<img src="/images/gallery/Shrew2015-6-250.gif" width="250" height="375" alt="" align="right"/> Shrew and knew it had to be done. From next to nothing, a Shakespeare company was formed. The dedication and determination of a few theater geeks, and support from a generous community, launched the Petaluma Shakespeare Festival. </p>   		\r\n		  <p>Our first show was a huge success. Despite the ramshackle stage and the rented costumes, the actors doubling as front house managers and marketing gurus, the performance was top notch. A few patrons claimed it was a good as Oregon, or Marin! Armed with the encouragement and inspiration of an appreciative audience, the players have decided to continue the pursuit of a legitimate Festival in Petaluma.  </p>\r\n	  \r\n		  <div class="content_container">\r\n		    <p><strong>Katie Watts Review in the Argus Courier:</strong> </p>\r\n		    <p>Why Petaluma? Why a Shakespeare festival here?\r\n		      \r\n		      To which Producer-Director Jay Cimo replied simply, “Why not?”\r\n		      \r\n		      He’s right...</p>\r\n		  	<div class="button_small">\r\n		      <a href="http://www.petaluma360.com/entertainment/theater/4454749-181/taming-of-the-shrew-delights">Read more</a>\r\n		    </div><!--close button_small-->\r\n		  </div>\r\n					\r\n					\r\n					\r\n					'),
(3, 2, '<p><img src="/images/gallery/foundrywharfsm.jpg" align="right">Borem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius, dui eget tempus porttitor, sapien dui consectetur orci, in placerat leo risus in mauris. Duis vehicula, metus in condimentum porttitor, neque urna hendrerit odio, at varius diam justo in risus. Donec ut gravida ipsum. Cras congue dapibus ultrices. Donec at bibendum urna. Cras quis felis vitae odio ornare sodales eget non risus. Pellentesque euismod neque a est suscipit, a ornare metus luctus. Vestibulum auctor, lectus ac elementum maximus, turpis sapien sollicitudin velit, quis hendrerit lectus lorem sed turpis. Mauris scelerisque lacus sem, ac molestie urna ultricies ac. Praesent ut interdum ex.\r\n</p><p>\r\nDonec nisl enim, fringilla sit amet tristique vulputate, efficitur non felis. Nulla eleifend justo eget sodales sodales. Nunc nec leo ut nibh pharetra facilisis sit amet non justo. Donec ut turpis ut massa posuere posuere. Cras convallis sem turpis. Aenean in placerat velit, sit amet porttitor nibh. Suspendisse sagittis lacus at magna elementum, in porta sem eleifend. Pellentesque pellentesque ipsum eu neque tincidunt, ac imperdiet urna convallis. Donec sed libero arcu. Donec urna tellus, euismod ut pretium vitae, accumsan et ligula. Etiam mi mauris, egestas a molestie eu, molestie eu lorem.\r\n</p><p>\r\n<img src="/images/gallery/actors.jpg" align="left">\r\nNam congue sem et turpis fermentum dignissim. Morbi facilisis, lorem nec malesuada volutpat, tortor dui vehicula eros, nec efficitur nibh libero ut tellus. Aenean maximus dui vitae erat placerat, at tristique nisi varius. Donec blandit lorem nisi, nec suscipit tellus mollis vitae. Maecenas eget leo ullamcorper, viverra felis ut, convallis dui. Donec sollicitudin urna at purus vehicula, sed aliquet tellus maximus. Maecenas blandit enim et mi ullamcorper, quis sodales nisi ultricies. Nunc commodo venenatis lacus at vestibulum. Fusce metus odio, molestie in gravida in, vulputate vel risus. Cras suscipit massa quis condimentum vestibulum. Cras feugiat ante eu vulputate pulvinar. Quisque volutpat accumsan enim eu condimentum. Curabitur enim neque, condimentum venenatis sapien quis, facilisis molestie nisl. Praesent justo magna, tristique in neque ut, rutrum vulputate odio. Cras sed mattis lorem. Vestibulum tempus, justo non pulvinar dictum, sapien mi dictum sem, quis ultrices justo dui ut turpis.\r\n</p><p>\r\nCras eget velit convallis, feugiat risus non, ultricies ante. Pellentesque pharetra sed leo non porttitor. Nunc porttitor quis justo id eleifend. In id felis at sem viverra fringilla a ac felis. Cras accumsan bibendum odio sed molestie. Ut tincidunt sodales justo in consectetur. Proin sed felis massa. Donec tempor faucibus sem quis dapibus. Nam blandit dolor non dolor vehicula consectetur. Praesent vulputate interdum scelerisque.\r\n</p><p>\r\nEtiam tempus nulla dolor. Quisque tincidunt odio a enim pharetra, a volutpat purus scelerisque. Nam ut magna bibendum, placerat nisi in, lacinia velit. Fusce id nisi congue, tempus urna et, facilisis orci. Sed sit amet purus pretium tellus sagittis posuere. In sed odio ante. Sed finibus elementum purus nec blandit. Nullam rutrum posuere efficitur. Aliquam erat volutpat. Nulla nec massa eu ex eleifend consequa.</p>\r\n					'),
(4, 2, '<p>\r\nCras eget velit convallis, feugiat risus non, ultricies ante. Pellentesque pharetra sed leo non porttitor. Nunc porttitor quis justo id eleifend. In id felis at sem viverra fringilla a ac felis. Cras accumsan bibendum odio sed molestie. Ut tincidunt sodales justo in consectetur. Proin sed felis massa. Donec tempor faucibus sem quis dapibus. Nam blandit dolor non dolor vehicula consectetur. Praesent vulputate interdum scelerisque.\r\n</p><p>\r\nEtiam tempus nulla dolor. Quisque tincidunt odio a enim pharetra, a volutpat purus scelerisque. Nam ut magna bibendum, placerat nisi in, lacinia velit. Fusce id nisi congue, tempus urna et, facilisis orci. Sed sit amet purus pretium tellus sagittis posuere. In sed odio ante. Sed finibus elementum purus nec blandit. Nullam rutrum posuere efficitur. Aliquam erat volutpat. Nulla nec massa eu ex eleifend consequa.</p>'),
(5, 2, '<img src="/images/gallery/sebastopol.jpg"><p>Since 2014 the Bay Area Readers Theater aka BARDS (and now known as the Petaluma Shakespeare Festival) has been holding public readings of (slightly abridged versions) Shakespeare''s plays.</p>\r\n<p>We meet each month at Aqus Cafe in Petaluma, and other places including Sepastopol Gallery in Sebastopol.  Our readings are very informal.  The principal roles are typical pre-cast but many roles are cast from the assembled crowd.  There are no small parts, only small actors.</p>\r\n<p>The readings are free and open to all</p>\r\n					\r\n					'),
(17, 2, '<p>Support Our Festival&nbsp;&nbsp;&nbsp;&nbsp;</p>'),
(18, 2, 'Please give lots');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'edit_pages', 'Editor', '2016-04-18 17:08:48', '2016-04-19 00:04:36'),
(2, 'manage_site', 'Site Administrator', '2016-04-18 17:14:26', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `quote` text NOT NULL,
  `attribution` text NOT NULL,
  `source1` text NOT NULL,
  `source2` text NOT NULL,
  `source3` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `quote`, `attribution`, `source1`, `source2`, `source3`, `created_at`, `updated_at`) VALUES
(1, 'A horse! a horse! my kingdom for a horse!', 'Shakespeare', 'Richard III', '5', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'A jest''s prosperity lies in the ear<br />Of him that hears it, never in the tongue<br />Of him that makes it.', 'Shakespeare', 'Love''s Labour''s Lost', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'A little water clears us of this deed.', 'Shakespeare', 'Macbeth', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Affliction is enamour''d of thy parts,<br />And thou art wedded to calamity.', 'Shakespeare', 'Romeo and Juliet', '3', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'All that glisters is not gold.', 'Shakespeare', 'The Merchant of Venice', '2', '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'All the world''s a stage,<br />And all the men and women merely players.', 'Shakespeare', 'As You Like It', '2', '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'And oftentimes excusing of a fault<br />Doth make the fault the worse by the excuse.', 'Shakespeare', 'King John', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'As flies to wanton boys, are we to the gods.<br />They kill us for their sport.', 'Shakespeare', 'King Lear', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Beware the ides of March.', 'Shakespeare', 'Julius Caesar', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Blood will have blood.', 'Shakespeare', 'Macbeth', '3', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Brevity is the soul of wit.', 'Shakespeare', 'Hamlet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'But be not afraid of greatness: some are born great, some achieve greatness, and some have greatness thrust upon them.', 'Shakespeare', 'Twelfth Night', '2', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'But words are words; I never yet did hear<br />That the bruised heart was pierced through the ear.', 'Shakespeare', 'Othello', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'But, soft! what light through yonder window breaks?<br />It is the east, and Juliet is the sun.', 'Shakespeare', 'Romeo and Juliet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'By the pricking of my thumbs,<br />Something wicked this way comes.', 'Shakespeare', 'Macbeth', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Come away, come away, death,<br />And in sad cypress let me be laid;<br />Fly away, fly away breath;<br />I am slain by a fair cruel maid.', 'Shakespeare', 'Twelfth Night', '2', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Come not between the dragon and his wrath.', 'Shakespeare', 'King Lear', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Come not within the measure of my wrath.', 'Shakespeare', 'The Two Gentleman of Verona', '5', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Come what come may,<br />Time and the hour runs through the roughest day.', 'Shakespeare', 'Macbeth', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Come, come, good wine is a good familiar creature, if it be well used; exclaim no more against it.', 'Shakespeare', 'Othello', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Company, villanous company, hath been the spoil of me.', 'Shakespeare', 'Henry IV, Part 1', '3', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Cry "Havoc," and let slip the dogs of war.', 'Shakespeare', 'Julius Caesar', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Cupid is a knavish lad,<br />Thus to make poor females mad.', 'Shakespeare', 'A Midsummer Night''s Dream', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Double, double toil and trouble;<br />Fire burn, and cauldron bubble.', 'Shakespeare', 'Macbeth', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Et tu, Brute! Then fall Caesar!', 'Shakespeare', 'Julius Caesar', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Every subject''s duty is the king''s; but every subject''s soul is his own.', 'Shakespeare', 'Henry V', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Fair is foul, and foul is fair.', 'Shakespeare', 'Macbeth', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Frailty, thy name is woman!', 'Shakespeare', 'Hamlet', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Friends, Romans, countrymen, lend me your ears;<br />I come to bury Caesar, not to praise him.<br />The evil that men do lives after them;<br />The good is oft interred with their bones.', 'Shakespeare', 'Julius Caesar', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'From lowest place when virtuous things proceed,<br />The place is dignified by the doer''s deed.', 'Shakespeare', 'All''s Well that Ends Well', '2', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Glamis hath murdered sleep, and there Cawdor<br />Shall sleep no more, Macbeth shall sleep no more!', 'Shakespeare', 'Macbeth', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Good night, good night! parting is such sweet sorrow,<br />That I shall say good night till it be morrow.', 'Shakespeare', 'Romeo and Juliet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Harp not on that string.', 'Shakespeare', 'Richard III', '4', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'He hath eaten me out of house and home.', 'Shakespeare', 'Henry IV, Part 2', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'He that dies pays all debts.', 'Shakespeare', 'The Tempest', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'He that is strucken blind cannot forget<br />The precious treasure of his eyesight lost.', 'Shakespeare', 'Romeo and Juliet', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Here will be an old abusing of God''s patience and the king''s English.', 'Shakespeare', 'The Merry Wives of Windsor', '1', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'How hard it is to hide the sparks of nature!', 'Shakespeare', 'Cymbeline', '3', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'How many goodly creatures are there here!<br />How beauteous mankind is! O brave new world,<br />That has such people in''t!', 'Shakespeare', 'The Tempest', '5', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'How oft the sight of means to do ill deeds<br />Make deeds ill done!', 'Shakespeare', 'King John', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'How poor are they that have not patience!', 'Shakespeare', 'Othello', '2', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'How sharper than a serpent''s tooth it is<br />To have a thankless child!', 'Shakespeare', 'King Lear', '1', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'I am a man<br />More sinned against than sinning.', 'Shakespeare', 'King Lear', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'I am fire and air; my other elements<br />I give to baser life.', 'Shakespeare', 'Antony and Cleopatra', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'I charge thee, fling away ambition:<br />By that sin fell the angels.', 'Shakespeare', 'Henry VIII', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'I dare do all that may become a man;<br />Who dares do more is none.', 'Shakespeare', 'Macbeth', '1', '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'I understand a fury in your words,<br />But not the words.', 'Shakespeare', 'Othello', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'I wasted time, and now doth time waste me.', 'Shakespeare', 'Richard II', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'If all the year were playing holidays,<br />To sport would be as tedious as to work.', 'Shakespeare', 'Henry IV, Part 1', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'If it were now to die,<br />''Twere now to be most happy.', 'Shakespeare', 'Othello', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'If music be the food of love, play on;<br />Give me excess of it, that, surfeiting,<br />The appetite may sicken, and so die.', 'Shakespeare', 'Twelfth Night', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'If one good deed in all my life I did,<br />I do repent it from my very soul.', 'Shakespeare', 'Titus Andronicus', '5', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'If this were played upon a stage now, I could condemn it as an improbable fiction.', 'Shakespeare', 'Twelfth Night', '3', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'If we shadows have offended,<br />Think but this, and all is mended,<br />That you have but slumbered here<br />While these visions did appear.', 'Shakespeare', 'A Midsummer Night''s Dream', '5', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Is this a dagger which I see before me,<br />The handle toward my hand? Come, let me clutch thee.', 'Shakespeare', 'Macbeth', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'It is a heretic that makes the fire,<br />Not she who burns in''t.', 'Shakespeare', 'The Winter''s Tale', '2', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Kill me to-morrow: let me live to-night!', 'Shakespeare', 'Othello', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Lechery, lechery; still, wars and lechery: nothing else holds fashion.', 'Shakespeare', 'Troilus and Cressida', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Let us be sacrificers, but not butchers.', 'Shakespeare', 'Julius Caesar', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Like madness is the glory of this life.', 'Shakespeare', 'Timon of Athens', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Lord, what fools these mortals be!', 'Shakespeare', 'A Midsummer Night''s Dream', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Love sought is good, but given unsought is better.', 'Shakespeare', 'Twelfth Night', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Macbeth shall never vanquished be until<br />Great Birnam wood to high Dunsinane hill<br />Shall come against him.', 'Shakespeare', 'Macbeth', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Many a good hanging prevents a bad marriage.', 'Shakespeare', 'Twelfth Night', '1', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Men are mad things.', 'Shakespeare', 'The Two Noble Kinsmen', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Men of few words are the best men.', 'Shakespeare', 'Henry V', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Men''s evil manners live in brass; their virtues<br />We write in water.', 'Shakespeare', 'Henry VIII', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Misery acquaints a man with strange bedfellows.', 'Shakespeare', 'The Tempest', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'My conscience hath a thousand several tongues,<br />And every tongue brings in a several tale,<br />And every tale condemns me for a villain.', 'Shakespeare', 'Richard III', '5', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'My library<br />Was dukedom large enough.', 'Shakespeare', 'The Tempest', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'My Oberon! what visions have I seen!<br />Methought I was enamoured of an ass.', 'Shakespeare', 'A Midsummer Night''s Dream', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Nature teaches beasts to know their friends.', 'Shakespeare', 'Coriolanus', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Neither a borrower nor a lender be;<br />For loan oft loses both itself and friend,<br />And borrowing dulls the edge of husbandry.', 'Shakespeare', 'Hamlet', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Never durst poet touch a pen to write<br />Until his ink were tempered with Love''s sighs.', 'Shakespeare', 'Love''s Labour''s Lost', '4', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'No beast so fierce but knows some touch of pity.', 'Shakespeare', 'Richard III', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'No legacy is so rich as honesty.', 'Shakespeare', 'All''s Well that Ends Well', '3', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'Nothing emboldens sin so much as mercy.', 'Shakespeare', 'Timon of Athens', '3', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'Now cracks a noble heart. Good-night, sweet prince,<br />And flights of angels sing thee to thy rest!', 'Shakespeare', 'Hamlet', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'Now is the winter of our discontent<br />Made glorious summer by this sun of York.', 'Shakespeare', 'Richard III', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'O Romeo, Romeo! wherefore art thou Romeo?', 'Shakespeare', 'Romeo and Juliet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'O thou invisible spirit of wine, if thou hast no name to be known by, let us call thee devil!', 'Shakespeare', 'Othello', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'O tiger''s heart wrapped in a woman''s hide!', 'Shakespeare', 'Henry VI, Part 3', '1', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'O world! how apt the poor are to be proud.', 'Shakespeare', 'Twelfth Night', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'O! the fierce wretchedness that glory brings us.', 'Shakespeare', 'Timon of Athens', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'O, how full of briers is this working-day world!', 'Shakespeare', 'As You Like It', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'O, pardon me, thou bleeding piece of earth,<br />That I am meek and gentle with these butchers!', 'Shakespeare', 'Julius Caesar', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'O, what a noble mind is here o''erthrown!', 'Shakespeare', 'Hamlet', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Once more unto the breach, dear friends, once more...!', 'Shakespeare', 'Henry V', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Sigh no more, ladies, sigh no more,<br />Men were deceivers ever,-<br />One foot in sea and one on shore,<br />To one thing constant never.', 'Shakespeare', 'Much Ado About Nothing', '2', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Since every Jack became a gentleman<br />There''s many a gentle person made a Jack.', 'Shakespeare', 'Richard III', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'Smooth runs the water where the brook is deep.', 'Shakespeare', 'Henry VI, Part 2', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'So wise so young, they say, do never live long.', 'Shakespeare', 'Richard III', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'Some griefs are medicinable.', 'Shakespeare', 'Cymbeline', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'Some rise by sin, and some by virtue fall.', 'Shakespeare', 'Measure for Measure', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'Spread thy close curtain, love-performing night.', 'Shakespeare', 'Romeo and Juliet', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'Such duty as the subject owes the prince,<br />Even such a woman oweth to her husband.', 'Shakespeare', 'The Taming of the Shrew', '5', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'Teach thy necessity to reason thus;<br />There is no virtue like necessity.', 'Shakespeare', 'Richard II', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'That man that hath a tongue, I say, is no man,<br />If with his tongue he cannot win a woman.', 'Shakespeare', 'The Two Gentleman of Verona', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'The common curse of mankind, folly and ignorance.', 'Shakespeare', 'Troilus and Cressida', '2', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'The course of true love never did run smooth.', 'Shakespeare', 'A Midsummer Night''s Dream', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'The devil can cite Scripture for his purpose.', 'Shakespeare', 'The Merchant of Venice', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'The first thing we do, let''s kill all the lawyers.', 'Shakespeare', 'Henry VI, Part 2', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'The fool doth think he is wise, but the wise man knows himself to be a fool.', 'Shakespeare', 'As You Like It', '5', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'The gods are just, and of our pleasant vices<br />Make instruments to plague us.', 'Shakespeare', 'King Lear', '5', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'The law hath not been dead, though it hath slept.', 'Shakespeare', 'Measure for Measure', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'The lunatic, the lover, and the poet<br />Are of imagination all compact.', 'Shakespeare', 'A Midsummer Night''s Dream', '5', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'The miserable have no other medicine,<br />But only hope.', 'Shakespeare', 'Measure for Measure', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'The play''s the thing<br />Wherein I''ll catch the conscience of the king.', 'Shakespeare', 'Hamlet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'The ripest fruit first falls.', 'Shakespeare', 'Richard II', '2', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'The strain of man''s bred out<br />Into baboon and monkey.', 'Shakespeare', 'Timon of Athens', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'The worst is not<br />So long as we can say, "This is the worst."', 'Shakespeare', 'King Lear', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'There''s daggers in men''s smiles.', 'Shakespeare', 'Macbeth', '2', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'There''s no trust,<br />No faith, no honesty in men.', 'Shakespeare', 'Romeo and Juliet', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'There''s small choice in rotten apples.', 'Shakespeare', 'The Taming of the Shrew', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'Things sweet to taste prove in digestion sour.', 'Shakespeare', 'Richard II', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'Things won are done; joy''s soul lies in the doing.', 'Shakespeare', 'Troilus and Cressida', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'This above all: to thine own self be true,<br />And it must follow, as the night the day,<br />Thou canst not then be false to any man.', 'Shakespeare', 'Hamlet', '1', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'This fellow''s wise enough to play the fool,<br />And to do that well craves a kind of wit.', 'Shakespeare', 'Twelfth Night', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'This is the way to kill a wife with kindness.', 'Shakespeare', 'The Taming of the Shrew', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'Though I am not naturally honest, I am so sometimes by chance.', 'Shakespeare', 'The Winter''s Tale', '4', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'Though this be madness, yet there is method in''t.', 'Shakespeare', 'Hamlet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'To be direct and honest is not safe.', 'Shakespeare', 'Othello', '3', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'To be, or not to be: that is the question.', 'Shakespeare', 'Hamlet', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'To-morrow, and to-morrow, and to-morrow,<br />Creeps in this petty pace from day to day....', 'Shakespeare', 'Macbeth', '5', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'Virtue is bold, and goodness never fearful.', 'Shakespeare', 'Measure for Measure', '3', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'Was ever woman in this humour wooed?<br />Was ever woman in this humour won?', 'Shakespeare', 'Richard III', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'We are such stuff<br />As dreams are made on; and our little life<br />Is rounded with a sleep.', 'Shakespeare', 'The Tempest', '4', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'We have seen better days.', 'Shakespeare', 'Timon of Athens', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'What ''s in a name? That which we call a rose<br />By any other name would smell as sweet.', 'Shakespeare', 'Romeo and Juliet', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'What''s done cannot be undone.', 'Shakespeare', 'Macbeth', '5', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'What''s gone and what''s past help<br />Should be past grief.', 'Shakespeare', 'The Winter''s Tale', '3', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'When beggars die, there are no comets seen;<br />The heavens themselves blaze forth the death of princes.', 'Shakespeare', 'Julius Caesar', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'When our actions do not,<br />Our fears do make us traitors.', 'Shakespeare', 'Macbeth', '4', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'When we are born, we cry that we are come<br />To this great stage of fools.', 'Shakespeare', 'King Lear', '4', '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'Who steals my purse steals trash; ''tis something, nothing;<br />''Twas mine, ''tis his, and has been slave to thousands;<br />But he that filches from me my good name<br />Robs me of that which not enriches him<br />And makes me poor indeed.', 'Shakespeare', 'Othello', '3', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'Why, then the world ''s mine oyster,<br />Which I with sword will open.', 'Shakespeare', 'The Merry Wives of Windsor', '2', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'Love is a smoke raised with the fume of sighs; <br />', 'Shakespeare', 'Romeo and Juliet', '1', '1', '2016-02-23 23:26:31', '2016-02-24 07:26:31'),
(138, 'I am the cygnet to this pale faint swan,<br />\r\nWho chants a doleful hymn to his own death,<br />\r\nAnd from the organ-pipe of frailty sings<br />\r\nHis soul and body to their lasting rest.', 'Shakespeare', 'King John', 'V', 'III', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'Be not afeard. The isle is full of noises,<br />Sounds and sweet airs that give delight and hurt not.<br />', 'Shakespeare', 'The Tempest', 'III', 'II', '2016-02-23 23:25:25', '2016-02-24 07:25:25'),
(140, 'We know what we are, but know not what we may be', 'Shakespeare', 'Hamlet', '4', '5', '2016-02-22 02:17:22', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Site Administrator', '2016-04-18 23:56:53', '2016-04-18 23:56:53');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `created_at`) VALUES
(1, 'front_page', '2016-01-30 18:20:42'),
(2, 'main_body', '2016-01-30 18:20:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Stephen Hamilton', 'stephen@crescentcreative.com', '$2y$10$jsyZSHcxyc8IGnuy.3WeTuToOlTkEP0oFFmjvwjgrSPc9MH916.7O', 'mn99qRTJPGpbQzGJJLmXxsqupCM6iuSxDOxfgsfjVpmmV0U0r1RlB3kRJqbF', '2016-04-18 17:16:40', '2016-04-19 00:16:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contents_section_id_foreign` (`section_id`),
  ADD KEY `contents_page_id_foreign` (`page_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_page`
--
ALTER TABLE `menu_page`
  ADD PRIMARY KEY (`menu_id`,`page_id`),
  ADD KEY `menu_page_menu_id_index` (`menu_id`),
  ADD KEY `menu_page_page_id_index` (`page_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `page_section`
--
ALTER TABLE `page_section`
  ADD PRIMARY KEY (`page_id`,`section_id`),
  ADD KEY `page_section_page_id_index` (`page_id`),
  ADD KEY `page_section_section_id_index` (`section_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `quotes` ADD FULLTEXT KEY `quote` (`quote`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contents_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_page`
--
ALTER TABLE `menu_page`
  ADD CONSTRAINT `menu_page_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_page_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_section`
--
ALTER TABLE `page_section`
  ADD CONSTRAINT `page_section_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `page_section_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

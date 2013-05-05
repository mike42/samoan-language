-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 05, 2013 at 10:50 PM
-- Server version: 5.5.28
-- PHP Version: 5.4.4-14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `samoan`
--

-- --------------------------------------------------------

--
-- Table structure for table `sm_def`
--

CREATE TABLE IF NOT EXISTS `sm_def` (
  `def_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Definition ID',
  `def_word_id` int(11) NOT NULL COMMENT 'The entry that this definition goes with',
  `def_type` int(11) NOT NULL,
  `def_en` varchar(256) NOT NULL COMMENT 'English definition',
  PRIMARY KEY (`def_id`),
  KEY `def_word_id` (`def_word_id`),
  KEY `def_type` (`def_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Each definition of a word goes here. Eg. moe noun, moe verb.' AUTO_INCREMENT=1422 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_def_tag`
--

CREATE TABLE IF NOT EXISTS `sm_def_tag` (
  `def_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`def_id`,`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tags definitions (for ''polite'', ''vulgar'', etc)';

-- --------------------------------------------------------

--
-- Table structure for table `sm_example`
--

CREATE TABLE IF NOT EXISTS `sm_example` (
  `example_id` int(11) NOT NULL AUTO_INCREMENT,
  `example_str` text NOT NULL COMMENT 'Includes characters for linking words',
  `example_t_style` text NOT NULL COMMENT 'T-style string of example',
  `example_k_style` text NOT NULL COMMENT 'K-style string of example',
  `example_t_style_recorded` int(1) NOT NULL DEFAULT '0',
  `example_k_style_recorded` int(1) NOT NULL DEFAULT '0',
  `example_en` text NOT NULL,
  `example_en_lit` text NOT NULL,
  `example_uploaded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `example_audio_tag` text NOT NULL,
  PRIMARY KEY (`example_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=346 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_exampleaudio`
--

CREATE TABLE IF NOT EXISTS `sm_exampleaudio` (
  `example_id` int(11) NOT NULL,
  `audio_k_stye` int(1) NOT NULL,
  `audio_upload_date` datetime NOT NULL,
  `audio_speaker` int(11) NOT NULL,
  PRIMARY KEY (`example_id`,`audio_k_stye`),
  KEY `example_id` (`example_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sm_examplerel`
--

CREATE TABLE IF NOT EXISTS `sm_examplerel` (
  `example_rel_example_id` int(11) NOT NULL,
  `example_rel_def_id` int(11) NOT NULL,
  PRIMARY KEY (`example_rel_example_id`,`example_rel_def_id`),
  KEY `example_rel_example_id` (`example_rel_example_id`),
  KEY `example_rel_def_id` (`example_rel_def_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Links examples with definitions';

-- --------------------------------------------------------

--
-- Table structure for table `sm_letter`
--

CREATE TABLE IF NOT EXISTS `sm_letter` (
  `letter_html` mediumtext NOT NULL,
  `letter_html_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `letter_html_valid` int(1) NOT NULL,
  `letter_id` varchar(1) NOT NULL,
  PRIMARY KEY (`letter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cached data for per-letter vocab';

-- --------------------------------------------------------

--
-- Table structure for table `sm_listlang`
--

CREATE TABLE IF NOT EXISTS `sm_listlang` (
  `lang_id` varchar(2) NOT NULL COMMENT 'ISO language code',
  `lang_name` varchar(32) NOT NULL COMMENT 'Expand to..',
  KEY `id` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='list of ISO language codes for borrowed words';

-- --------------------------------------------------------

--
-- Table structure for table `sm_listreltype`
--

CREATE TABLE IF NOT EXISTS `sm_listreltype` (
  `rel_type_id` varchar(4) NOT NULL DEFAULT '',
  `rel_type_short` varchar(256) NOT NULL,
  `rel_type_long` varchar(256) NOT NULL,
  `rel_type_long_label` varchar(256) NOT NULL,
  PRIMARY KEY (`rel_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sm_listtype`
--

CREATE TABLE IF NOT EXISTS `sm_listtype` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of word type',
  `type_abbr` varchar(256) NOT NULL COMMENT 'Abbreviation for displaying word type',
  `type_name` varchar(256) NOT NULL COMMENT 'Full name of word type',
  `type_title` varchar(255) NOT NULL,
  `type_short` varchar(256) NOT NULL,
  `type_istag` int(1) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_page`
--

CREATE TABLE IF NOT EXISTS `sm_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_short` varchar(256) NOT NULL,
  `page_revision` int(11) DEFAULT NULL,
  `page_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_short` (`page_short`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_revision`
--

CREATE TABLE IF NOT EXISTS `sm_revision` (
  `revision_id` int(11) NOT NULL AUTO_INCREMENT,
  `revision_page_id` int(11) NOT NULL,
  `revision_title` varchar(256) NOT NULL,
  `revision_author` int(11) NOT NULL,
  `revision_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revision_text` mediumtext NOT NULL,
  `revision_text_parsed` mediumtext NOT NULL,
  `revision_parse_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `revision_parse_valid` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`revision_id`),
  KEY `revision_page_id` (`revision_page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=587 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_spelling`
--

CREATE TABLE IF NOT EXISTS `sm_spelling` (
  `spelling_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Word ID',
  `spelling_t_style` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Standard way of writing the word',
  `spelling_t_style_recorded` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True if a recording exists',
  `spelling_k_style` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Word as it is spoken in k-style',
  `spelling_k_style_recorded` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True if recording exists for k-style',
  `spelling_simple` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Non-unique version of the word without macrons or glottal stops, for searches',
  `spelling_sortkey` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Sortkey for the word',
  `spelling_searchkey` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `spelling_sortkey_sm` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`spelling_id`),
  UNIQUE KEY `spelling_t_style` (`spelling_t_style`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Each word with unique pronunciation' AUTO_INCREMENT=1392 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_spellingaudio`
--

CREATE TABLE IF NOT EXISTS `sm_spellingaudio` (
  `spelling_id` int(11) NOT NULL,
  `audio_k_style` int(1) NOT NULL,
  `audio_upload_date` datetime NOT NULL,
  `audio_speaker` int(11) NOT NULL,
  PRIMARY KEY (`spelling_id`,`audio_k_style`),
  KEY `spelling_id` (`spelling_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sm_user`
--

CREATE TABLE IF NOT EXISTS `sm_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(127) NOT NULL,
  `user_pass` varchar(127) NOT NULL,
  `user_salt` varchar(127) NOT NULL,
  `user_token` varchar(127) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_email_confirmed` int(1) NOT NULL DEFAULT '0',
  `user_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_role` varchar(256) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='User accounts' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_word`
--

CREATE TABLE IF NOT EXISTS `sm_word` (
  `word_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Word ID',
  `word_spelling` int(11) NOT NULL COMMENT 'Refers to word_simple ID',
  `word_num` int(1) NOT NULL COMMENT 'Number, used if the word has several very distinct meanings',
  `word_origin_lang` varchar(2) DEFAULT NULL COMMENT 'ISO language code of origin word',
  `word_origin_word` varchar(127) NOT NULL COMMENT 'Word as it is in original language',
  `word_auto` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True if word was added automatically',
  `word_redirect_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`word_id`),
  UNIQUE KEY `word_idstr_unique` (`word_spelling`,`word_num`),
  KEY `word_spelling` (`word_spelling`),
  KEY `word_origin_lang` (`word_origin_lang`),
  KEY `word_redirect_to` (`word_redirect_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1391 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_wordrel`
--

CREATE TABLE IF NOT EXISTS `sm_wordrel` (
  `wordrel_id` int(11) NOT NULL AUTO_INCREMENT,
  `wordrel_word_id` int(11) NOT NULL,
  `wordrel_type` varchar(256) NOT NULL,
  `wordrel_target` int(11) NOT NULL,
  PRIMARY KEY (`wordrel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1082 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sm_def`
--
ALTER TABLE `sm_def`
  ADD CONSTRAINT `sm_def_ibfk_1` FOREIGN KEY (`def_word_id`) REFERENCES `sm_word` (`word_id`),
  ADD CONSTRAINT `sm_def_ibfk_2` FOREIGN KEY (`def_type`) REFERENCES `sm_listtype` (`type_id`);

--
-- Constraints for table `sm_exampleaudio`
--
ALTER TABLE `sm_exampleaudio`
  ADD CONSTRAINT `sm_exampleaudio_ibfk_1` FOREIGN KEY (`example_id`) REFERENCES `sm_example` (`example_id`);

--
-- Constraints for table `sm_examplerel`
--
ALTER TABLE `sm_examplerel`
  ADD CONSTRAINT `sm_examplerel_ibfk_1` FOREIGN KEY (`example_rel_example_id`) REFERENCES `sm_example` (`example_id`),
  ADD CONSTRAINT `sm_examplerel_ibfk_2` FOREIGN KEY (`example_rel_def_id`) REFERENCES `sm_def` (`def_id`);

--
-- Constraints for table `sm_revision`
--
ALTER TABLE `sm_revision`
  ADD CONSTRAINT `sm_revision_ibfk_1` FOREIGN KEY (`revision_page_id`) REFERENCES `sm_page` (`page_id`);

--
-- Constraints for table `sm_spellingaudio`
--
ALTER TABLE `sm_spellingaudio`
  ADD CONSTRAINT `sm_spellingaudio_ibfk_1` FOREIGN KEY (`spelling_id`) REFERENCES `sm_spelling` (`spelling_id`);

--
-- Constraints for table `sm_word`
--
ALTER TABLE `sm_word`
  ADD CONSTRAINT `sm_word_ibfk_1` FOREIGN KEY (`word_spelling`) REFERENCES `sm_spelling` (`spelling_id`),
  ADD CONSTRAINT `sm_word_ibfk_2` FOREIGN KEY (`word_redirect_to`) REFERENCES `sm_word` (`word_id`),
  ADD CONSTRAINT `sm_word_ibfk_3` FOREIGN KEY (`word_origin_lang`) REFERENCES `sm_listlang` (`lang_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

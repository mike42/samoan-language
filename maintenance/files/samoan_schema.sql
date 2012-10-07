-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 07, 2012 at 02:40 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bitrevis_sm`
--

-- --------------------------------------------------------

--
-- Table structure for table `sm_audio`
--

CREATE TABLE IF NOT EXISTS `sm_audio` (
  `audio_id` int(11) NOT NULL AUTO_INCREMENT,
  `audio_spelling_id` int(11) NOT NULL DEFAULT '0',
  `audio_example` int(11) NOT NULL DEFAULT '0',
  `audio_uploaded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audio_k_style` int(11) NOT NULL DEFAULT '0',
  `audio_speaker` varchar(256) NOT NULL,
  PRIMARY KEY (`audio_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_def`
--

CREATE TABLE IF NOT EXISTS `sm_def` (
  `def_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Definition ID',
  `def_word_id` int(11) NOT NULL COMMENT 'The entry that this definition goes with',
  `def_type` int(11) NOT NULL,
  `def_en` varchar(256) NOT NULL COMMENT 'English definition',
  PRIMARY KEY (`def_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Each definition of a word goes here. Eg. moe noun, moe verb.' AUTO_INCREMENT=1387 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=316 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Links examples with definitions';

-- --------------------------------------------------------

--
-- Table structure for table `sm_listlang`
--

CREATE TABLE IF NOT EXISTS `sm_listlang` (
  `lang_id` varchar(256) NOT NULL COMMENT 'ISO language code',
  `lang_name` varchar(256) NOT NULL COMMENT 'Expand to..',
  KEY `id` (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='list of ISO language codes for borrowed words';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_page`
--

CREATE TABLE IF NOT EXISTS `sm_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_short` varchar(256) NOT NULL,
  `page_revision` int(11) DEFAULT NULL,
  `page_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
  `revision_text` text NOT NULL,
  `revision_text_parsed` text NOT NULL,
  `revision_parse_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `revision_parse_valid` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`revision_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=194 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_spelling`
--

CREATE TABLE IF NOT EXISTS `sm_spelling` (
  `spelling_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Word ID',
  `spelling_t_style` text NOT NULL COMMENT 'Standard way of writing the word',
  `spelling_t_style_recorded` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True if a recording exists',
  `spelling_k_style` text NOT NULL COMMENT 'Word as it is spoken in k-style',
  `spelling_k_style_recorded` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True if recording exists for k-style',
  `spelling_simple` text NOT NULL COMMENT 'Non-unique version of the word without macrons or glottal stops, for searches',
  `spelling_sortkey` text NOT NULL COMMENT 'Sortkey for the word',
  `spelling_searchkey` text NOT NULL,
  `spelling_sortkey_sm` text NOT NULL,
  PRIMARY KEY (`spelling_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Each word with unique pronunciation' AUTO_INCREMENT=1354 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_user`
--

CREATE TABLE IF NOT EXISTS `sm_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` text NOT NULL,
  `user_pass` varchar(256) NOT NULL,
  `user_salt` varchar(256) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_email_confirmed` int(1) NOT NULL DEFAULT '0',
  `user_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_role` varchar(256) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User accounts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sm_word`
--

CREATE TABLE IF NOT EXISTS `sm_word` (
  `word_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Word ID',
  `word_spelling` int(11) NOT NULL COMMENT 'Refers to word_simple ID',
  `word_num` int(1) NOT NULL COMMENT 'Number, used if the word has several very distinct meanings',
  `word_origin_lang` varchar(256) NOT NULL COMMENT 'ISO language code of origin word',
  `word_origin_word` varchar(256) NOT NULL COMMENT 'Word as it is in original language',
  `word_auto` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True if word was added automatically',
  `word_redirect` varchar(256) NOT NULL COMMENT 'Not-empty for a "see ..." word',
  `word_redirect_to` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`word_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1358 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1054 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

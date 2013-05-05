CREATE TABLE IF NOT EXISTS `sm_letter` (
  `letter_html` mediumtext NOT NULL,
  `letter_html_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `letter_html_valid` int(1) NOT NULL,
  `letter_id` varchar(1) NOT NULL,
  PRIMARY KEY (`letter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cached data for per-letter vocab';

ALTER TABLE  `sm_revision` CHANGE  `revision_text`  `revision_text` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE  `revision_text_parsed`  `revision_text_parsed` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

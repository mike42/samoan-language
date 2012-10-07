<?php
class revision_model {
	public static $template;

	public static function init() {
		core::loadClass('database');
		self::$template = array(
				'revision_id'			=> '0',
				'revision_page_id'		=> '0',
				'revision_title'		=> '',
				'revision_author'		=> '',
				'revision_ts'			=> '',
				'revision_text'			=> '',
				'revision_text_parsed'	=> '',
				'revision_parse_ts'		=> '0000-00-00 00:00:00',
				'revision_parse_valid'	=> '0');
	}

	public static function insert($page, $revision) {
		/* Be sure the revision goes to the right place */
		$revision['revision_page_id'] = $page['page_id'];
		
		/* Add the revision */
		$query = "INSERT INTO {TABLE}revision (revision_id, revision_page_id, revision_title, revision_author, revision_ts, revision_text, revision_text_parsed, revision_parse_ts, revision_parse_valid) VALUES (NULL , '%d', '%s', '%d', CURRENT_TIMESTAMP , '%s', '', '0000-00-00 00:00:00', '0')";		
		if(!$revision_id = database::retrieve($query, 2, (int)$page['page_id'], $revision['revision_title'], $revision['revision_author'], $revision['revision_text'])) {
			return false;
		}
		
		/* Update revision id */
		return page_model::setCurrentRevisionID($page['page_id'], $revision_id);
	}

} ?>



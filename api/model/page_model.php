<?php 
class page_model {
	private static $template;

	public static function init() {
		core::loadClass('database');
		core::loadClass('revision_model');

		self::$template = array(
				'page_id'				=> '0',
				'page_short'			=> '',
				'page_revision'			=> '0',
				'page_created'			=> '',
				'page_rel_revision'		=> array());
	}

	public static function getByShort($id) {
		$query = "SELECT * FROM sm_page left join sm_revision on page_revision = revision_id and page_id = revision_page_id WHERE page_short = ?";
		if($row = database::get_row(database::retrieve($query, [$id]))) {
			$page = database::row_from_template($row, page_model::$template);
			$page['page_rel_revision'] = database::row_from_template($row, revision_model::$template);
			return $page;
		} else {
			return false;
		}
	}

	public static function insert($short) {
		$query = "INSERT INTO sm_page (page_id, page_short, page_revision, page_created) VALUES (NULL , ?, NULL, CURRENT_TIMESTAMP)";
		database::retrieve($query, [$short]);

		if(!$page = self::getByShort($short)) {
			/* Something has gone wrong */
			return false;
		}

		/* Start page with blank template */
		$revision = revision_model::$template;
		$revision['revision_title'] = "New Page";
		$revision['revision_text'] = "";
		revision_model::insert($page, $revision);

		return true;
	}

	public static function setCurrentRevisionID($page_id, $revision_id) {
		$query = "UPDATE sm_page SET page_revision =? WHERE page_id =?;";
		database::retrieve($query, [$revision_id, $page_id]);
	}

	public static function render($page) {
		core::loadClass('parser');
		return wikitextParser::parse($page['page_rel_revision']['revision_text']);
	}

	public static function delete($id) {
		/* Delete a page by ID (you should of course check that it exists first, or this will do nothing) */
		$query = "DELETE FROM sm_revision WHERE revision_page_id =?;";
		database::retrieve($query, [(int)$id]);
		$query = "DELETE FROM sm_page WHERE page_id =?;";
		database::retrieve($query, [(int)$id]);
		return true;
	}
} ?>

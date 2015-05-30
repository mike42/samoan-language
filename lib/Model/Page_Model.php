<?php

namespace SmWeb;

class Page_Model implements Model {
	private static $template;
	public static function init() {
		Core::loadClass ( 'Database' );
		Core::loadClass ( 'Revision_Model' );
		
		self::$template = array (
				'page_id' => '0',
				'page_short' => '',
				'page_revision' => '0',
				'page_created' => '',
				'page_rel_revision' => array () 
		);
	}
	public static function getByShort($id) {
		$query = "SELECT * FROM {TABLE}page left join {TABLE}revision on page_revision = revision_id and page_id = revision_page_id WHERE page_short = '%s'";
		if ($row = Database::retrieve ( $query, 1, $id )) {
			$page = Database::row_from_template ( $row, Page_Model::$template );
			$page ['page_rel_revision'] = Database::row_from_template ( $row, Revision_Model::$template );
			return $page;
		} else {
			return false;
		}
	}
	public static function insert($short) {
		$query = "INSERT INTO {TABLE}page (page_id, page_short, page_revision, page_created) VALUES (NULL , '%s', NULL, CURRENT_TIMESTAMP)";
		Database::retrieve ( $query, 0, $short );
		
		if (! $page = self::getByShort ( $short )) {
			/* Something has gone wrong */
			return false;
		}
		
		/* Start page with blank template */
		$revision = Revision_Model::$template;
		$revision ['revision_title'] = "New Page";
		$revision ['revision_text'] = "";
		Revision_Model::insert ( $page, $revision );
		
		return true;
	}
	public static function setCurrentRevisionID($page_id, $revision_id) {
		$query = "UPDATE {TABLE}page SET page_revision ='%d' WHERE page_id =%d;";
		Database::retrieve ( $query, 0, $revision_id, $page_id );
	}
	public static function render($page) {
		Core::loadClass ( 'Parser' );
		return \WikitextParser::parse ( $page ['page_rel_revision'] ['revision_text'] );
	}
	public static function delete($id) {
		/* Delete a page by ID (you should of course check that it exists first, or this will do nothing) */
		$query = "DELETE FROM {TABLE}revision WHERE revision_page_id =%d;";
		Database::retrieve ( $query, 0, ( int ) $id );
		$query = "DELETE FROM {TABLE}page WHERE page_id =%d;";
		Database::retrieve ( $query, 0, ( int ) $id );
		return true;
	}
}

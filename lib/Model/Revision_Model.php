<?php

namespace SmWeb;

class Revision_Model implements Model {
	private static $instance;
	public static $template;
	public $database;
	public function __construct(database $database) {
		$this->database = $database;
	}
	public static function getInstance(database $database) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
		}
		return self::$instance;
	}
	public static function init() {
		Core::loadClass ( 'Database' );
		self::$template = array (
				'revision_id' => '0',
				'revision_page_id' => '0',
				'revision_title' => '',
				'revision_author' => '',
				'revision_ts' => '',
				'revision_text' => '',
				'revision_text_parsed' => '',
				'revision_parse_ts' => '0000-00-00 00:00:00',
				'revision_parse_valid' => '0' 
		);
	}
	public function insert($page, $revision) {
		/* Be sure the revision goes to the right place */
		$revision ['revision_page_id'] = $page ['page_id'];
		
		/* Add the revision */
		$query = "INSERT INTO {TABLE}revision (revision_id, revision_page_id, revision_title, revision_author, revision_ts, revision_text, revision_text_parsed, revision_parse_ts, revision_parse_valid) VALUES (NULL , '%d', '%s', '%d', CURRENT_TIMESTAMP , '%s', '', '0000-00-00 00:00:00', '0')";
		if (! $revision_id = $this->database->retrieve ( $query, 2, ( int ) $page ['page_id'], $revision ['revision_title'], $revision ['revision_author'], $revision ['revision_text'] )) {
			return false;
		}
		
		/* Update revision id */
		return Page_Model::setCurrentRevisionID ( $page ['page_id'], $revision_id );
	}
	public function cache_purge($revision_id) {
		/* Purge a single revision */
		$query = "UPDATE {TABLE}revision SET revision_text_parsed ='', revision_parse_valid =0 WHERE revision_id =%d";
		$this->database->retrieve ( $query, 0, ( int ) $revision_id );
	}
	public function cache_purge_page($revision_page_id) {
		/* Purge all revisions for a page */
		$query = "UPDATE {TABLE}revision SET revision_text_parsed ='', revision_parse_valid =0 WHERE revision_page_id =%d";
		$this->database->retrieve ( $query, 0, ( int ) $revision_page_id );
	}
	public function cache_purge_all() {
		/* Purge every cached revision (potentially slow) */
		$query = "UPDATE {TABLE}revision SET revision_text_parsed ='', revision_parse_valid =0 WHERE 1";
		$this->database->retrieve ( $query, 0 );
	}
	public function cache_save($revision) {
		$query = "UPDATE {TABLE}revision SET revision_text_parsed ='%s', revision_parse_ts = CURRENT_TIMESTAMP, revision_parse_valid =1 WHERE revision_id =%d";
		$this->database->retrieve ( $query, 0, $revision ['revision_text_parsed'], ( int ) $revision ['revision_id'] );
	}
}
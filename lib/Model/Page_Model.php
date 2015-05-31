<?php

namespace SmWeb;

class Page_Model implements Model {
	private static $instance;
	private static $template;
	private $database;
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
		Core::loadClass ( 'Revision_Model' );
		
		self::$template = array (
				'page_id' => '0',
				'page_short' => '',
				'page_revision' => '0',
				'page_created' => '',
				'page_rel_revision' => array () 
		);
	}
	public function getByShort($id) {
		$query = "SELECT * FROM {TABLE}page left join {TABLE}revision on page_revision = revision_id and page_id = revision_page_id WHERE page_short = '%s'";
		if ($row = $this->database->retrieve ( $query, 1, $id )) {
			$page = $this->database->row_from_template ( $row, Page_Model::$template );
			$page ['page_rel_revision'] = $this->database->row_from_template ( $row, Revision_Model::$template );
			return $page;
		} else {
			return false;
		}
	}
	public function insert($short) {
		$query = "INSERT INTO {TABLE}page (page_id, page_short, page_revision, page_created) VALUES (NULL , '%s', NULL, CURRENT_TIMESTAMP)";
		$this->database->retrieve ( $query, 0, $short );
		
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
	public function setCurrentRevisionID($page_id, $revision_id) {
		$query = "UPDATE {TABLE}page SET page_revision ='%d' WHERE page_id =%d;";
		$this->database->retrieve ( $query, 0, $revision_id, $page_id );
	}
	public function render($page) {
		Core::loadClass ( 'Parser' );
		$parser = Parser::getInstance($this -> database);
		return $parser -> parse($page ['page_rel_revision'] ['revision_text']);
	}
	public function delete($id) {
		/* Delete a page by ID (you should of course check that it exists first, or this will do nothing) */
		$query = "DELETE FROM {TABLE}revision WHERE revision_page_id =%d;";
		$this->database->retrieve ( $query, 0, ( int ) $id );
		$query = "DELETE FROM {TABLE}page WHERE page_id =%d;";
		$this->database->retrieve ( $query, 0, ( int ) $id );
		return true;
	}
}

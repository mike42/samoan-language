<?php

namespace SmWeb;

class ExampleRel_Model implements Model {
	private static $instance;
	private static $template;
	public $database;
	private $example;
	public function __construct(database $database) {
		$this->database = $database;
		$this->example = Example_Model::getInstance ( $database );
	}
	public static function getInstance(database $database) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
		}
		return self::$instance;
	}
	public static function init() {
		Core::loadClass ( 'Database' );
		Core::loadClass ( 'Example_Model' );
		Core::loadClass ( 'Def_Model' );
		
		self::$template = array (
				'example_rel_example_id' => '0',
				'example_rel_def_id' => '0' 
		);
	}
	public function add($example_id, $word_id, $def_id) {
		if (! $example = $this->example->getById ( $example_id )) {
			/* No such example */
			return false;
		}
		
		if (! $def = Def_Model::get ( $word_id, $def_id )) {
			/* No such def or def/word don't match */
			return false;
		}
		
		if ($examplerel = self::get ( $def_id, $example_id )) {
			/* Already associated */
			return false;
		}
		
		$query = "INSERT INTO {TABLE}examplerel (example_rel_example_id, example_rel_def_id) VALUES (%d, %d);";
		$this->database->retrieve ( $query, 0, ( int ) $example_id, ( int ) $def_id );
		return true;
	}
	public function delete($example_id, $word_id, $def_id) {
		if (! $example = $this->example->getById ( $example_id )) {
			/* No such example */
			return false;
		}
		
		if (! $def = Def_Model::get ( $word_id, $def_id )) {
			/* No such def or def/word don't match */
			return false;
		}
		
		if (! $examplerel = self::get ( $def_id, $example_id )) {
			/* Alren't associated */
			return false;
		}
		
		$query = "DELETE FROM {TABLE}examplerel WHERE example_rel_example_id =%d AND example_rel_def_id =%d;";
		$this->database->retrieve ( $query, 0, ( int ) $example_id, ( int ) $def_id );
		return true;
	}
	public function get($def_id, $example_id) {
		$query = "SELECT * FROM {TABLE}examplerel WHERE example_rel_example_id =%d AND example_rel_def_id =%d;";
		$res = $this->database->retrieve ( $query, 0, ( int ) $example_id, ( int ) $def_id );
		
		if ($row = $this->database->get_row ( $res )) {
			$examplerel = $this->database->row_from_template ( $row, self::$template );
			return $examplerel;
		}
		return false;
	}
}

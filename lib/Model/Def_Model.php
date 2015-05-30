<?php

namespace SmWeb;

class Def_Model implements Model {
	private static $template;
	public static $database;
	public static function init() {
		Core::loadClass ( 'Database' );
		Core::loadClass ( 'ListType_Model' );
		Core::loadClass ( 'Example_Model' );
		Core::loadClass ( 'ExampleRel_Model' );
		
		self::$template = array (
				'def_id' => '0',
				'def_entry_id' => '0',
				'def_type' => '0',
				'def_en' => '',
				'rel_type' => ListType_Model::$template,
				'rel_example' => array () 
		);
		self::$database = Database::getInstance();
	}
	public static function get($word_id, $def_id) {
		$query = "SELECT * FROM sm_def " . "LEFT JOIN {TABLE}listtype ON def_type = type_id " . "WHERE def_word_id =%d AND def_id =%d;";
		if (! $res = self::$database -> retrieve ( $query, 0, ( int ) $word_id, ( int ) $def_id )) {
			return false;
		}
		
		if ($row = self::$database -> get_row ( $res )) {
			$def = self::$database -> row_from_template ( $row, self::$template );
			$def ['rel_type'] = self::$database -> row_from_template ( $row, ListType_Model::$template );
			$def ['rel_example'] = Example_Model::listByDef ( $def ['def_id'] );
			return $def;
		}
		
		return false;
	}
	public static function listByWord($word_id) {
		$query = "SELECT * FROM sm_def " . "LEFT JOIN {TABLE}listtype ON def_type = type_id " . "WHERE def_word_id =%d;";
		
		$ret = array ();
		if ($res = self::$database -> retrieve ( $query, 0, ( int ) $word_id )) {
			while ( $row = self::$database -> get_row ( $res ) ) {
				/* Load def and associated type */
				$def = self::$database -> row_from_template ( $row, self::$template );
				$def ['rel_type'] = self::$database -> row_from_template ( $row, ListType_Model::$template );
				$def ['rel_example'] = Example_Model::listByDef ( $def ['def_id'] );
				$ret [] = $def;
			}
		}
		return $ret;
	}
	
	/**
	 * Return ID of a new (blank) definition for a given word
	 * 
	 * @param int $word_id        	
	 */
	public static function add($word_id) {
		$query = "INSERT INTO  {TABLE}def (def_id, def_word_id, def_type, def_en) VALUES (NULL, %d,  '0',  '');";
		return self::$database -> retrieve ( $query, 2, ( int ) $word_id );
	}
	public static function update($def) {
		$query = "UPDATE {TABLE}def SET def_type =%d, def_en ='%s' WHERE def_id =%d;";
		return self::$database -> retrieve ( $query, 0, ( int ) $def ['def_type'], $def ['def_en'], ( int ) $def ['def_id'] );
	}
	
	/**
	 * Delete the specified definition
	 *
	 * @param int $def_id        	
	 */
	public static function delete($def_id) {
		/* Delete linked examples */
		$query = "DELETE FROM {TABLE}examplerel WHERE example_rel_def_id =%d;";
		self::$database -> retrieve ( $query, 0, ( int ) $def_id );
		
		/* Delete definition itself */
		$query = "DELETE FROM {TABLE}def WHERE def_id =%d;";
		return self::$database -> retrieve ( $query, 0, ( int ) $def_id );
	}
}

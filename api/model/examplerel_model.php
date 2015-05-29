<?php

namespace SmWeb;

class examplerel_model implements model {
	private static $template;
	public static function init() {
		core::loadClass ( 'database' );
		core::loadClass ( 'example_model' );
		core::loadClass ( 'def_model' );
		
		self::$template = array (
				'example_rel_example_id' => '0',
				'example_rel_def_id' => '0' 
		);
	}
	public static function add($example_id, $word_id, $def_id) {
		if (! $example = example_model::getById ( $example_id )) {
			/* No such example */
			return false;
		}
		
		if (! $def = def_model::get ( $word_id, $def_id )) {
			/* No such def or def/word don't match */
			return false;
		}
		
		if ($examplerel = self::get ( $def_id, $example_id )) {
			/* Already associated */
			return false;
		}
		
		$query = "INSERT INTO {TABLE}examplerel (example_rel_example_id, example_rel_def_id) VALUES (%d, %d);";
		database::retrieve ( $query, 0, ( int ) $example_id, ( int ) $def_id );
		return true;
	}
	public static function delete($example_id, $word_id, $def_id) {
		if (! $example = example_model::getById ( $example_id )) {
			/* No such example */
			return false;
		}
		
		if (! $def = def_model::get ( $word_id, $def_id )) {
			/* No such def or def/word don't match */
			return false;
		}
		
		if (! $examplerel = self::get ( $def_id, $example_id )) {
			/* Alren't associated */
			return false;
		}
		
		$query = "DELETE FROM {TABLE}examplerel WHERE example_rel_example_id =%d AND example_rel_def_id =%d;";
		database::retrieve ( $query, 0, ( int ) $example_id, ( int ) $def_id );
		return true;
	}
	public static function get($def_id, $example_id) {
		$query = "SELECT * FROM {TABLE}examplerel WHERE example_rel_example_id =%d AND example_rel_def_id =%d;";
		$res = database::retrieve ( $query, 0, ( int ) $example_id, ( int ) $def_id );
		
		if ($row = database::get_row ( $res )) {
			$examplerel = database::row_from_template ( $row, self::$template );
			return $examplerel;
		}
		return false;
	}
}

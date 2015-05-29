<?php

namespace SmWeb;

class spelling_model implements model {
	public static $template;
	public static function init() {
		core::loadClass ( 'database' );
		self::$template = array (
				'spelling_id' => '0',
				'spelling_t_style' => '',
				'spelling_t_style_recorded' => '0',
				'spelling_k_style' => '',
				'spelling_k_style_recorded' => '0',
				'spelling_simple' => '',
				'spelling_sortkey' => '',
				'spelling_searchkey' => '',
				'spelling_sortkey_sm' => '' 
		);
	}
	public static function calcKStyle($name) {
		/*
		 * Takes a string of written Samoan and generates its k-style equivalant
		 * NB: K-style written samoan is unconventional
		 */
		$name = str_replace ( "t", "k", $name );
		$name = str_replace ( "n", "g", $name );
		$name = str_replace ( "T", "K", $name );
		$name = str_replace ( "N", "G", $name );
		/* This one's a bit more contentious, but seems to work as a general rule. */
		$name = str_replace ( "r", "l", $name );
		$name = str_replace ( "R", "L", $name );
		return $name;
	}
	public static function calcSortkey($name) {
		/* Strips out characters which are non-significant for sorting purposes */
		$name = strtolower ( $name );
		$name = str_replace ( "'", "", $name );
		$name = str_replace ( "ā", "a", $name );
		$name = str_replace ( "ē", "e", $name );
		$name = str_replace ( "ī", "i", $name );
		$name = str_replace ( "ō", "o", $name );
		$name = str_replace ( "ū", "u", $name );
		$name = str_replace ( "-", "", $name );
		return $name;
	}
	public static function calcSortkeySm($name) {
		/*
		 * Fun fact: This is the same algorithm you would use for a substitution cipher.
		 * see if you can figure out why I'm using one.
		 */
		$name = self::calcSortkey ( $name );
		$len = strlen ( $name );
		$name2 = "";
		for($i = 0; $i < $len; $i ++) {
			$c = substr ( $name, $i, 1 );
			$j = 0;
			foreach ( core::$alphabet_sm as $a ) {
				if ($a == $c) {
					$name2 .= core::$alphabet_en [$j];
					break;
				}
				$j ++;
			}
		}
		return $name2;
	}
	public static function calcSimple($name) {
		/* Kept distinct from the sortkey function for future-proofness, but they are currently the same. */
		return self::calcSortkey ( $name );
	}
	public static function calcSearchkey($name) {
		/*
		 * The most compressed form of the word, to match in searches. Loses case, k/t stylicity, macrons, stops, and spaces.
		 * Not intended to be human-readable.
		 */
		$name = self::calcSimple ( $name );
		$name = self::calcKStyle ( $name );
		$name = str_replace ( " ", "", $name );
		return $name;
	}
	
	/**
	 * Get spelling by its t-style representation
	 */
	public static function getBySpelling($spelling_t_style) {
		$query = "SELECT * FROM {TABLE}spelling WHERE spelling_t_style ='%s';";
		if ($row = database::retrieve ( $query, 1, $spelling_t_style )) {
			return self::fromRow ( $row );
		}
		return false;
	}
	
	/**
	 * Add a new spelling to the database
	 *
	 * @param string $spelling_t_style        	
	 */
	public static function add($spelling_t_style) {
		$spelling = self::$template;
		
		/* All the fun derived fields */
		$spelling ['spelling_t_style'] = $spelling_t_style;
		$spelling ['spelling_k_style'] = self::calcKStyle ( $spelling_t_style );
		$spelling ['spelling_simple'] = self::calcSimple ( $spelling_t_style );
		$spelling ['spelling_sortkey'] = self::calcSortkey ( $spelling_t_style );
		$spelling ['spelling_searchkey'] = self::calcSearchkey ( $spelling_t_style );
		$spelling ['spelling_sortkey_sm'] = self::calcSortkeySm ( $spelling_t_style );
		
		$query = "INSERT INTO {TABLE}spelling (spelling_id, spelling_t_style, spelling_t_style_recorded, " . "spelling_k_style, spelling_k_style_recorded, spelling_simple, spelling_sortkey, spelling_searchkey, " . "spelling_sortkey_sm) VALUES (NULL, '%s', '0', '%s', '0', '%s', '%s', '%s', '%s');";
		$spelling ['spelling_id'] = database::retrieve ( $query, 2, $spelling ['spelling_t_style'], $spelling ['spelling_k_style'], $spelling ['spelling_simple'], $spelling ['spelling_sortkey'], $spelling ['spelling_searchkey'], $spelling ['spelling_sortkey_sm'] );
		return $spelling;
	}
	private static function fromRow($row, $depth = 0) {
		return database::row_from_template ( $row, self::$template );
	}
}

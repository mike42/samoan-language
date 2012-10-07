<?php
class spelling_model {
	public static $template;
	
	public static function init() {
		core::loadClass('database');
		self::$template = array(
				'spelling_id'				=> '0',
				'spelling_t_style'			=> '',
				'spelling_t_style_recorded'	=> '0',
				'spelling_k_style'			=> '',
				'spelling_k_style_recorded'	=> '0',
				'spelling_simple'			=> '',
				'spelling_sortkey'			=> '',
				'spelling_searchkey'		=> '',
				'spelling_sortkey_sm'		=> '');
	}
	
	public static function calcKStyle($name) {
		/* Takes a string of written Samoan and generates its k-style equivalant
		 NB: K-style written samoan is unconventional */
		$name = str_replace("t","k",  $name);
		$name = str_replace("n","g",  $name);
		$name = str_replace("T","K",  $name);
		$name = str_replace("N","G",  $name);
		/* This one's a bit more contentious, but seems to work as a general rule. */
		$name = str_replace("r","l",  $name);
		$name = str_replace("R","L",  $name);
		return $name;
	}
	
	public static function calcSortkey($name) {
		/* Strips out characters which are non-significant for sorting purposes */
		$name = strtolower($name);
		$name = str_replace("'","",  $name);
		$name = str_replace("ā","a", $name);
		$name = str_replace("ē","e", $name);
		$name = str_replace("ī","i", $name);
		$name = str_replace("ō","o", $name);
		$name = str_replace("ū","u", $name);
		$name = str_replace("-","", $name);
		return $name;
	}
	
	public static function calcSortkeySm($name) {
		/* Fun fact: This is the same algorithm you would use for a substitution cipher.
		 see if you can figure out why I'm using one. */
		$name = $this-> calcSortkey($name);
		$len = strlen($name);
		$name2 = "";
		for($i = 0; $i < $len; $i++) {
			$c = substr($name,$i,1);
			$j = 0;
			foreach($this->alphabet_sm as $a) {
				if($a == $c) {
					$name2 .= $this->alphabet_en[$j];
					break;
				}
				$j++;
			}
		}
		return $name2;
	}
	
	public static function calcSimple($name) {
		/* Kept distinct from the sortkey function for future-proofness, but they are currently the same. */
		return self::calcSortkey($name);
	}
	
	public static function calcSearchkey($name) {
		/* The most compressed form of the word, to match in searches. Loses case, k/t stylicity, macrons, stops, and spaces.
		 Not intended to be human-readable. */
		$name = self::calcSimple($name);
		$name = self::calcKStyle($name);
		$name = str_replace(" ","",$name);
		return $name;
	}
	
}



?>
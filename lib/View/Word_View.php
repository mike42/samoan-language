<?php

namespace SmWeb;

class Word_View implements View {
	private static $config;
	private static $roman_numerals; // Used to label defs as i, ii, ii etc
	public static function init() {
		self::$roman_numerals = Array (
				"i",
				"ii",
				"iii",
				"iv",
				"v",
				"vi",
				"vii",
				"viii",
				"ix",
				"x",
				"xi",
				"xii",
				"xiii",
				"xiv",
				"xv",
				"xvi",
				"xvii",
				"xviii",
				"xix",
				"xx" 
		);
		
		Core::loadClass ( 'Def_View' );
		Core::loadClass ( 'Example_View' );
		Core::loadClass ( 'ListType_View' );
		Core::loadClass ( 'ListLang_View' );
		
		self::$config = Core::getConfig ( 'Core' );
	}
	public static function view_html(array $data) {
		$data ['titlebar'] = $data ['word'] ['rel_spelling'] ['spelling_t_style'] . " - Samoan Language Vocabulary";
		self::useTemplate ( "view", $data );
	}
	public static function edit_html(array $data) {
		$template = "edit";
		if (isset ( $data ['form'] )) {
			$template = "edit_" . $data ['form'];
		}
		$data ['title'] = "Editing " . $data ['word'] ['rel_spelling'] ['spelling_t_style'];
		self::useTemplate ( $template, $data );
	}
	public static function create_html(array $data) {
		self::useTemplate ( "create", $data );
	}
	public static function letter_html(array $data) {
		self::useTemplate ( "letter", $data );
	}
	public static function type_html(array $data) {
		self::useTemplate ( "type", $data );
	}
	public static function default_html(array $data) {
		self::useTemplate ( "default", $data );
	}
	public static function search_html(array $data) {
		self::useTemplate ( "search", $data );
	}
	public static function search_json(array $data) {
		header ( "content-type: application/json" );
		/* Construct heavily simplified key->val data structure for autocomplete use */
		$ret = array ();
		foreach ( $data ['words'] as $word ) {
			$id = Word_Model::getIdStrBySpellingNum ( $word ['rel_spelling'] ['spelling_t_style'], $word ['word_num'] );
			$defs = '';
			if ($word ['rel_target']) {
				$target_id = Word_Model::getIdStrBySpellingNum ( $word ['rel_target'] ['rel_spelling'] ['spelling_t_style'], $word ['rel_target'] ['word_num'] );
				$defs = "see $target_id";
			} else {
				$dl = array ();
				foreach ( $word ['rel_def'] as $key => $def ) {
					$dl [] = $def ['def_en'];
				}
				$defs = implode ( "; ", $dl );
			}
			$ret [] = array (
					'name' => $id,
					'label' => $defs 
			);
		}
		echo json_encode ( array (
				"words" => $ret 
		) );
	}
	public static function error_html(array $data) {
		if ($data ['error'] == "404") {
			header ( "HTTP/1.0 404 Not Found" );
			$data ['title'] = "Error - Word not found";
		}
		self::useTemplate ( "error", $data );
	}
	
	/**
	 * Show data using given template
	 *
	 * @param string $template
	 *        	Template to use
	 * @param mixed $data
	 *        	for the page
	 */
	private static function useTemplate($template, $data) {
		$permissions = Core::getPermissions ( 'page' );
		$view_template = dirname ( __FILE__ ) . "/template/Word/$template.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
	public static function toHTML($word) {
		$str = self::linkToWord ( $word ) . " ";
		
		if ($word ['rel_target']) {
			/* This definition is just a pointer/redirect */
			$str .= " see " . self::linkToWord ( $word ['rel_target'], true, false, true );
		} else {
			/* Find related words for header */
			$headerItems = array (
					"alt",
					"pl",
					"pv",
					"also" 
			);
			$inner = self::getInnerItems ( $headerItems, $word ['rel_words'] );
			if (count ( $inner ) != 0) {
				$str .= "(" . implode ( ", ", $inner ) . ") ";
			}
			
			if (count ( $word ['rel_def'] ) > 1) {
				$str .= "<dl>";
				$count = 0;
				/* Loop through definitions */
				foreach ( $word ['rel_def'] as $def ) {
					$str .= "<dd><small>" . strtoupper ( self::roman_numeral ( $count ) ) . "</small>. " . Def_View::toHTML ( $def ) . "</dd>";
					$count ++;
				}
				$str .= "</dl>";
			} elseif (count ( $word ['rel_def'] ) == 1) {
				$str .= Def_View::toHTML ( array_pop ( $word ['rel_def'] ) );
			}
			
			$footerItems = array (
					"from",
					"syn",
					"opp",
					"sals" 
			);
			$inner = self::getInnerItems ( $footerItems, $word ['rel_words'], true, false, true, 'rel_type_long' );
			if ($word ['word_origin_lang'] != '') {
				$inner [] = "from " . ListLang_View::externalDef ( $word ['rel_lang'], $word ['word_origin_word'] );
			}
			if (count ( $inner ) != 0) {
				$str .= "(" . implode ( ", ", $inner ) . ") ";
			}
		}
		
		return "<div class=\"sm-entry\">" . $str . "</div>\n";
	}
	private static function getInnerItems($items, $relatives, $show_audio = true, $bold = true, $link_to = false, $key = 'rel_type_short') {
		$ret = array ();
		foreach ( $items as $item ) {
			if (isset ( $relatives [$item] ) && count ( $relatives [$item] > 0 )) {
				/* Items of this type */
				$inner = array ();
				foreach ( $relatives [$item] as $relative ) {
					$inner [] = self::linkToWord ( $relative ['word'], $show_audio, $bold, $link_to );
				}
				$del = ($item == "from") ? " + " : ", ";
				$ret [] = (strlen ( $relatives [$item] [0] [$key] ) > 0 ? Core::escapeHTML ( $relative [$key] ) . " " : "") . implode ( $del, $inner );
			}
		}
		return $ret;
	}
	
	/**
	 *
	 * @param mixed $word
	 *        	The word to link to
	 * @param boolean $show_audio
	 *        	Set to true to include an audio link beside the word
	 * @param boolean $bold
	 *        	Set to true to show the word in boldface
	 * @return string
	 */
	public static function linkToWord($word, $show_audio = true, $bold = true, $link_to = true) {
		/* Figure out word link */
		$spelling = $word ['rel_spelling'] ['spelling_t_style'];
		$combined = $spelling . (($word ['word_num'] != 0) ? ( int ) $word ['word_num'] : "");
		$wordURL = Core::constructURL ( "word", "view", array (
				$combined 
		), "html" );
		$sub = (($word ['word_num'] != 0) ? "<sub><small>" . ( int ) $word ['word_num'] . "</small></sub>" : "");
		
		if ($show_audio) {
			if ($word ['rel_spelling'] ['spelling_t_style_recorded']) {
				/* Make link to audio */
				$audioOGG = Core::constructURL ( "audio", "listen", array (
						'spelling',
						$spelling 
				), "ogg" );
				$audioMP3 = Core::constructURL ( "audio", "listen", array (
						'spelling',
						$spelling 
				), "mp3" );
				$id = "word" . ( int ) $word ['word_id'] . "-sp" . ( int ) ($word ['rel_spelling'] ['spelling_id']);
				$audio = "<audio id=\"$id\" preload=\"none\">" . "<source src=\"" . Core::escapeHTML ( $audioOGG ) . "\" type=\"audio/ogg\" />" . "<source src=\"" . Core::escapeHTML ( $audioMP3 ) . "\" type=\"audio/mp3\" />" . "</audio>" . "<a href=\"javascript:void(0);\" onclick=\"audio_play('$id')\" title=\"" . Core::escapeHTML ( $spelling ) . "\">" . "<img src=\"" . Core::escapeHTML ( self::$config ['webroot'] ) . "style/images/listen.png\" border=0 /></a>";
			} else {
				/* Link to upload page */
				$audioURL = Core::constructURL ( "audio", "view", array (
						'spelling',
						$spelling 
				), "html" );
				$audio = "<a href=\"" . Core::escapeHTML ( $audioURL ) . "\" title=\"" . Core::escapeHTML ( $spelling ) . "\"><img src=\"" . Core::escapeHTML ( self::$config ['webroot'] ) . "style/images/no-sound.png\" border=0 /></a>";
				$audio = ""; // Hiding red audio links for now.
			}
		} else {
			/* No audio link at all */
			$audio = "";
		}
		
		$str = ($link_to ? "<a href=\"" . Core::escapeHTML ( $wordURL ) . "\">" : "") . ($bold ? "<b>" . Core::escapeHTML ( $spelling ) . $sub . "</b>" : Core::escapeHTML ( $spelling ) . $sub) . ($link_to ? "</a>" : "") . $audio;
		return "<span class=\"sm-word\">$str</span>";
	}
	public static function alphabeticPageLinks($sep = "\n", $internal = false) {
		$alphabet = Core::getAlphabet ();
		foreach ( $alphabet as $letter ) {
			$dest = $internal ? "#" . strtolower ( $letter ) : Core::constructURL ( "word", "letter", array (
					strtolower ( $letter ) 
			), "html" );
			$outp [] = "<a href=\"" . $dest . "\">" . Core::escapeHTML ( strtoupper ( $letter ) ) . "</a>";
		}
		return implode ( $sep, $outp );
	}
	public static function roman_numeral($count) {
		if (( int ) $count < count ( self::$roman_numerals )) {
			return self::$roman_numerals [$count];
		}
		return $count;
	}
	
	/**
	 * Return a combo box for viewing word-relation types (eg opposite, compound word, etc)
	 */
	public static function makeWordRelComboBox(array $list, $key = 'rel_type_id', $selected_id = '') {
		$str = "<select name=\"" . Core::escapeHTML ( $key ) . "\">\n";
		$str .= "<option value=\"\">(relation type)</option>";
		foreach ( $list as $listreltype ) {
			$selected = ($selected_id == $listreltype [$key]) ? " selected=\"selected\"" : "";
			$str .= "\t<option value=\"" . Core::escapeHTML ( $listreltype [$key] ) . "\"$selected>" . Core::escapeHTML ( $listreltype ['rel_type_long_label'] ) . "</option>\n";
		}
		
		$str .= "</select>\n";
		return $str;
	}
}

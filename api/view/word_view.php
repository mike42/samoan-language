<?php
class word_view {
	private static $config;
	private static $roman_numerals; // Used to label defs as i, ii, ii etc
	
	public function init() {
		self::$config = core::getConfig('core');
		self::$roman_numerals = Array("i","ii","iii","iv","v","vi","vii","viii","ix","x","xi","xii","xiii","xiv","xv","xvi","xvii","xviii","xix","xx");

		core::loadClass('def_view');
		core::loadClass('example_view');
		
		self::$config = core::getConfig('core');
	}
	
	public static function view_html($data) {
		$data['titlebar'] = $data['word']['rel_spelling']['spelling_t_style'] . " - Samoan Language Vocabulary";
		self::useTemplate("view", $data);
	}
	
	public static function edit_html($data) {
		$template = "edit";
		if(isset($data['form'])) {
			$template = "edit_".$data['form'];
		}
		$data['title'] = "Editing " . $data['word']['rel_spelling']['spelling_t_style'];
		self::useTemplate($template, $data);
	}
	
	public static function letter_html($data) {
		self::useTemplate("letter", $data);
	}
	
	public static function type_html($data) {
		self::useTemplate("type", $data);
	}
	
	public static function default_html($data) {
		self::useTemplate("default", $data);
	}
	
	public static function search_html($data) {
		self::useTemplate("search", $data);
	}
	
	public static function error_html($data) {
		if($data['error'] == "404") {
			header("HTTP/1.0 404 Not Found");
			$data['title'] = "Error &mdash; Word not found";
		}
		self::useTemplate("error", $data);
	}
	
	/**
	 * Show data using given template
	 * 
	 * @param string $template Template to use
	 * @param mixed $data	Data for the page
	 */
	private static function useTemplate($template, $data) {
		$permissions = core::getPermissions('page');
		$view_template = dirname(__FILE__)."/template/word/$template.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}
	
	
	public static function toHTML($word) {
		$str = self::linkToWord($word). " ";
		
		if($word['rel_target']) {
			/* This definition is just a pointer/redirect */
			$str .= " see ". self::linkToWord($word['rel_target'], true, false, true);
		} else {
			/* Find related words for header */
			$headerItems = array("alt", "pl", "pv", "also");
			$inner = self::getInnerItems($headerItems, $word['rel_words']);
			if(count($inner) != 0) {
				$str .= "(" . implode(", ", $inner) .") ";
			}
			
			if(count($word['rel_def']) > 1) {
				$str .= "<dl>";
				$count = 0;
				/* Loop through definitions */
				foreach($word['rel_def'] as $def) {
					$str .= "<dd><small>" . strtoupper(self::$roman_numerals[$count]) . "</small>. " .def_view::toHTML($def) . "</dd>";
					$count++;
				}
				$str .= "</dl>";
			} elseif(count($word['rel_def']) == 1) {
				$str .= def_view::toHTML(array_pop($word['rel_def']));
			}
			
			$footerItems = array("from", "syn", "opp", "sals");
			$inner = self::getInnerItems($footerItems, $word['rel_words'], true, false, true, 'rel_type_long');
			if(count($inner) != 0) {
				$str .= "(" . implode(", ", $inner) .") ";
			}
			
		}
		
		return "<div class=\"entry\">".$str."</div>\n";
	}
	
	static function getInnerItems($items, $relatives, $show_audio = true, $bold = true, $link_to = false, $key = 'rel_type_short') {
		$ret = array();
		foreach($items as $item) {
			if(isset($relatives[$item]) && count($relatives[$item] > 0)) {
				/* Items of this type */
				$inner = array();
				foreach($relatives[$item] as $relative) {
					$inner[] = self::linkToWord($relative['word'], $show_audio, $bold, $link_to);
				}
				$ret[] = (strlen($relatives[$item][0][$key]) > 0? core::escapeHTML($relative[$key]) . " " : "") . implode(", ", $inner);
			}
		}
		return $ret;
	}
	
	/**
	 * @param mixed $word The word to link to
	 * @param boolean $show_audio Set to true to include an audio link beside the word
	 * @param boolean $bold Set to true to show the word in boldface
	 * @return string
	 */
	public static function linkToWord($word, $show_audio = true, $bold = true, $link_to = true) {
		/* Figure out word link */
		$spelling = $word['rel_spelling']['spelling_t_style'];
		$combined = $spelling.(($word['word_num'] != 0)? (int)$word['word_num'] : "");
		$wordURL = core::constructURL("word", "view", array($combined), "html");
		$sub = (($word['word_num'] != 0)? "<sub><small>".(int)$word['word_num']."</small></sub>" : "");
		
		if($show_audio) {
			if($word['rel_spelling']['spelling_t_style_recorded']) {
				/* Make link to audio */
				$audioOGG = core::constructURL("audio", "listen", array($spelling), "ogg");
				$audioMP3 = core::constructURL("audio", "listen", array($spelling), "mp3");
				$audio = "<audio id=\"".urlencode($word['rel_spelling']['spelling_t_style'])."\" preload=\"none\">" .
							"<source src=\"".core::escapeHTML($audioOGG)."\" type=\"audio/ogg\" />" .
							"<source src=\"".core::escapeHTML($audioMP3)."\" type=\"audio/mp3\" />" .
							"</audio>" .
							"<a href=\"javascript:void(0);\" onclick=\"audio_play('".urlencode($spelling)."')\" title=\"".core::escapeHTML($spelling)."\">" . 
							"<img src=\"".core::escapeHTML(self::$config['webroot'])."style/images/listen.png\" border=0/></a>";
			} else {
				/* Link to upload page */
				$audioURL = core::constructURL("audio", "view", array($spelling), "html");
				$audio = "<a href=\"".core::escapeHTML($audioURL)."\" title=\"".core::escapeHTML($spelling)."\"><img src=\"".core::escapeHTML(self::$config['webroot'])."style/images/no-sound.png\" border=0/></a>";
			}
		} else {
			/* No audio link at all */
			$audio = "";
		}
		
		$str = ($link_to? "<a href=\"".core::escapeHTML($wordURL)."\">" : "").
					($bold? "<b>".core::escapeHTML($spelling).$sub . "</b>" : core::escapeHTML($spelling).$sub) .
					($link_to? "</a>" : "") . $audio;
		return $str;

	}
	
	public static function alphabeticPageLinks($sep = "\n") {
		$alphabet = core::getAlphabet();
		foreach($alphabet as $letter) {
			$outp[] = "<a href=\"".core::constructURL("word", "letter", array(strtolower($letter)), "html"). "\">".core::escapeHTML(strtoupper($letter))."</a>";
		}
		return implode($sep, $outp);
	}
}


?>
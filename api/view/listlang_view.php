<?php
class listlang_view {
	public static function init() {
		
	}
	
	/**
	 * Make a compbo box for the user to select a language
	 */
	public static function makeComboBox($list, $key = 'lang_id', $selected_id = '') {
		$str = "<select name=\"".core::escapeHTML($key)."\">\n";

		$str .= "\t<option value=\"\">(none)</option>\n";
		foreach($list as $listtype) {
			$selected = ($selected_id == $listtype[$key])? " selected=\"selected\"" : "";
			$str .= "\t<option value=\"".core::escapeHTML($listtype[$key])."\"$selected>" . 
					core::escapeHTML($listtype['lang_name']) . "</option>\n";
		}

		$str .= "</select>\n";
		return $str;
	}
	
	
	
	/**
	 * Link to an external definition.
	 */
	public static function externalDef($listlang, $word_origin_word) {
		return core::escapeHTML($listlang['lang_name']) . " <i><a href=\"".core::escapeHTML("http://en.wiktionary.org/wiki/".$word_origin_word."#".$listlang['lang_name'])."\">".core::escapeHTML($word_origin_word)."</a></i>";
	}
}
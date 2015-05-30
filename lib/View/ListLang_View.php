<?php

namespace SmWeb;

class ListLang_View implements View {
	public static function init() {
	}
	
	/**
	 * Make a compbo box for the user to select a language
	 */
	public static function makeComboBox($list, $key = 'lang_id', $selected_id = '') {
		$str = "<select name=\"" . Core::escapeHTML ( $key ) . "\">\n";
		
		$str .= "\t<option value=\"\">(none)</option>\n";
		foreach ( $list as $listtype ) {
			$selected = ($selected_id == $listtype [$key]) ? " selected=\"selected\"" : "";
			$str .= "\t<option value=\"" . Core::escapeHTML ( $listtype [$key] ) . "\"$selected>" . Core::escapeHTML ( $listtype ['lang_name'] ) . "</option>\n";
		}
		
		$str .= "</select>\n";
		return $str;
	}
	
	/**
	 * Link to an external definition.
	 */
	public static function externalDef($listlang, $word_origin_word) {
		return Core::escapeHTML ( $listlang ['lang_name'] ) . " <i><a href=\"" . Core::escapeHTML ( "http://en.wiktionary.org/wiki/" . $word_origin_word . "#" . $listlang ['lang_name'] ) . "\">" . Core::escapeHTML ( $word_origin_word ) . "</a></i>";
	}
}

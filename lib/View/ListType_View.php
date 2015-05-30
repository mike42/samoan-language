<?php

namespace SmWeb;

class ListType_View implements View {
	public static function init() {
	}
	public static function makeComboBox($list, $key = 'type_id', $selected_id = '') {
		$str = "<select name=\"" . Core::escapeHTML ( $key ) . "\">\n";
		
		foreach ( $list as $listtype ) {
			$selected = ($selected_id == $listtype [$key]) ? " selected=\"selected\"" : "";
			$str .= "\t<option value=\"" . Core::escapeHTML ( $listtype [$key] ) . "\"$selected>" . Core::escapeHTML ( $listtype ['type_name'] ) . "</option>\n";
		}
		
		$str .= "</select>\n";
		return $str;
	}
}

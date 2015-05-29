<?php

namespace SmWeb;

class listtype_view implements view {
	public static function init() {
	}
	public static function makeComboBox($list, $key = 'type_id', $selected_id = '') {
		$str = "<select name=\"" . core::escapeHTML ( $key ) . "\">\n";
		
		foreach ( $list as $listtype ) {
			$selected = ($selected_id == $listtype [$key]) ? " selected=\"selected\"" : "";
			$str .= "\t<option value=\"" . core::escapeHTML ( $listtype [$key] ) . "\"$selected>" . core::escapeHTML ( $listtype ['type_name'] ) . "</option>\n";
		}
		
		$str .= "</select>\n";
		return $str;
	}
}

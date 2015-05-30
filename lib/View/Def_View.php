<?php

namespace SmWeb;

class Def_View implements View {
	public static function init() {
		Core::loadClass ( 'Def_Model' );
	}
	public static function toHTML($def) {
		$typeStr = "";
		if ($def ['rel_type'] ['type_id'] != '0') {
			/* Definition type info */
			$type = $def ['rel_type'];
			$typeURL = Core::constructURL ( "word", "type", array (
					$type ['type_short'] 
			), "html" );
			$typeStr = "<i class=\"type-link\">" . "<a href=\"" . Core::escapeHTML ( $typeURL ) . "\">" . Core::escapeHTML ( $type ['type_abbr'] ) . "</a></i> ";
		}
		
		$str = $typeStr . Core::escapeHTML ( $def ['def_en'] );
		
		/* Append any examples */
		$any = false;
		foreach ( $def ['rel_example'] as $example ) {
			if (! $any) {
				$any = true;
				$str .= " &mdash; ";
			} else {
				$str .= ". ";
			}
			$str .= Example_View::toHTML ( $example ) . ": " . Core::escapeHTML ( $example ['example_en'] );
		}
		/* Always finish with semicolon */
		$str .= "; ";
		return $str;
	}
}

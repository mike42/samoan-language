<?php
class def_view {
	public static function init() {
		core::loadClass('def_model');
	}

	public static function toHTML($def) {
		$typeStr = "";
		if($def['rel_type']['type_id'] != '0') {
			/* Definition type info */
			$type = $def['rel_type'];
			$typeURL = core::constructURL("word", "type", array($type['type_short']), "html");
			$typeStr = "<i class=\"type-link\">" .
					"<a href=\"".core::escapeHTML($typeURL)."\">" .
					core::escapeHTML($type['type_abbr'])."</a></i> ";
		}

		$str = $typeStr . core::escapeHTML($def['def_en']);

		/* Append any examples */
		$any = false;
		foreach($def['rel_example'] as $example) {
			if(!$any) {
				$any = true; $str .= " &mdash; ";
			} else {
				$str .= ". ";
			}
			$str .= example_view::toHTML($example).": ".core::escapeHTML($example['example_en']);
		}
		/* Always finish with semicolon */
		$str .= "; ";
		return $str;
	}

}
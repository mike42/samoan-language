<?php
class example_view {
	public static $config;

	public static function init() {
		self::$config = core::getConfig('core');
	}

	public static function view_html($data) {
		$data['title'] = "Language examples";
		self::useTemplate('view', $data);
	}

	public static function edit_html($data) {
		$data['title'] = "Editing example";
		self::useTemplate('edit', $data);
	}

	public static function create_html($data) {
		$data['title'] = "Add example";
		self::useTemplate('create', $data);
	}

	public static function search_html($data) {
		$data['title'] = "Search examples";
		self::useTemplate('search', $data);
	}

	public static function error_html($data) {
		if($data['error'] == "404") {
			header("HTTP/1.0 404 Not Found");
			$data['title'] = "Error - Example not found";
		} else if($data['error'] == "403") {
			header("HTTP/1.0 403 Forbidden");
			$data['title'] = "Error - Forbidden";

		}
		self::useTemplate('error', $data);
	}

	private static function useTemplate($template, $data) {
		$permissions = core::getPermissions('example');
		$view_template = dirname(__FILE__)."/template/example/$template.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}

	public static function toHTML($example, $show_en = true) {
		$inp = $example['example_str'];
		$str = '';

		/* Parsing for [ | ] setup */
		$inlink = $pasttarget = false;
		$target = $text = "";

		for($i = 0; $i < mb_strlen($inp); $i++) {
			/* Get current char */
			$c = mb_substr($inp,$i,1);
			if($inlink) {
				if($c == "]" || $c == "." || $c == "," || $c == "?" || $c == "!") {
					$str .= self::linkToWord($target, $text);
					$inlink = $pasttarget = false;
					$target = $text = "";
					if($c != "]") {
						/* Append punctuation other than ] which caused exit */
						$str .= $c;
					}
				} elseif($c == "|") {
					/* Target is finalised now, but clear text and go again */
					$pasttarget = true;
					$text = "";
				} elseif(!$pasttarget) {
					/* Adding to target and (non-numeric bits only) to text */
					if(!is_numeric($c)) {
						$text .= $c;
					}
					$target .= $c;
				} else {
					/* We have passed a |, so only append to text */
					$text .= $c;
				}
			} elseif ($c == "[") {
				/* Start being in a link */
				$inlink = true;
			} elseif($c != "]" && $c != "<" && $c != ">") {
				/* Because of a strange bug dropping the macron-ed letters,
				 I've removed html_escape in favour of this: */
				$str .= $c;
			}
		}

		return "<span class=\"example-sm\">".$str."</span>";
	}

	private static function linkToWord($target, $text) {
		/* Make a link to a word referenced in this example */
		$target = strtolower($target);
		$targetURL = core::constructURL("word", "view", array($target), "html");
		return "<a href=\"".core::escapeHTML($targetURL)."\">".core::escapeHTML($text)."</a>";
	}
}

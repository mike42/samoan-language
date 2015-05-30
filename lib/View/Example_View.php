<?php

namespace SmWeb;

class Example_View implements View {
	public static $config;
	public static function init() {
		self::$config = Core::getConfig ( 'Core' );
	}
	public function view_html(array $data) {
		$data ['title'] = "Language examples";
		self::useTemplate ( 'view', $data );
	}
	public function edit_html(array $data) {
		$data ['title'] = "Editing example";
		self::useTemplate ( 'edit', $data );
	}
	public function create_html(array $data) {
		$data ['title'] = "Add example";
		self::useTemplate ( 'create', $data );
	}
	public function search_html(array $data) {
		$data ['title'] = "Search examples";
		self::useTemplate ( 'search', $data );
	}
	public function error_html(array $data) {
		if ($data ['error'] == "404") {
			header ( "HTTP/1.0 404 Not Found" );
			$data ['title'] = "Error - Example not found";
		} else if ($data ['error'] == "403") {
			header ( "HTTP/1.0 403 Forbidden" );
			$data ['title'] = "Error - Forbidden";
		}
		self::useTemplate ( 'error', $data );
	}
	private static function useTemplate($template, $data) {
		$permissions = Core::getPermissions ( 'example' );
		$view_template = dirname ( __FILE__ ) . "/template/Example/$template.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
	public static function toHTML($example, $show_en = true, $plain = false) {
		$inp = $example ['example_str'];
		$str = '';
		
		/* Parsing for [ | ] setup */
		$inlink = $pasttarget = false;
		$target = $text = "";
		
		for($i = 0; $i < mb_strlen ( $inp ); $i ++) {
			/* Get current char */
			$c = mb_substr ( $inp, $i, 1 );
			if ($inlink) {
				if ($c == "]" || $c == "." || $c == "," || $c == "?" || $c == "!") {
					if (! $plain) {
						$str .= self::linkToWord ( $target, $text );
					} else {
						$str .= $text;
					}
					$inlink = $pasttarget = false;
					$target = $text = "";
					if ($c != "]") {
						/* Append punctuation other than ] which caused exit */
						$str .= $c;
					}
				} elseif ($c == "|") {
					/* Target is finalised now, but clear text and go again */
					$pasttarget = true;
					$text = "";
				} elseif (! $pasttarget) {
					/* Adding to target and (non-numeric bits only) to text */
					if (! is_numeric ( $c )) {
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
			} elseif ($c != "]" && $c != "<" && $c != ">") {
				/*
				 * Because of a strange bug dropping the macron-ed letters,
				 * I've removed html_escape in favour of this:
				 */
				$str .= ($c == "\n") ? ($plain ? "\n" : "<br />") : $c;
			}
		}
		
		return $plain ? $str : "<span class=\"example-sm\">" . $str . "</span>";
	}
	private static function linkToWord($target, $text) {
		/* Make a link to a word referenced in this example */
		$target = strtolower ( $target );
		$targetURL = Core::constructURL ( "word", "view", array (
				$target 
		), "html" );
		return "<a href=\"" . Core::escapeHTML ( $targetURL ) . "\">" . Core::escapeHTML ( $text ) . "</a>";
	}
}

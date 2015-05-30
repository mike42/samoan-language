#!/usr/bin/env php
<?php

namespace SmWeb;
/* Export XDXF version of the database */
require_once(dirname(__FILE__) . "/../../../lib/Core.php");
Core::loadClass("Database");
Core::loadClass("ListType_Model");
Core::loadClass("Word_Model");
Core::loadClass("Example_View");

function word_toXDXF($word, $indent) {
	$spelling = $word['rel_spelling']['spelling_t_style'];
	$combined = esc($spelling).(($word['word_num'] != 0)? "<sub>".(int)$word['word_num']."</sub>" : "");
	
	// Name of word
	$str = "$indent<ar>\n$indent\t<k>" . $combined . "</k>\n"; 
	foreach($word['rel_def'] as $def) {
		$str .= "$indent\t<def>\n";
		if($def['rel_type']['type_abbr'] != "") {
			$str .= "$indent\t\t<gr><abbr>" . esc($def['rel_type']['type_abbr']) . "</abbr></gr>\n";
		}
		$str .= "$indent\t\t" . esc($def['def_en']) . "\n";
		foreach($def['rel_example'] as $example) {
			$sm = Example_View::toHTML($example, false, true);
			$str .= "$indent\t\t<ex>\n" .
				"$indent\t\t\t<ex_orig>" . esc($sm) . "</ex_orig>\n" .
				"$indent\t\t\t<ex_trans>" . esc($example['example_en']) . "</ex_trans>\n" .
				"$indent\t\t</ex>\n";
		}
		$str .= "$indent\t</def>\n";
	}
	$str .= "$indent</ar>\n";
	return $str;
}

function esc($in) {
	/* Escape an XML value */
	return Core::escapeHTML($in);
}

$abbr = ListType_Model::listAll();
$date = $date = date("d-m-Y"); // I strongly dislike this format
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE xdxf SYSTEM "https://raw.github.com/soshial/xdxf_makedict/master/format_standard/xdxf_strict.dtd">
<xdxf lang_from="SMO" lang_to="ENG" format="logical" revision="032beta">
	<meta_info>
		<title>Samoan Language Vocabulary</title>
		<full_title>Samoan Language Vocabulary</full_title>
		<description>Samoan language words with English meanings, collected by Michael Billington. See http://http://mike.bitrevision.com/samoan/</description>
		<abbreviations>
<?php
		foreach($abbr as $a) {
			if($a['type_abbr'] != "") {
				echo "\t\t\t<abbr_def><abbr_k>" . esc($a['type_abbr']) . "</abbr_k> <abbr_v>" . esc($a['type_name']) . "</abbr_v></abbr_def>\n";
			}
		}
?>		</abbreviations>
		<file_ver>001</file_ver>
		<creation_date><?php echo $date ?></creation_date>
	</meta_info>
	<lexicon>
<?php
		foreach(Core::$alphabet_sm as $a) {
			$words = Word_Model::listByLetter($a);

			foreach($words as $word) {
				echo word_toXDXF($word, "\t\t");
			}
		}
?>	</lexicon>
</xdxf>

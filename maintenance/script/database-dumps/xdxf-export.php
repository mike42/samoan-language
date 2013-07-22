#!/usr/bin/env php
<?php
/* Export XDXF version of the database */
require_once(dirname(__FILE__) . "/../../../api/core.php");
core::loadClass("database");
core::loadClass("listtype_model");
core::loadClass("word_model");
core::loadClass("example_view");

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
			$sm = example_view::toHTML($example, false, true);
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
	return core::escapeHTML($in);
}

$abbr = listtype_model::listAll();
$date = $date = date("d-m-Y"); // I strongly dislike this format
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
?><!DOCTYPE xdxf SYSTEM "https://raw.github.com/soshial/xdxf_makedict/master/format_standard/xdxf_strict.dtd">
<xdxf lang_from="SMO" lang_to="ENG" format="logical" revision="032beta">
	<meta_info>
		<title>Samoan Language Vocabulary</title>
		<full_title>Samoan Language Vocabulary</full_title>
		<description>Samoan language words with English meanings, collected by Michael Billington. See http://http://mike.bitrevision.com/samoan/</description>
		<abbreviations>
<?
		foreach($abbr as $a) {
			if($a['type_abbr'] != "") {
				echo "\t\t\t<abbr_def><abbr_k>" . esc($a['type_abbr']) . "</abbr_k> <abbr_v>" . esc($a['type_name']) . "</abbr_v></abbr_def>\n";
			}
		}
?>		</abbreviations>
		<file_ver>001</file_ver>
		<creation_date><? echo $date ?></creation_date>
	</meta_info>
	<lexicon>
<?
		foreach(core::$alphabet_sm as $a) {
			$words = word_model::listByLetter($a);

			foreach($words as $word) {
				echo word_toXDXF($word, "\t\t");
			}
		}
?>	</lexicon>
</xdxf>

<style>
.ui-autocomplete-loading {
	background: white url('<?php echo self::$config['webroot']; ?>style/images/ui-anim_basic_16x16.gif') right center no-repeat;
}

.ui-autocomplete {
	font-size: 75%;
}
</style>
<script>
	$(function() {
		function log( message ) {
			$( "<div>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
 
		$( "#word" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "<?php echo core::constructURL("word", "search", array("suggest"), "json"); ?>",
					type: "post",
					dataType: "json",
					data: {
    					term: request.term
    				},
					success: function( data ) {
						response( $.map( data.words, function( item ) {
							return {
								label: item.name + ' (' + item.label + ')',
								value: item.name
							}
						}));
					}
				});
			},
			minLength: 1,
		});
	});

	function delRel(word_id) {
		if(confirm("Are you sure you want to delete this relation?")) {
			$("#word_id").val(word_id);
			$("#delRel").submit();
		}
	}
</script>


<?php
$rel_type_id = $data['rel_type_id'];
echo "<form id=\"addRel\" action=\"".core::constructURL("word", "edit", array($data['id'], "rel", $rel_type_id), "html")."\" method=\"POST\">";
echo "<table class=\"word-edit\">\n";
echo "<input type=\"hidden\" name=\"addRel\" value=\"yes\" />";
if(isset($data['word']['rel_words'][$rel_type_id])) {
	/* Show current members of this category */
	echo "\t<caption>".core::escapeHTML($data['word']['rel_words'][$rel_type_id][0]['rel_type_long_label'])."</caption>\n";
	echo "<input type=\"hidden\" name=\"rel_type_id\" value=\"".core::escapeHTML($rel_type_id)."\" />";
	echo "<tr><th>Items:</th><td>";
	foreach($data['word']['rel_words'][$rel_type_id] as $wordrel) {
		echo word_view::linkToWord($wordrel['word'], false, false, true);
		echo " <a href=\"#\" onClick=\"delRel(".(int)$wordrel['word']['word_id'].")\" style=\"color:#f00 \">[x]</a><br />";
	}
	echo "</td>";
} else {
	/* Show selector box for category */
	echo "\t<caption>Add related word</caption>\n";
	echo "<tr><th>Type:</th><td>".word_view::makeWordRelComboBox($data['listreltype'])."</td></tr>";


}
/* Show submit and back buttons */
echo "<tr><th>Word:</th><td class=\"ui-widget\">";
echo "<input name=\"word\" id=\"word\" />";
if(isset($data['message'])) {
	echo "<div style=\"color: #f00\">".core::escapeHTML($data['message'])."</div>";
}
echo "</td></tr>";
echo "<tr><td colspan=\"2\" style=\"text-align: right\" />";
echo "<a href=\"".core::constructURL('word', 'edit', array($data['id']), 'html')."\">[back to word]</a><input type=\"submit\" value=\"Add word\" />";
echo "</td></tr>";
echo "</table>";
echo "</form>";


if(isset($data['word']['rel_words'][$rel_type_id])) {
	echo "<form id=\"delRel\" action=\"".core::constructURL("word", "edit", array($data['id'], "rel", $rel_type_id), "html")."\" method=\"POST\">";
	echo "<input type=\"hidden\" name=\"delRel\" value=\"yes\" />";
	echo "<input type=\"hidden\" name=\"rel_type_id\" value=\"".core::escapeHTML($rel_type_id)."\" />";
	echo "<input type=\"hidden\" name=\"word_id\" value=\"\" id=\"word_id\" />";
	echo "</form>";
}
?>
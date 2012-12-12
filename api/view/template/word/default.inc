<p>
	This is a non-exhaustive vocabulary of Samoan words. This section
	contains examples, recordings, and English translations for many of the words which appear on the site.
	It currently contains <b><?php echo (int)$data['counts']['word']; ?> entries</b> with <b><?php echo (int)$data['counts']['audio']; ?> recordings</b> and <b><?php echo (int)$data['counts']['example']; ?> examples</b>.
</p>
<p>Browse alphabetically:</p>
<ul>
	<li><? echo word_view::alphabeticPageLinks() ;?></li>
</ul>

<form id="form-type" action="words.php" method="post"
	style="padding: 0; margin: 0">
	<input type="hidden" name="action" value="type" />
	<p>Some other options:
	
	
	<ul>
		<li>Browse by word type: <?		/*		$field_id="type";
$form = "<select name=\"$field_id\" onChange=\"form_submit('$field_id');\" style=\"padding:0;margin:0\">";
$res = $samoan -> db -> type_enumerate();
while($data = $samoan -> db -> get_row($res)) {
$type = $samoan -> db -> toType($data);
$form .= "	<option value=\"".(int)$type->type_id."\">".$samoan->html_escape($type->type_name)."</option>";
}
$form .= "</select><input type=\"submit\" value=\"Go\" style=\"display:none;\"/>"; echo $form; */ ?>
		</li>
		<li><a href="/words/letter/all">View the entire vocabulary on single
				page</a></li>
	</ul>
</form>

<p style="display: none;">
	If you wish to contribute extra content, please contact me via email to
	arrange access: <a href="mailto:michael.billington@gmail.com">michael.billington@gmail.com</a>
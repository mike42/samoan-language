<script type="text/javascript">
	function previewPage() {
		$('#action').val('preview');
		$('#frmedit').submit();
	}

	function deletePage() {
		if(confirm('Are you sure you want to delete this page?')) {
			$('#action').val('delete');
			$('#frmedit').submit();
		}
	}
</script>

<div class="editform">
	<form id="frmedit" action="<? echo core::constructURL('page', 'edit', array($data['id']), 'html'); ?>" method="post">
		<input type="hidden" name="action" id="action" value="save">
		<table>
			<tr>
				<td>Page title:</td>
				<td><input type="text" name="revision_title" value="<?php echo core::escapeHTML($data['page']['page_rel_revision']['revision_title']); ?>"/></td>
			</tr>
			<? if(isset($data['preview'])) {
				echo "<div class=\"notice\">Note: This is only a preview, your changes have not been saved!</div>";
				echo "<hr>";
				echo $data['page']['page_rel_revision']['revision_text_parsed'];
				echo "<hr>";
			} ?>
			<tr>
				<td colspan=2>
					<textarea id="page-editbox" name="revision_text" cols=65 rows=20><?php echo core::escapeHTML($data['page']['page_rel_revision']['revision_text']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan=2 style="text-align: right">
					<? $dest = core::constructURL('page', 'view', array($data['id']), 'html'); ?>
					<a href="<? echo $dest; ?>">[cancel edit]</a>
					<? 	if($permissions['delete']) {
							echo "<input type=\"button\" onClick=\"deletePage()\" class=\"danger\" value=\"Delete\" />";
						}
					?>
					<input type="button" onClick="previewPage()" value="Preview" />
					<input type="submit" name="save" value="Save" />
				</td>
			</tr>
		</table>
	</form>
</div>

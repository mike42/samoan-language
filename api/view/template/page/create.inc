<p>
	Please click the button below to confirm that you would like to create
	the page '<b><?php  echo core::escapeHTML($data['id'])?>
	</b>'.
</p>
<div style="float: right">
	<form
		action="<?php  echo core::constructURL('page', 'create', array($data['id']), 'html');  ?>"
		method="post">
		<input type="submit" name="submit" value="Create page" />
	</form>
</div>

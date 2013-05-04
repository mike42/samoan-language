<form method="post"
	action="<?php  echo core::constructURL("user", "login", array(""), "html"); ?>">
	<?php
	if(isset($data['message'])) {
		echo "<div style=\"color: #f00\">".core::escapeHTML($data['message'])."</div>";
	}
	?>
	<table>
		<tr>
			<td>Username or email:</td>
			<td><?php 
			/* Put submitted username back in the username box */
			$value = "";
			if(isset($_POST['user_name'])) {
				$value = $_POST['user_name'];
				} ?> <input type="text" name="user_name"
				value="<?php echo core::escapeHTML($value) ?>" style="width: 170px;" />
			</td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="user_password" value=""
				style="width: 170px;" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><input type="submit"
				name="submit" value="Log in" /></td>
		</tr>

	</table>
</form>

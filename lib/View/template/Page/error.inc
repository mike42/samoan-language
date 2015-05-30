<?php
namespace SmWeb;

switch($data['error']) {
	case '403':
		echo "<p>You don't have permission to do that. Please contact the site owner for access.</p>";
		break;
	default:
		echo "<p>The page you were looking for does not exist.</p>";
		if($permissions['create']) {
			echo "Would you like to <a href=\"".Core::constructURL("page", "create", array($data['id']), "html")."\">create it</a>?";
		}

}

?>
<?php
namespace SmWeb;
 
if($permissions['edit']) {
	echo "<div style=\"float:right\"><a href=\"".Core::constructURL('page', 'edit', array($data['id']), 'html')."\">(edit)</a></div>";
}

echo $data['page']['page_rel_revision']['revision_text_parsed']; ?>
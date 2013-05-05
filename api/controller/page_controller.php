<?php 
class page_controller {
	public static function init() {
		core::loadClass('page_model');
	}

	public static function view($id) {
		if($page = page_model::getByShort($id)) {
			if(!($page['page_rel_revision']['revision_parse_valid'] == 1)) {
				$page['page_rel_revision']['revision_text_parsed'] = page_model::render($page);
				revision_model::cache_save($page['page_rel_revision']);
			}
			return array('title' => $page['page_rel_revision']['revision_title'], 'page' => $page, 'id' => $id);
		} else {
			return array('title' => 'Not found', 'error' => '404', 'id' => $id);
		}
	}

	public static function create($id) {
		if($page = page_model::getByShort($id)) {
			/* Page already exists - redirect to it */
			$url = core::constructURL('page', 'view', array($id), 'html');
			core::redirect($url);
		}

		$permissions = core::getPermissions('page');
		if(!$permissions['create']) {
			/* No permission */
			return array('title' => 'Forbidden', 'error' => '403', 'id' => $id);
		}

		if(!isset($_POST['submit'])) {
			/* Has not submitted (show form) */
			return array('title' => 'Create page', 'id' => $id);
		}

		/* Create the page and go right to the edit form */
		page_model::insert($id);
		$url = core::constructURL('page', 'edit', array($id), 'html');
		core::redirect($url);
	}


	public static function edit($id) {
		$permissions = core::getPermissions('page');

		if(!$permissions['edit']) {
			/* No permission */
			return array('title' => 'Forbidden', 'error' => '403', 'id' => $id);
		}

		if(!$page = page_model::getByShort($id)) {
			/* Page does not exist -- Go to create it instead */
			$url = core::constructURL('page', 'create', array($id), 'html');
			core::redirect($url);
			return;
		}

		if(!isset($_POST['action'])) {
			/* Has not submitted (show the edit form) */
			return array('title' => "Editing '". $page['page_rel_revision']['revision_title'] . "'", 'id' => $id, 'page' => $page);
		}

		$revision = revision_model::$template;
		$revision['revision_page_id'] = $page['page_id'];
		$revision['revision_title'] = $_POST['revision_title'];
		$revision['revision_text']  = $_POST['revision_text'];
		if($author = session::getUser()) {
			$revision['revision_author'] = $author['user_id'];
		} else {
			$revision['revision_author'] = 0;
		}
		$page['page_rel_revision'] = $revision;

		if($_POST['action'] == "save") {
			/* Actually submit */
			revision_model::insert($page, $revision);
				
			/* Go back to the page */
			$url = core::constructURL('page', 'view', array($id), 'html');
			core::redirect($url);
			return;
		} else if($_POST['action'] == "delete") {
			if(!$permissions['delete']) {
				return array('title' => 'Forbidden', 'error' => '403', 'id' => $id);
			}
				
			page_model :: delete($page['page_id']);

			/* Take the user to the (now deleted) site */
			$url = core::constructURL('page', 'view', array($id), 'html');
			core::redirect($url);
			return;
		}
		/* Preview type thing */
		$page['page_rel_revision']['revision_text_parsed'] = page_model::render($page);
		return array('title' => "Editing '". $page['page_rel_revision']['revision_title'] . "'", 'id' => $id, 'page' => $page, 'preview' => true);
	}

	public static function purge($id) {
		if(!$page = page_model::getByShort($id)) {
			return array('title' => 'Not found', 'error' => '404', 'id' => $id);
		}

		revision_model::cache_purge_page($page['page_id']);
		$url = core::constructURL('page', 'view', array($page['page_short']), 'html');
		core::redirect($url);
	}
}
?>

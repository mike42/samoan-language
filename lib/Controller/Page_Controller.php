<?php

namespace SmWeb;

class Page_Controller implements Controller {
	public static function init() {
		Core::loadClass ( 'Page_Model' );
	}
	public static function view($id) {
		if ($page = Page_Model::getByShort ( $id )) {
			if (! ($page ['page_rel_revision'] ['revision_parse_valid'] == 1)) {
				$page ['page_rel_revision'] ['revision_text_parsed'] = Page_Model::render ( $page );
				Revision_Model::cache_save ( $page ['page_rel_revision'] );
			}
			return array (
					'title' => $page ['page_rel_revision'] ['revision_title'],
					'page' => $page,
					'id' => $id 
			);
		} else {
			return array (
					'title' => 'Not found',
					'error' => '404',
					'id' => $id 
			);
		}
	}
	public static function create($id) {
		if ($page = Page_Model::getByShort ( $id )) {
			/* Page already exists - redirect to it */
			$url = Core::constructURL ( 'page', 'view', array (
					$id 
			), 'html' );
			Core::redirect ( $url );
		}
		
		$permissions = Core::getPermissions ( 'page' );
		if (! $permissions ['create']) {
			/* No permission */
			return array (
					'title' => 'Forbidden',
					'error' => '403',
					'id' => $id 
			);
		}
		
		if (! isset ( $_POST ['submit'] )) {
			/* Has not submitted (show form) */
			return array (
					'title' => 'Create page',
					'id' => $id 
			);
		}
		
		/* Create the page and go right to the edit form */
		Page_Model::insert ( $id );
		$url = Core::constructURL ( 'page', 'edit', array (
				$id 
		), 'html' );
		Core::redirect ( $url );
	}
	public static function edit($id) {
		$permissions = Core::getPermissions ( 'page' );
		
		if (! $permissions ['edit']) {
			/* No permission */
			return array (
					'title' => 'Forbidden',
					'error' => '403',
					'id' => $id 
			);
		}
		
		if (! $page = Page_Model::getByShort ( $id )) {
			/* Page does not exist -- Go to create it instead */
			$url = Core::constructURL ( 'page', 'create', array (
					$id 
			), 'html' );
			Core::redirect ( $url );
			return;
		}
		
		if (! isset ( $_POST ['action'] )) {
			/* Has not submitted (show the edit form) */
			return array (
					'title' => "Editing '" . $page ['page_rel_revision'] ['revision_title'] . "'",
					'id' => $id,
					'page' => $page 
			);
		}
		
		$revision = Revision_Model::$template;
		$revision ['revision_page_id'] = $page ['page_id'];
		$revision ['revision_title'] = $_POST ['revision_title'];
		$revision ['revision_text'] = $_POST ['revision_text'];
		if ($author = Session::getUser ()) {
			$revision ['revision_author'] = $author ['user_id'];
		} else {
			$revision ['revision_author'] = 0;
		}
		$page ['page_rel_revision'] = $revision;
		
		if ($_POST ['action'] == "save") {
			/* Actually submit */
			Revision_Model::insert ( $page, $revision );
			
			/* Go back to the page */
			$url = Core::constructURL ( 'page', 'view', array (
					$id 
			), 'html' );
			Core::redirect ( $url );
			return;
		} else if ($_POST ['action'] == "delete") {
			if (! $permissions ['delete']) {
				return array (
						'title' => 'Forbidden',
						'error' => '403',
						'id' => $id 
				);
			}
			
			Page_Model::delete ( $page ['page_id'] );
			
			/* Take the user to the (now deleted) site */
			$url = Core::constructURL ( 'page', 'view', array (
					$id 
			), 'html' );
			Core::redirect ( $url );
			return;
		}
		/* Preview type thing */
		$page ['page_rel_revision'] ['revision_text_parsed'] = Page_Model::render ( $page );
		return array (
				'title' => "Editing '" . $page ['page_rel_revision'] ['revision_title'] . "'",
				'id' => $id,
				'page' => $page,
				'preview' => true 
		);
	}
	public static function purge($id) {
		if (! $page = Page_Model::getByShort ( $id )) {
			return array (
					'title' => 'Not found',
					'error' => '404',
					'id' => $id 
			);
		}
		
		Revision_Model::cache_purge_page ( $page ['page_id'] );
		$url = Core::constructURL ( 'page', 'view', array (
				$page ['page_short'] 
		), 'html' );
		Core::redirect ( $url );
	}
}

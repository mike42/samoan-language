<?php
class example_controller {
	public static function init() {
		core::loadClass('example_model');
		core::loadClass('word_model');
	}

	public static function view($example_id) {
		if($example_id == '') {
			core::redirect(core::constructURL('example', 'search', array(''), 'html'));
			return array();
		} elseif($example = example_model::getById($example_id)) {
			return array('example' => $example);
		} else {
			return array('error' => '404');
		}
	}

	public static function search($word) {
		if(isset($_GET['s']) && $word == '') {
			$word = $_GET['s'];
		}
		$word = trim($word);
		$part = word_model::getSpellingAndNumberFromStr($word);
		$example_list = example_model::listByWordMention($part['spelling'], $part['number']);
		return array('search' => $word, 'examples' => $example_list);
	}

	public static function create() {
		$permissions = core::getPermissions('example');
		if(!$permissions['create']) {
			return array('error' => '403');
		}

		if(isset($_REQUEST['example_en']) && isset($_REQUEST['example_str'])) {
			$example_id = example_model::insert($_REQUEST['example_str'], $_REQUEST['example_en']);
			core::redirect(core::constructURL('example', 'edit', array($example_id), 'html'));
		} else {
			return array();
		}
	}

	public static function edit($example_id) {
		$permissions = core::getPermissions('example');
		if(!$permissions['edit']) {
			/* No edit permission */
			return array('error' => '403');
		}

		if(!$example = example_model::getById($example_id)) {
			/* No such example */
			return array('error' => '404');
		}

		if(!isset($_REQUEST['action'])) {
			/* No action (show edit form) */
			return array('example' => $example);
		}
		$action = $_REQUEST['action'];

		if(!isset($_POST['example_str']) && isset($_POST['example_en'])) {
			print_r($_POST);
			/* Got dodgy data */
			return array('error' => '404');
		}

		/* Update example if we have enough info */
		$example['example_en'] = $_POST['example_en'];
		$example['example_str'] = $_POST['example_str'];

		if($action == 'delete') {
			/* Delete the page */
			if(!$permissions['delete']) {
				return array('error' => '403');
			}

			example_model::delete($example['example_id']);
			$dest  = core::constructURL('example', 'view', array(''));
			core::redirect($dest);
			return;
		} else if($action == 'save') {
			/* Save the page */
			example_model::update($example);
			$dest = core::constructURL('example', 'view', array($example['example_id']), 'html');
			core::redirect($dest);
			return;
		}

		/* Default to preview */
		return array('example' => $example, 'preview' => true);
	}
}

?>

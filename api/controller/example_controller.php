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
		if($permissions['edit']) {
			if($example = example_model::getById($example_id)) {
				if(isset($_REQUEST['submit']) && isset($_POST['example_sm']) && isset($_POST['example_str'])) {
					/* Update example if we have enough info */
					$example['example_en'] = $_POST['example_en'];
					$example['example_str'] = $_POST['example_str'];
					example_model::update($example);	
					core::redirect(core::constructURL('example', 'view', array($example['example_id'])));
					return;
				}
				return array('example' => $example);
			} else {
				/* No such example */
				return array('error' => '403');
			}
		} else {
			/* No edit permission */
			return array('error' => '403');
		}
	}
}

?>

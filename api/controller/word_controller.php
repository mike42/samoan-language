<?php
class word_controller {
	public function init() {
		core::loadClass('word_model');
	}
	
	public function view($id) {
		if($id == '') {
			return array('title' => 'Samoan Language Vocabulary', 'view' => 'default');
		}
		
		if($id = word_model::getWordIDfromStr($id)) {
			if($word = word_model::getByID($id)) {
				$id = $word['rel_spelling']['spelling_t_style'].(($word['word_num'] != 0)? (int)$word['word_num'] : "");
				return array('title' => 'Samoan Language Vocabulary', 'word' => $word, 'id' => $id);
			}
		}
		return array('error' => '404');
	}
	
	public function edit($id) {
		$permissions = core::getPermissions('word');
		
		/* Check edit permissions */
		if($permissions['edit']) {
			/* Now go ahead and return view info */
			return self::view($id);
		}
		
		/* No permission */
		return array('error' => '403', 'id' => $id);
	}
	
	
	public function type($type_short) {
		if(!$type = listtype_model::getByShort($type_short)) {
			return array('error' => '404');
		}
		
		if($words = word_model::listByTypeShort($type_short)) {
			$title = $type['type_title'];
			return array('title' => $title, 'words' => $words, 'type' => $type);
		}
		return array('error' => '404');		return array('error' => '404');
	}
	
	public function letter($letter) {
		if($words = word_model::listByLetter($letter)) {
			$title = "Samoan Words: ". core::escapeHTML(strtoUpper($letter). " " . strtolower($letter));
			return array('title' => $title, 'words' => $words, 'letter' => $letter);
		}
		return array('error' => '404');
	}
	
	public function search($search) {
		if($search == '' && !isset($_REQUEST['s'])) {
			return array('redirect' => core::constructURL("page", "view", array("home"), "html"));
		}
		
		if($search == '') {
			$search = $_REQUEST['s'];
		}
		
		//if($id = word_model::getWordIDfromStr($search)) {
		//	/* An exact match exists for this word. Redirect to it */
		//	return array('redirect' => core::constructURL("word", "view", array($search), "html"));
		//}
		
		$searchKey = spelling_model::calcSearchkey($search);
		$words = word_model::getBySpellingSearchKey($searchKey);

		return array('search' => $search, 'words' => $words, 'title' => 'Search Vocabulary');
	}

}


?>
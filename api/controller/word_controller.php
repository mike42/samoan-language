<?php
class word_controller {
	public function init() {
		core::loadClass('word_model');
		core::loadClass('def_model');
	}
	
	/**
	 * Show a single word
	 * 
	 * @param string $id
	 */
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
	
	/**
	 * Edit a word
	 * 
	 * @param string $id
	 */
	public function edit($id, $secondary, $target) {
		$permissions = core::getPermissions('word');
		
		/* Check edit permissions */
		if(!$permissions['edit']) {
			/* No permission */
			return array('error' => '403', 'id' => $id);
		}
			
		$wordInfo = self::view($id);
		if(isset($wordInfo['error'])) {
			return $wordInfo; /* If that didn't work we need to pass on the error */
		}
		$editPage = core::constructURL("word", "edit", array($id), "html");
		
		/* Now go ahead and return view info */
		switch($secondary) {
			case 'delete';
				$wordInfo['form'] = "redirect";
				if(isset($_POST['confirm'])) {
					die("Deleting words not implemented");					
				}
				break;
			
			case 'redirect':
				$wordInfo['form'] = "redirect";
				if(isset($_POST['word_redirect_to'])) {
					// Todo
					die("Editing word origin unimplemented.");
				}
				break;
				
			case 'origin':
				$wordInfo['form'] = "origin";
				if(isset($_POST['word_spelling'])) {
					// Todo
					die("Editing word origin unimplemented.");
				}
				
				break;
			
			case 'move':
				$wordInfo['form'] = "move";
				if(isset($_POST['word_spelling'])) {
					// Todo
					die("Word move unimplemented.");
				}
				
				break;
				
			case 'def':
				if($target == "") {
					/* Add new def */
					// TODO set $def here
					die("Adding new def unimplemented");
				} elseif(!$def = def_model::get($wordInfo['word']['word_id'], $target)) {
					core::redirect($editPage);
				}
				
				$wordInfo['def'] = $def;
				$wordInfo['form'] = "def";
				break;
			
			case 'rel':
				$wordInfo['form'] = "rel";
				break;
				
			case '':
				/* Nothing special for standard case */
				break;
				
			default:
				/* Invalid edit URL, take back to main editing page */
				core::redirect($editPage);
		}

		return $wordInfo;
	}
	
	
	/**
	 * List all words containing a definition of a given type
	 * 	(eg all words which can be used as adjectives)
	 * 
	 * @param string $type_short
	 */
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
	
	/**
	 * List all words beginning with a certain letter
	 * 
	 * @param string $letter
	 */
	public function letter($letter) {
		if($words = word_model::listByLetter($letter)) {
			$title = "Samoan Words: ". core::escapeHTML(strtoUpper($letter). " " . strtolower($letter));
			return array('title' => $title, 'words' => $words, 'letter' => $letter);
		}
		return array('error' => '404');
	}
	
	/**
	 * Return words which match a given search pattern
	 * 
	 * @param unknown_type $search
	 */
	public function search($search) {
		if($search == '' && !isset($_REQUEST['s'])) {
			return array('redirect' => core::constructURL("page", "view", array("home"), "html"));
		}
		
		if($search == '') {
			$search = $_REQUEST['s'];
		}
		
		$searchKey = spelling_model::calcSearchkey($search);
		$words = word_model::getBySpellingSearchKey($searchKey);

		return array('search' => $search, 'words' => $words, 'title' => 'Search Vocabulary');
	}

}


?>
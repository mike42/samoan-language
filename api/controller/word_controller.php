<?php
class word_controller {
	public function init() {
		core::loadClass('word_model');
		core::loadClass('def_model');
		core::loadClass('spellingaudio_model');
	}

	/**
	 * Show a single word
	 *
	 * @param string $id
	 */
	public function view($idstr) {
		if($idstr == '') {
			$counts = array(
					'word' => word_model::countWords(),
					'example' => example_model::countExamples(),
					'audio' => spellingaudio_model::countAudio());
			return array(	'title'  => 'Samoan Language Vocabulary',
					'view'   => 'default',
					'counts' => $counts);
		}

		if($id = word_model::getWordIDfromStr($idstr)) {
			if($word = word_model::getByID($id)) {
				$id = $word['rel_spelling']['spelling_t_style'].(($word['word_num'] != 0)? (int)$word['word_num'] : "");
				return array('title' => 'Samoan Language Vocabulary', 'word' => $word, 'id' => $id);
			}
		}
		return array('error' => '404', 'word' => $idstr);
	}

	public function create($spelling_t_style = '') {
		$permissions = core::getPermissions('word');
		if(!$permissions['create']) {
			/* No permission */
			return array('error' => '403');
		}

		if(isset($_POST['spelling_t_style'])) {
			$spelling_t_style = $_POST['spelling_t_style'];
		}

		if(isset($_POST['confirm'])) {
			/* Ensure there are no numbers at the end of the name */
			$newSpelling = word_model::getSpellingAndNumberFromStr($_POST['spelling_t_style']);
			$spelling_t_style = trim($newSpelling['spelling']);
				
			if($spelling_t_style != '') {
				/* Make sure we have the spelling in the database */
				if(!$spelling = spelling_model::getBySpelling($spelling_t_style)) {
					$spelling = spelling_model::add($spelling_t_style);
				}
				$word_num = self::get_next_wordnum($spelling_t_style);

				/* Go ahead and create the word page */
				$word = word_model::add($spelling['spelling_id'], $word_num);
				core::redirect(core::constructURL("word", "edit", array(word_model::getIdStrBySpellingNum($spelling_t_style, $word_num)), "html"));
			}
		}

		return array('title' => "Create new word", 'spelling_t_style' => $spelling_t_style);
	}

	/**
	 * Edit a word
	 *
	 * @param string $id
	 */
	public function edit($id, $secondary = '', $target = '') {
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
			/* Check for delete permission */
			if(!$permissions['delete']) {
				return array('error' => "403");
			}

			$wordInfo['form'] = "delete";
			if(isset($_POST['confirm'])) {
				/* Actually delete the word */
				word_model::delete($wordInfo['word']['word_id']);
				core::redirect(core::constructURL("word", "", array(), "html"));
			}
			break;
				
			case 'redirect':
				/* Change a word into a redirect */
				$wordInfo['form'] = "redirect";
				if(isset($_POST['word_redirect_to'])) {
					/* Check that the target word exists */
					$redirect = $_POST['word_redirect_to'];
					if($redirect == '') {
						/* Clear redirect */
						$word_id = '0';
					} else if(!$word_id = word_model::getWordIDfromStr($_POST['word_redirect_to'])) {
						$wordInfo['message'] = "That word does not exist";
						return $wordInfo;
					}
					$wordInfo['word']['word_redirect_to'] = $word_id;

					/* Update and redirect */
					word_model::setRedirect($wordInfo['word']);
					core::redirect($editPage);
				}
				break;

			case 'origin':
				/* Edit word origin */
				$wordInfo['form'] = "origin";
				if(isset($_POST['word_origin_word']) && isset($_POST['lang_id'])) {
					$lang_id = $_POST['lang_id'];
					$origin_word = $_POST['word_origin_word'];
					if(!$lang = listlang_model::get($lang_id)) {
						$lang_id ='';
						$origin_word = '';
					}
					/* Set word origin */
					$wordInfo['word']['word_origin_lang'] = $lang_id;
					$wordInfo['word']['word_origin_word'] = $origin_word;
						
					/* Update and redirect */
					word_model::setOrigin($wordInfo['word']);
					core::redirect($editPage);
				}
				$wordInfo['listlang'] = listlang_model::listAll();

				break;
					
			case 'move':
				/* Change spelling of the word */
				$wordInfo['form'] = "move";
				if(isset($_POST['spelling_t_style'])) {
					$word_id = $wordInfo['word']['word_id'];
					$newSpelling = word_model::getSpellingAndNumberFromStr($_POST['spelling_t_style']);
					$spelling_t_style = trim($newSpelling['spelling']);
					if($spelling_t_style != '') {
						if($wordInfo['word']['rel_spelling']['spelling_t_style'] != $spelling_t_style) {
							/* Make sure we have the spelling in the database */
							if(!$spelling = spelling_model::getBySpelling($spelling_t_style)) {
								$spelling = spelling_model::add($spelling_t_style);
							}
							/* Get the next number and move */
							$word_num = self::get_next_wordnum($spelling_t_style);
							$spelling_id = $spelling['spelling_id'];
							word_model::move($word_id, $spelling_id, $word_num);
							/* Edit page has changed now */
							$id = word_model::getIdStrBySpellingNum($spelling_t_style, $word_num);
							$editPage = core::constructURL("word", "edit", array($id), "html");
						}
						core::redirect($editPage);
					}
				}
				break;

			case 'def':
				/* Find definition or add a blank one as requested */
				$word_id = $wordInfo['word']['word_id'];
				$def_id = $target;
				if($def_id == "") {
					/* Add new def */
					$def_id = def_model::add($word_id);
					/* Navigate to new def */
					$defEdit = core::constructURL("word", "edit", array($id, "def", (int)$def_id), "html");
					core::redirect($defEdit);
				}

				$defEdit = core::constructURL("word", "edit", array($id, "def", (int)$def_id), "html");
				if(!$def = def_model::get($word_id, $target)) {
					/* Def not found. Return to edit page */
					core::redirect($editPage);
				} elseif(isset($_POST['def_en']) && isset($_POST['type_id']) && isset($_POST['action'])) {
					if($_POST['action'] == 'delete') {
						def_model::delete($def['def_id']);
					} else {
						/* Check everything */
						$def['def_en'] = $_POST['def_en'];
						$def['def_type'] = $_POST['type_id'];
						if(!$type = listtype_model::get($def['def_type'])) {
							core::redirect($defEdit);
						}
						def_model::update($def);
					}
					/* Navigate back to edit page*/
					core::redirect($editPage);
				}

				$wordInfo['def'] = $def;
				$wordInfo['listtype'] = listtype_model::listAll();
				$wordInfo['form'] = "def";
				break;
			case 'example':
				$word_id = $wordInfo['word']['word_id'];
				$def_id = $target;
				if(!$def = def_model::get($word_id, $target)) {
					/* Def not found. Return to edit page */
					core::redirect($editPage);
				}

				if(isset($_POST['action']) && isset($_POST['example_id'])) {
					$example_id = (int)$_POST['example_id'];
					$action = $_POST['action'];

					if($action == 'delete') {
						examplerel_model :: delete($example_id, $word_id, $def_id);
					} else if($action == 'add') {
						examplerel_model :: add($example_id, $word_id, $def_id);
					}

					/* Re-fetch modified definition */
					if(!$def = def_model::get($word_id, $target)) {
						core::redirect($editPage);
					}
					if(!$wordInfo['word'] = word_model::getByID($word_id)) {
						core::redirect($editPage);
					}
				}

				$wordInfo['def'] = $def;
				$wordInfo['candidates'] = example_model::listByWordMention($wordInfo['word']['rel_spelling']['spelling_t_style'], $wordInfo['word']['word_num']);
				$wordInfo['form'] = "example";
				break;
			case 'rel':
				$wordInfo['form'] = "rel";
				$fail = false;

				if(isset($_POST['addRel'])  && isset($_POST['rel_type_id']) && isset($_POST['word']) ) {
					$word_str = $_POST['word'];
					$rel_type_id = $_POST['rel_type_id'];

					if(!$word_id = word_model::getWordIDfromStr($word_str)) {
						/* Verify word exists */
						$wordInfo['message'] = "Word does not exist";
						$fail = true;
					}

					if(!$fail && !word_model::reltypeExists($rel_type_id)) {
						/* Verify rel_type_id exists */
						$wordInfo['message'] = "Please select a type first.";
						$fail = true;
					}
						
					if(!$fail && word_model::isRelated($wordInfo['word']['word_id'], $rel_type_id, $word_id)) {
						/* Verify words aren't already related */
						$wordInfo['message'] = "Already listed.";
						$fail = true;
					}
						
					if(!$fail && (int)$wordInfo['word']['word_id'] == $word_id) {
						/* Just because this would be silly */
						$wordInfo['message'] = "Cannot relate word to itself.";
						$fail = true;
					}
						
					if(!$fail) {
						/* Ok. relate the words now */
						word_model::relateWords($wordInfo['word']['word_id'], $rel_type_id, $word_id);
						$url = core::constructURL("word", "edit", array($wordInfo['id'], 'rel', $rel_type_id), "html");
						core::redirect($url);
					}

				} else if(isset($_POST['delRel']) && isset($_POST['rel_type_id'])  && isset($_POST['word_id'])) {
					$word_id = $_POST['word_id'];
					$rel_type_id = $_POST['rel_type_id'];
						
					if(!$word = word_model::getByID($word_id)) {
						/* Check word exists */
						$wordInfo['message'] = "Delete failed (word not found)";
						$fail = true;
					}
						
					if(!$fail && !word_model::reltypeExists($rel_type_id)) {
						/* Verify rel_type_id exists */
						$wordInfo['message'] = "There was a problem deleting (reltype not found), please try again.";
						$fail = true;
					}

					if(!$fail && !word_model::isRelated($wordInfo['word']['word_id'], $rel_type_id, $word_id)) {
						/* Verify words aren't already related */
						$wordInfo['message'] = "Delete failed (word not related).";
						$fail = true;
					}

					if(!$fail) {
						word_model::unRelateWords($wordInfo['word']['word_id'], $rel_type_id, $word_id);
						$url = core::constructURL("word", "edit", array($wordInfo['id'], 'rel', $rel_type_id), "html");
						core::redirect($url);
					}
				}

				$wordInfo['rel_type_id'] = $target;
				$wordInfo['listreltype'] = word_model::listRelType();

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
		return array('error' => '404');
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
		if($search == 'suggest') {
			return self::suggest();
		}

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

	/**
	 * Search for a word using the beginning only. (should be invoked using search/suggest.json)
	 */
	private function suggest() {
		if(!isset($_POST['term'])) {
			return array('redirect' => core::constructURL("page", "view", array("home"), "html"));
		}

		$search = $_POST['term'];
		$searchKey = spelling_model::calcSearchkey($search);
		$words = word_model::getBySpellingSearchKey($searchKey, true);
		return array('search' => $search, 'words' => $words, 'title' => 'Search Vocabulary');
	}

	/**
	 * Handle re-numberings of words as cleanly as possible
	 *
	 * @param string $spelling_t_style
	 * @return the next vacant number on this spelling, after moving around things as necessary
	 */
	private function get_next_wordnum($spelling_t_style) {
		$word_num = 0;

		/* If there is already a word 'foo0', change it to 'foo1' and make this 'foo2' */
		if($word = word_model::getWordBySpellingAndWordNum($spelling_t_style, 0)) {
			word_model::renumber($word['word_id'], 1);
			$word_num = 2;
		} else if($word = word_model::getWordBySpellingAndWordNum($spelling_t_style, 1)) {
			/* If there is already a foo1, then don't try to make foo0! */
			$word_num = 2;
		}

		/* Now search for the next spare location */
		while($word = word_model::getWordBySpellingAndWordNum($spelling_t_style, $word_num)) {
			$word_num++;
		}

		return $word_num;
	}
}


?>

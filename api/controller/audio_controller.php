<?php
class audio_controller {
	static $audioDir;


	function init() {
		core::loadClass("spellingaudio_model");
		core::loadClass("spelling_model");
	}

	static function view($type = false, $id = false) {
		switch($type) {
			case 'spelling':
				if(!$spelling = spelling_model::getBySpelling($id)) {
					/* Spelling does not actually exist! good luck */
					return array("error" => "404");
				}

				if($spellingaudio = spellingaudio_model::getRowBySpellingTStyle($id, 0)) {
					return array('spelling' => $spelling, 'spellingaudio' => $spellingaudio);
				}

				return array('spelling' => $spelling);
		}

		return array("error" => "404");
	}

	/**
	 * Dish out an audio file to the user if one can be found
	 *
	 * @param string $type
	 * @param string $id
	 */
	static function listen($type = false, $id = false) {
		switch($type) {
			case 'spelling':
				if($spellingaudio = spellingaudio_model::getRowBySpellingTStyle($id, 0)) {
					/* Just look for recording */
					return array("fn" => $spellingaudio['spelling_id'], "type" => "spelling");
				}
				break;

			case 'spelling-k':
				if($spellingaudio = spellingaudio_model::getRowBySpellingTStyle($id, 1)) {
					return array("fn" => $spellingaudio['spelling_id'], "type" => "spelling-k");
				}

				if(!$spelling = spelling_model::getBySpelling($id)) {
					/* Spelling does not actually exist! good luck */
					return array("error" => "404");
				}

				if($spelling['spelling_t_style'] != $spelling['spelling_k_style']) {
					/* T- and K styles are not the same */
					return array("error" => "404");
				}


				if($spellingaudio = spellingaudio_model::getRowBySpellingTStyle($id, 0)) {
					/* Attempt to fall back on similar T-style recording if no K-style one was found */
					return array("fn" => $spellingaudio['spelling_id'], "type" => "spelling");
				}
				break;


			case 'example':
				// TODO
				break;

			case 'example-k':
				// TODO
				break;

		}

		return array("error" => "404");
	}
}

?>

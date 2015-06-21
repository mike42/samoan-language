<?php

namespace SmWeb;

class Audio_Controller implements Controller {
	static $audioDir;
	
	private $spelling;
	private $spellingAudio;
	private $database;
	
	public static function init() {
		Core::loadClass ( "SpellingAudio_Model" );
		Core::loadClass ( "Spelling_Model" );
	}

	public function __construct(Database $database) {
		$this -> database = $database;
		$this -> spelling = Spelling_Model::getInstance( $database );
		$this -> spellingAudio = SpellingAudio_Model::getInstance ( $database );
	}

	/**
	 * @param string $type Type of audio to view.
	 * @param string $id ID of audio to view.
	 * @return mixed Values for the corresponding view
	 */
	public function view($type = false, $id = false) {
		switch ($type) {
			case 'spelling' :
				if (! $spelling = $this -> spelling -> getBySpelling ( $id )) {
					/* Spelling does not actually exist! good luck */
					return array (
							"error" => "404" 
					);
				}
				
				if ($spellingaudio = $this -> spellingAudio -> getRowBySpellingTStyle ( $id, 0 )) {
					return array (
							'spelling' => $spelling,
							'spellingaudio' => $spellingaudio 
					);
				}
				return array (
						'spelling' => $spelling 
				);
		}
		return array (
				"error" => "404" 
		);
	}

	/**
	 * Dish out an audio file to the user if one can be found
	 *
	 * @param string $type type of audio file (example, spelling of word, etc)
	 * @param string $id key for finding the file
	 */
	public function listen($type = false, $id = false) {
		switch ($type) {
			case 'spelling' :
				if ($spellingaudio = $this -> spellingAudio -> getRowBySpellingTStyle ( $id, 0 )) {
					/* Just look for recording */
					return array (
							"fn" => $spellingaudio ['spelling_id'],
							"type" => "spelling" 
					);
				}
				break;
			
			case 'spelling-k' :
				if ($spellingaudio = $this -> spellingAudio -> getRowBySpellingTStyle ( $id, 1 )) {
					return array (
							"fn" => $spellingaudio ['spelling_id'],
							"type" => "spelling-k" 
					);
				}
				
				if (! $spelling = $this -> spelling -> getBySpelling ( $id )) {
					/* Spelling does not actually exist! good luck */
					return array (
							"error" => "404" 
					);
				}
				
				if ($spelling ['spelling_t_style'] != $spelling ['spelling_k_style']) {
					/* T- and K styles are not the same */
					return array (
							"error" => "404" 
					);
				}
				
				if ($spellingaudio = $this -> spellingAudio -> getRowBySpellingTStyle ( $id, 0 )) {
					/* Attempt to fall back on similar T-style recording if no K-style one was found */
					return array (
							"fn" => $spellingaudio ['spelling_id'],
							"type" => "spelling" 
					);
				}
				break;
			
			case 'example' :
				// TODO
				break;
			
			case 'example-k' :
				// TODO
				break;
		}
		return array (
				"error" => "404" 
		);
	}

	/**
	 * @param Spelling_Model $spelling
	 */
	public function setSpelling(Spelling_Model $spelling) {
		$this -> spelling = $spelling;
	}

	/**
	 * @param SpellingAudio_Model $spellingAudio
	 */
	public function setSpellingAudio(SpellingAudio_Model $spellingAudio) {
		$this -> spellingAudio = $spellingAudio;
	}
}

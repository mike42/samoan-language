<?php

namespace SmWeb;

class Example_Controller implements Controller {
	private $database;
	private $example;
	private $word;
	
	public function __construct(database $database) {
		$this -> database =  $database;
		$this -> example = Example_Model::getInstance($database);
		$this -> word = Word_Model::getInstance($database);
	}
	
	public static function init() {
		Core::loadClass ( 'Example_Model' );
		Core::loadClass ( 'Word_Model' );
	}
	public function view($example_id) {
		if ($example_id == '') {
			Core::redirect ( Core::constructURL ( 'example', 'search', array (
					'' 
			), 'html' ) );
			return array ();
		} elseif ($example = $this -> example -> getById ( $example_id )) {
			return array (
					'example' => $example 
			);
		} else {
			return array (
					'error' => '404' 
			);
		}
	}
	public function search($word) {
		if (isset ( $_GET ['s'] ) && $word == '') {
			$word = $_GET ['s'];
		}
		$word = trim ( $word );
		$part = $this -> word -> getSpellingAndNumberFromStr ( $word );
		$example_list = $this -> example -> listByWordMention ( $part ['spelling'], $part ['number'] );
		return array (
				'search' => $word,
				'examples' => $example_list 
		);
	}
	public function create() {
		$permissions = Core::getPermissions ( 'example' );
		if (! $permissions ['create']) {
			return array (
					'error' => '403' 
			);
		}
		
		if (isset ( $_REQUEST ['example_en'] ) && isset ( $_REQUEST ['example_str'] )) {
			$example_id = $this -> example -> insert ( $_REQUEST ['example_str'], $_REQUEST ['example_en'] );
			Core::redirect ( Core::constructURL ( 'example', 'edit', array (
					$example_id 
			), 'html' ) );
		} else {
			return array ();
		}
	}
	public function edit($example_id) {
		$permissions = Core::getPermissions ( 'example' );
		if (! $permissions ['edit']) {
			/* No edit permission */
			return array (
					'error' => '403' 
			);
		}
		
		if (! $example = $this -> example -> getById ( $example_id )) {
			/* No such example */
			return array (
					'error' => '404' 
			);
		}
		
		if (! isset ( $_REQUEST ['action'] )) {
			/* No action (show edit form) */
			return array (
					'example' => $example 
			);
		}
		$action = $_REQUEST ['action'];
		
		if (! isset ( $_POST ['example_str'] ) && isset ( $_POST ['example_en'] )) {
			print_r ( $_POST );
			/* Got dodgy data */
			return array (
					'error' => '404' 
			);
		}
		
		/* Update example if we have enough info */
		$example ['example_en'] = $_POST ['example_en'];
		$example ['example_str'] = $_POST ['example_str'];
		
		if ($action == 'delete') {
			/* Delete the page */
			if (! $permissions ['delete']) {
				return array (
						'error' => '403' 
				);
			}
			
			$this -> example -> delete ( $example ['example_id'] );
			$dest = Core::constructURL ( 'example', 'view', array (
					'' 
			) );
			Core::redirect ( $dest );
			return;
		} else if ($action == 'save') {
			/* Save the page */
			$this -> example -> update ( $example );
			$dest = Core::constructURL ( 'example', 'view', array (
					$example ['example_id'] 
			), 'html' );
			Core::redirect ( $dest );
			return;
		}
		
		/* Default to preview */
		return array (
				'example' => $example,
				'preview' => true 
		);
	}
}

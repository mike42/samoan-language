<?php
$config['core']['webroot']					= 'https://test.samoan.ws/';
$config['core']['default']['controller']	= 'page';
$config['core']['default']['action']		= 'view';
$config['core']['default']['arg']			= array('home');
$config['core']['default']['format']		= 'html';
//$config['core']['footer']					= dirname(__FILE__) . "/foot.inc";

/* Database */
$config['database']['user'] = 'samoan_language';
$config['database']['password'] = 'test_password';
$config['database']['host'] = 'mysql';
$config['database']['name'] = 'samoan_language';
$config['database']['prefix'] = 'sm_';

$config['audio']['extern'] = 'https://test.samoan.ws/sm/data/audio/';
$config['parser']['imgextern'] = 'https://test.samoan.ws/sm/data/images/';

/* Permissions */
$config['session'] = array(
    'anon' => array(
        'page' => array(
            'view' => true,
            'edit' => false,
            'create' => false,
            'delete' => false),
        'word' => array(
            'view' => true,
            'edit' => false,
            'create' => false,
            'delete' => false),
        'example' => array(
            'view' => true,
            'edit' => false,
            'create' => false,
            'delete' => false),
        'audio' => array(
            'view' => true,
            'edit' => false,
            'create' => false,
            'delete' => false)),
    'user' => array(
        'page' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => false),
        'word' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => false),
        'example' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => true),
        'audio' => array(
            'view' => true,
            'edit' => false,
            'create' => false,
            'delete' => false)),
    'admin' => array(
        'page' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => true),
        'word' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => true),
        'example' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => false),
        'audio' => array(
            'view' => true,
            'edit' => true,
            'create' => true,
            'delete' => true)));
?>
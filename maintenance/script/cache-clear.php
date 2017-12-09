#!/usr/bin/env php
<?php
/* Use this script to purge the cache */
require_once(dirname(__FILE__) . "/../../api/core.php");
set_error_handler('core::exceptions_error_handler');

core::loadClass("revision_model");
core::loadClass("letter_model");
revision_model::cache_purge_all();
letter_model::cache_purge_all();
echo "The cache is now clear.\n";

?>

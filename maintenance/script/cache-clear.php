#!/usr/bin/php
<?php
/* Use this script to purge the cache */
require_once(dirname(__FILE__) . "/../../api/core.php");
core::loadClass("revision_model");
revision_model::cache_purge_all();
echo "The cache is now clear.\n";

?>

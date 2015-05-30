#!/usr/bin/env php
<?php

/* Use this script to purge the cache */
namespace SmWeb;

require_once (dirname ( __FILE__ ) . "/../../lib/Core.php");
Core::loadClass ( "Revision_Model" );
Core::loadClass ( "Letter_Model" );
Revision_Model::cache_purge_all ();
Letter_Model::cache_purge_all ();
echo "The cache is now clear.\n";

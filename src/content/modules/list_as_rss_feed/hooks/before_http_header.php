<?php
if (isset ( $_GET ["format"] ) and $_GET ["format"] == "rss") {
	echo Template::executeModuleTemplate ( "list_as_rss_feed", "feed" );
	die ();
}
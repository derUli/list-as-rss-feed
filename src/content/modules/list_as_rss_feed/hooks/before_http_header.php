<?php
if (isset ( $_GET ["format"] ) and $_GET ["format"] == "rss") {
	ob_get_clean();
	require getModulePath("list_as_rss_feed")."templates/feed.php";
	exit();
}
<?php
$id = get_ID ();
if (get_type () == "list" and $id !== null) {
	$list = new List_Data ( $id );
	if ($list->content_id !== null) {
		$entries = $list->filter ();
		header ( "HTTP/1.0 200 OK" );
		header ( "Content-Type: text/xml; charset=UTF-8" );
		
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo "\n";
		echo '<rss version="2.0">';
		echo "\n";
		
		echo "<channel>";
		echo "<title>" . Template::getEscape ( getconfig ( "homepage_title" ) ) . "</title>\n";
		echo "<link>" . rootDirectory () . "</link>\n";
		echo "<description>" . Template::getEscape ( Settings::get ( "motto" ) ) . "</description>\n";
		
		if (! getconfig ( "hide_meta_generator" )) {
			$generator = "UliCMS " . cms_version ();
			echo "<generator>" . $generator . "</generator>\n";
		}
		
		echo "<pubDate>" . date ( "r" ) . "</pubDate>\n";
		
		foreach ( $entries as $row ) {
			
			$servername = $_SERVER ["SERVER_NAME"];
			
			if (is_ssl ()) {
				$url = "https://";
			} else {
				$url = "http://";
			}
			
			$url .= $servername;
			$meta = get_article_meta ( $row->systemname );
			
			$page = get_page ( $row->systemname );
			
			if ($meta and StringHelper::isNotNullOrEmpty ( $meta->excerpt )) {
				$description = $meta->excerpt;
			} else {
				$content = $page ["content"];
			}
			
			// Replace Relative URLs
			$description = str_replace ( "<a href=\"/", "<a href=\"$url/", $description );
			
			$description = str_replace ( "<a href='/", "<a href='$url/", $description );
			
			$description = str_replace ( " src=\"/", " src=\"$url/", $description );
			
			$description = str_replace ( " href=\"?seite=", " href=\"$url/?seite=", $description );
			
			echo "<item>\n";
			echo "<title>" . htmlspecialchars ( $row->title ) . "</title>\n";
			$link = rootDirectory () . buildSEOUrl ( $row->systemname );
			
			echo "<link>" . Template::getEscape ( $link ) . "</link>\n";
			echo "<description>" . htmlspecialchars ( $description ) . "</description>\n";
			echo "<pubDate>" . date ( "r", $page ["lastmodified"] ) . "</pubDate>\n";
			echo "<guid isPermaLink=\"false\">" . Template::getEscape ( $row->seo_shortname ) . "</guid>\n";
			echo "</item>\n";
		}
		
		echo "</channel>\n";
		echo "</rss>";
	} else {
		header ( "HTTP/1.0 404 Not Found" );
		translate ( "list_not_found" );
		exit ();
	}
} else {
	header ( "HTTP/1.0 404 Not Found" );
	translate ( "list_not_found" );
	exit ();
}

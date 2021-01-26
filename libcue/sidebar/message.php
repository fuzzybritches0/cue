<?php
$new = libcue_message_count('new');
$read = libcue_message_count('read');
$sent = libcue_message_count('sent');
$archived = libcue_message_count('archived');
$sidebar_sections[] =	libcue_html_link("Neue Nachricht verfassen", $REMOTE_HTTP_PATH . "?command=message&option=new_message") .
			libcue_html_headline("Ordner") .
			libcue_html_paragraph(libcue_html_link("Neue Nachrichten(" . $new . ")", $REMOTE_HTTP_PATH . "?command=message&list=new")) .
			libcue_html_paragraph(libcue_html_link("Gelesene Nachrichten(" . $read . ")", $REMOTE_HTTP_PATH . "?command=message&list=read")) . 
			libcue_html_paragraph(libcue_html_link("Gesendete Nachrichten(" . $sent . ")", $REMOTE_HTTP_PATH . "?command=message&list=sent")) . 
			libcue_html_paragraph(libcue_html_link("Archivierte Nachrichten(" . $archived . ")", $REMOTE_HTTP_PATH . "?command=message&list=archived"));
?>

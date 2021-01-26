<?php
if ( $_REQUEST['option'] === "read" )
	{
	if ( $_REQUEST['list'] === "new" || $_REQUEST['list'] === "read" || $_REQUEST['list'] === "sent" || $_REQUEST['list'] === "archived" )
		{
		if ( libcue_directory_exists("message/" . $_SESSION['user_id'] . "/" . $_REQUEST['list'], $_REQUEST['message']) )
			{
			$message = libcue_fsdb_load_variables("message/" . $_SESSION['user_id'] . "/" . $_REQUEST['list'] . "/" .
				$_REQUEST['message']);
			foreach ( explode(",", $message['recipients']) as $recipient )
				{
				$recipients[] = libcue_fsdb_load_variable( "user/" . $recipient, "user_name");
				}
			if ( $_REQUEST['list'] === "new" )
				{
				libcue_fsdb_save_variables("message/" . $_SESSION['user_id'] . "/read/" . $_REQUEST['message'], $message);
				libcue_fsdb_delete("message/" . $_SESSION['user_id'] . "/new/" . $_REQUEST['message']);
				}
			$message['message_text'] = str_replace("\n", "</br>", $message['message_text']);
			$recipients_ = implode(",", $recipients);
			$posts[] = libcue_html_headline("Betreff: " . $message['subject']) . libcue_html_paragraph("Sender: " .
			libcue_fsdb_load_variable("user/" . $message['sender_id'], "user_name")) . libcue_html_paragraph("Empfänger: " . $recipients_) .
			libcue_html_paragraph($message['message_text']);
			if ( isset($recipients[1]) )
				{
				$variables = array( "message_text", "answer_all" );
				}
			else
				{
				$variables = array( "message_text" );
				}
			$options['label'] = "Antworten";
			$options['action'] = $REMOTE_HTTP_PATH . "?command=message&option=answer&message=" . $_REQUEST['message'] . "&list=" . $_REQUEST['list'];
			$var_mode['message_text'] = "textarea"; $var_mode['answer_all'] = "checkbox";
			$var_desc['message_text'] = "Nachricht"; $var_desc['answer_all'] = "Allen antworten";
			$posts[] = libcue_html_headline("Antworten") . libcue_draw_form($options, $variables, $var_desc, $var_mode, $maxlength);
			}
		}
	}
if ( $_REQUEST['option'] === "send" )
	{
	$recipients = libcue_session_parse_users_groups_roles($_REQUEST['recipients']);
	if ( isset($recipients) ) { $variables['recipients'] = implode(",",$recipients); }
	if ( isset($recipients[0]) )
		{
		if ( strlen($_REQUEST['message_text']) < 1 )
			{
			$variables['message_text'] = "Keine Nachricht";
			}
		else
			{
			$variables['message_text'] = $_REQUEST['message_text'];
			}
		if ( strlen($_REQUEST['subject']) < 1 )
			{
			$variables['subject'] = "Kein Betreff";
			}
		else
			{
			$variables['subject'] = $_REQUEST['subject'];
			}
		$variables['sender_id'] = $_SESSION['user_id'];
		$variables['sender'] = $_SESSION['user_name'];
		$variables['time'] = date("d.m.o, H:i", time());
		foreach ( $recipients as $recipient )
			{
			libcue_fsdb_save_variables("message/" . $recipient . "/new/" . time() . $variables['sender_id'], $variables);
			}
		libcue_fsdb_save_variables("message/" . $variables['sender_id'] . "/sent/" . time() . $variables['sender_id'], $variables);
		}
	}
$list = "new";
if ( $_REQUEST['list'] === "read" || $_REQUEST['list'] === "sent" || $_REQUEST['list'] === "archived" )
	{
	$list = $_REQUEST['list'];
	}
if ( $_REQUEST['option'] === "new_message" || $_REQUEST['option'] === "forward" )
	{
	if ( $_REQUEST['option'] === "forward" && libcue_directory_exists("message/" . $_SESSION['user_id'] . "/" . $list, $_REQUEST['message']) )
		{
		$forward_message = libcue_fsdb_load_variables("message/" . $_SESSION['user_id'] . "/" . $list . "/" . $_REQUEST['message']);
		$options['var_content']['message_text'] = "- - - WEITERGELEITETE NACHRICHT - - -\nUrsprung: " .
			libcue_fsdb_load_variable("user/" . $forward_message['sender_id'], "user_name") . ", " .
			$forward_message['time'] . "\n\n" . $forward_message['message_text'] . "\n- - - ENDE WEITERGELEITETE NACHRICHT - - -";
		$options['var_content']['subject'] = $forward_message['subject'];
		}
	$variables = array( "recipients", "subject", "message_text" );
	$options['label'] = "Senden";
	$options['action'] = $REMOTE_HTTP_PATH . "?command=message&option=send";
	$var_mode['recipients'] = "text"; $var_mode['subject'] = "text";
	$var_mode['message_text'] = "textarea";
	$var_desc['recipients'] = "Empfänger"; $var_desc['subject'] = "Betreff";
	$var_desc['message_text'] = "Nachricht";
	$max_length['recipients'] = "32"; $max_length['subject'] = "32";
	$posts[] = libcue_html_headline("Neue Nachricht verfassen") . libcue_draw_form($options, $variables, $var_desc, $var_mode, $maxlength);
	}
if ( $_REQUEST['option'] === "move" && $_REQUEST['to'] === "archived" && libcue_directory_exists("message/" . $_SESSION['user_id'] . "/" . $list, $_REQUEST['message']) &&
	$list !== "archived" )
	{
	$message = libcue_fsdb_load_variables("message/" . $_SESSION['user_id'] . "/" . $list . "/" . $_REQUEST['message']);
	libcue_fsdb_save_variables("message/" . $_SESSION['user_id'] . "/archived/" . $_REQUEST['message'], $message);
	libcue_fsdb_delete("message/" . $_SESSION['user_id'] . "/" . $list . "/" . $_REQUEST['message']);
	}
if ( $_REQUEST['option'] === "delete" && $list === "archived" && libcue_directory_exists("message/" . $_SESSION['user_id'] . "/archived" , $_REQUEST['message']) )
	{
	libcue_fsdb_delete("message/" . $_SESSION['user_id'] . "/archived/" . $_REQUEST['message']);
	}
if ( $_REQUEST['option'] === "answer" )
	{
	if ( $list === "new" )
		{
		$list_from = "read";
		}
	else
		{
		$list_from = $list;
		}
	if ( libcue_directory_exists("message/" . $_SESSION['user_id'] . "/" . $list_from, $_REQUEST['message']) )
		{
		if ( strlen($_REQUEST['message_text']) < 1 )
			{
			$variables['message_text'] = "Keine Nachricht";
			}
		else
			{
			$variables['message_text'] = $_REQUEST['message_text'];
			}
		$answer_message = libcue_fsdb_load_variables("message/" . $_SESSION['user_id'] . "/" . $list_from . "/" . $_REQUEST['message']);
		if ( $_REQUEST['answer_all'] === "checked" )
			{
			$variables['recipients'] = $answer_message['recipients'] . "," . $answer_message['sender_id'];
			}
		else
			{
			$variables['recipients'] = $answer_message['sender_id'];
			}
		$variables['message_text'] = $variables['message_text'] . "\n- - - - - - -\n" .$answer_message['sender'] . " hat geschrieben, " .
			$answer_message['time'] . ":\n" . $answer_message['message_text'] . "\n - - - - - - -\n";
		$variables['sender'] = libcue_fsdb_load_variable("user/" . $_SESSION['user_id'], "user_name");
		$variables['sender_id'] = $_SESSION['user_id'];
		$variables['subject'] = $answer_message['subject'];
		$variables['time'] = date("d.m.o, H:i", time());
		foreach ( explode(",",$variables['recipients']) as $recipient )
			{
			libcue_fsdb_save_variables("message/" . $recipient . "/new/" . time() . $variables['sender_id'], $variables);
			}
		libcue_fsdb_save_variables("message/" . $variables['sender_id'] . "/sent/" . time() . $variables['sender_id'], $variables);
		}
	}	
$options_n['search'] = $search;
$options_n['page'] = $page;
$options_n['directory'] = "message/" . $_SESSION['user_id'] . "/" . $list;
$options_n['show_file_name'] = FALSE;
$variables_n = array( "subject", "sender", "time");
$var_search = array( "message_text", "subject", "sender", "time" );
$cell_desc = array( "Betreff", "Sender", "Zeit", "Lesen", "Archivieren", "Weiterleiten");
$action[] = "?command=message&option=read&list=" . $list . "&message=";
$action[] = "?command=message&option=move&list=" . $list . "&to=archived&message=";
$action[] = "?command=message&option=forward&list=" . $list . "&message=";
if ( $list === "archived" )
	{
	$cell_desc = array( "Betreff", "Sender", "Zeit", "Lesen", "Archivieren", "Weiterleiten", "Löschen");
	$action[] = "?command=message&option=delete&list=archived&message=";
	}
$options['page_action'] = "?command=message";
$options_n['char_index'] = FALSE;
$files = libcue_array_basename(glob($GLOBALS['DATABASE_PATH'] . "message/" . $_SESSION['user_id'] . "/" . $list . "/*"));
if ( is_array($files) )
	{
	$files = array_reverse($files);
	}
$description['new'] = "Neue Nachrichten"; $description['read'] = "Gelesene Nachrichten"; $description['sent'] = "Gesendete Nachrichten";
$description['archived'] = "Archivierte Nachrichten";
$posts[] = libcue_html_headline($description[$list]) . libcue_table_list($files ,$options_n, $variables_n, $cell_desc, $action, $var_search);
$current_page = "message";
require $LIBCUE_PATH . "sidebar/message.php";
?>

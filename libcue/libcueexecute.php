<?php
if ( isset($_SESSION['user_id']) || $_REQUEST['command'] === "login" || $GLOBALS['COMMAND'] === "blank" || $GLOBALS['COMMAND'] === "denied" ||
	$_REQUEST['command'] === "register" || $_REQUEST['command'] === "password_reset" || $_REQUEST['command'] === "contact_admin")
	{
	$posts[0] = "";
	if ( isset($GLOBALS['COMMAND']) )
		{
		$command = $GLOBALS['COMMAND'];
		}
	else
		{
		$command = $_REQUEST['command'];
		}
	if ( file_exists($LIBCUE_PATH  . "command/" . basename($command, ".php") . ".php") )
		{
		$command = $LIBCUE_PATH  . "command/" . basename($command, ".php") . ".php";
		}
	elseif ( strlen($_REQUEST['command']) < 1 )
		{
		$command = $LIBCUE_PATH  . "command/today.php";
		}
	if ( file_exists($command) )
		{
		require $command;
		if ( isset($GLOBALS['report_messages']) )
			{
			$posts[0] = libcue_error_report();
			}
		}
	else
		{
		$current_page = "notfound";
		$posts[] = libcue_html_add_post( "404", libcue_html_paragraph("Inhalt konnte nicht gefunden werden!"));
		}
	}
?>

<?php
if ( isset($_SESSION['user_name']) )
	{
	require $GLOBALS['LIBCUE_PATH'] . "command/today.php";
	}
if (! isset($_SESSION['user_name']) && isset($_REQUEST['user_name']) && isset($_REQUEST['password']) && isset($_COOKIE['browser_id']) )
	{
	if ( auth_user($_REQUEST['user_name'], $_REQUEST['password']) === TRUE )
		{
		initalize_user_settings($_REQUEST['user_name']);
		create_session_ID();
		require $GLOBALS['LIBCUE_PATH'] . "command/today.php";
		}
	else
		{
		libcue_session_increase_fail_count_('ip');
		if ( libcue_directory_exists("user", $_REQUEST['user_name']) ) { libcue_session_increase_fail_count_('user_name'); }
		$posts[] = libcue_html_headline( "Fehler" ) . "<b><font color='red'>Benutzername oder Passwort falsch!</font></b>";
		}
	}
?>

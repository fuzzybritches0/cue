<?php
$current_page = "schedule";
$sidebar_page_action = "?command=schedule";
require $LIBCUE_PATH . "sidebar/schedule.php";
if ( ! libcue_directory_exists("user", $_REQUEST['user']) )
	{
	libcue_error_message("Der Benutzer: " . $_REQUEST['user'] . " ist unbekannt!");
	}
elseif ( libcue_user_is_active($_REQUEST['user']) )
	{
	$GLOBALS['page_title'] = "WT " . $_REQUEST['user'];
	$user_id = libcue_session_return_user_id($_REQUEST['user']);
	}
if ( $_REQUEST['nameg'] === $_REQUEST['name'] && libcue_directory_exists( "week_roster/" .
	$user_id, $_REQUEST['name']) && libcue_mobe_weekroster_is_valid_and_sound($_REQUEST['name']) )
	{
	for ( $weekday=1; $weekday<=7; $weekday++ )
		{
		for ( $count=1; $count<=4; $count++ )
			{
			$variables[$weekday . "_time_" . $count . "_" . $_REQUEST['name']] = $_REQUEST[$weekday . "_time_" .$count .
			 "_" . $_REQUEST['name']];
			}
		}
	$variables['name'] = $_REQUEST['name'];
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		libcue_fsdb_save_variables("week_roster/" . $user_id . "/" . $_REQUEST['name'], $variables);
		}
	else
		{
		libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
		}
	}
if ( ! isset($_REQUEST['nameg']) && strlen($_REQUEST['name']) === 0 && libcue_mobe_weekroster_is_valid_and_sound("", TRUE) )
	{
	$count_wr = 0;
	$roster = "Wochentafel_0";
	while ( libcue_directory_exists("week_roster/" . $user_id, "Wochentafel_" . $count_wr) )
		{
		$count_wr++;
		$roster = "Wochentafel_" . $count_wr;
		}
	for ( $weekday=1; $weekday<=7; $weekday++ )
		{
		for ( $count=1; $count<=4; $count++ )
			{
			$variables[$weekday . "_time_" . $count . "_" . $roster] = $_REQUEST[$weekday . "_time_" . $count . "_"];
			}
		}
	$variables['name'] = $roster;
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		libcue_fsdb_save_variables("week_roster/" . $user_id . "/" . $roster, $variables);
		}
	else
		{
		libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
		}
	}
if ( ! isset($_REQUEST['nameg']) && strlen($_REQUEST['name']) > 0 && ! libcue_directory_exists("week_roster/" .
	$user_id, $_REQUEST['name']) && libcue_mobe_weekroster_is_valid_and_sound("", TRUE) )
	{
	for ( $weekday=1; $weekday<=7; $weekday++ )
		{
		for ( $count=1; $count<=4; $count++ )
			{
			$variables[$weekday . "_time_" . $count . "_" . $_REQUEST['name']] = $_REQUEST[$weekday . "_time_" . $count . "_"];
			}
		}
	$variables['name'] = $_REQUEST['name'];
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		libcue_fsdb_save_variables("week_roster/" . $user_id . "/" . $_REQUEST['name'], $variables);
		}
	else
		{
		libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
		}
	}
if ( isset($_REQUEST['nameg']) && strlen($_REQUEST['name']) > 0 && $_REQUEST['nameg'] != $_REQUEST['name'] &&
	! libcue_directory_exists( "week_roster/" . $user_id, $_REQUEST['name'])
	&& libcue_mobe_weekroster_is_valid_and_sound($_REQUEST['nameg']) )
	{
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		rename ( $GLOBALS['DATABASE_PATH'] . "week_roster/" . $user_id . "/" . $_REQUEST['nameg'],
		 	$GLOBALS['DATABASE_PATH'] . "week_roster/" . $user_id . "/" . $_REQUEST['name']);
		}
	for ( $weekday=1; $weekday<=7; $weekday++ )
		{
		for ( $count=1; $count<=4; $count++ )
			{
			$variables[$weekday . "_time_" . $count . "_" . $_REQUEST['name']] = $_REQUEST[$weekday . "_time_" .
			 $count . "_" . $_REQUEST['nameg']];
			$variables[$weekday . "_time_" . $count . "_" . $_REQUEST['nameg']] = "";
			}
		}
	$variables['name'] = $_REQUEST['name'];
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		libcue_fsdb_save_variables("week_roster/" . $user_id . "/" . $_REQUEST['name'], $variables);
		}
	else
		{
		libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
		}
	}
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		require $LIBCUE_PATH . "form/week_roster.php";
		}
	else
		{
		libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
		}
?>

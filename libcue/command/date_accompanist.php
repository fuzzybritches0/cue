<?php
$current_page = "schedule";
if ( isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']))
	{
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	$user_name = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_name");
	$sidebar_page_action = "?command=date_accompanist&option=list&user=" . $user_id;
	require $LIBCUE_PATH . "sidebar/schedule.php";
	}
else
	{	
	$sidebar_page_action = "?command=date_accompanist&option=list";
	require $LIBCUE_PATH . "sidebar/schedule.php";
	}
if ( $_REQUEST['option'] === "save_date" )
	{
	$users = libcue_session_parse_users_groups_roles($_REQUEST['users_selected']);
	libcue_date_save($users, libcue_session_get_var('schedule.year'), libcue_session_get_var('schedule.month'));
	}
if ( ! isset($_REQUEST['user']) )
	{
	$posts[] = libcue_html_headline("Sammeltermine für " . libcue_session_get_var('schedule.year') . "-" . libcue_session_get_var('schedule.month')) .
		libcue_mobe_list_date_collect();
	if ( $_REQUEST['option'] === "view" )
		{
		$posts[] = date_view($_REQUEST['file'], libcue_session_get_var('schedule.year'), libcue_session_get_var('schedule.month'));
		}
	else
		{
		$posts[] = date_form(array(), $_REQUEST['file'], libcue_session_get_var('schedule.year'), libcue_session_get_var('schedule.month'), "begleiter");
		}
	}
else
	{
	$posts[] = libcue_html_headline("Termine für " . $user_name . ", " . libcue_session_get_var('schedule.year') . "-" .
		libcue_session_get_var('schedule.month')) . libcue_mobe_list_date_user($user_id);
	if ( $_REQUEST['option'] === "view" )
		{
		$posts[] = date_view($_REQUEST['file'], libcue_session_get_var('schedule.year'), libcue_session_get_var('schedule.month'), $user_id);
		}
	else
		{
		$posts[] = date_form(array($user_id), $_REQUEST['file'], libcue_session_get_var('schedule.year'), libcue_session_get_var('schedule.month'), "begleiter");
		}
	}
?>

<?php
if ( isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']) &&
	libcue_session_user_is($_REQUEST['user'], "role", "klient") )
	{
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	$user_name = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_name");
	$current_page = "today";
	$sidebar_page_action = "?command=date_client&user=" . $user_id;
	require $LIBCUE_PATH . "sidebar/today.php";
	$users[0] = $user_id;
	if ( $_REQUEST['option'] === "save_date" )
		{
		libcue_date_save($users, libcue_session_get_var('today.year'), libcue_session_get_var('today.month'));
		}
	$posts[] = libcue_html_headline("Termine für " . $user_name . ", " . libcue_session_get_var('today.year') . "-" .
		libcue_session_get_var('today.month')) . libcue_mobe_list_date_client($user_id);
	if ( $_REQUEST['option'] === "view" )
		{
		$posts[] = date_view($_REQUEST['file'], libcue_session_get_var('today.year'), libcue_session_get_var('today.month'), $user_id);
		}
	else
		{
		$posts[] = date_form($users, $_REQUEST['file'], libcue_session_get_var('today.year'), libcue_session_get_var('today.month'), "klient");
		}
	}

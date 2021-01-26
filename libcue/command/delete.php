<?php
if ( ! isset($_REQUEST['layer']) ) $layer = "default";
else $layer = $_REQUEST['layer'];
$layer = $layer . "/";
if ( $_REQUEST['delete'] === "date_client" && isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) &&
	libcue_user_is_active($_REQUEST['user']))
	{
	$current_page = "today";
	$sidebar_page_action = "?command=today";
	require $LIBCUE_PATH . "sidebar/today.php";
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	$user_name = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_name");
	$date_vars = libcue_fsdb_load_variables("date/". $layer . $user_id . "/" . libcue_session_get_var('today.year') . "/" .
		libcue_session_get_var('today.month') . "/" . $_REQUEST['file']);
	$dday = substr($date_vars['date'], 0, 2);
	$dmonth = substr($date_vars['date'], 3, 2);
	$dyear = substr($date_vars['date'], 6, 4);
	if ( libcue_date_not_in_past( $dyear . $dmonth . $dday) )
		{
		if ( libcue_directory_exists("date/" . $layer . $user_id . "/" . libcue_session_get_var('today.year') . "/" .
			libcue_session_get_var('today.month'), $_REQUEST['file']) )
			{
			$usern = libcue_fsdb_load_variable("user/" . $user_id, "user_name");
			libcue_fsdb_delete("date/" . $layer . $user_id . "/" . libcue_session_get_var('today.year') . "/" .
			libcue_session_get_var('today.month') . "/" . $_REQUEST['file']);
			$post = libcue_html_paragraph("Lösche Termin für " . $usern );
			}
		$post = $post . libcue_html_headline("Termine für " . $usern .", ". libcue_session_get_var('today.year') . "-" .
				libcue_session_get_var('today.month')) . libcue_mobe_list_date_client($user_id);
		}
	}
if ( $_REQUEST['delete'] === "date" && isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']))
	{
	$current_page = "schedule";
	$sidebar_page_action = "?command=schedule";
	require $LIBCUE_PATH . "sidebar/schedule.php";
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	$user_name = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_name");
	$date_vars = libcue_fsdb_load_variables("date/" . $layer . $user_id . "/" . libcue_session_get_var('schedule.year') . "/" .
		libcue_session_get_var('schedule.month') . "/" . $_REQUEST['file']);
	$dday = substr($date_vars['date'], 0, 2);
	$dmonth = substr($date_vars['date'], 3, 2);
	$dyear = substr($date_vars['date'], 6, 4);
	if ( libcue_date_not_in_past( $dyear . $dmonth . $dday) )
		{
		if ( libcue_directory_exists("date/" . $layer . $user_id . "/" . libcue_session_get_var('schedule.year') . "/" .
			libcue_session_get_var('schedule.month'), $_REQUEST['file']) )
			{
			$usern = libcue_fsdb_load_variable("user/" . $user_id, "user_name");
			libcue_fsdb_delete("date/" . $layer . $user_id . "/" . libcue_session_get_var('schedule.year') . "/" .
			libcue_session_get_var('schedule.month') . "/" . $_REQUEST['file']);
			$post = $post . libcue_html_paragraph("Lösche Termin für " . $usern );
			}
		libcue_date_remove_user_from_date_collect($user_id, libcue_session_get_var('schedule.year'), libcue_session_get_var('schedule.month'), $_REQUEST['file']);
		$post = $post . libcue_html_headline("Termine für " . $usern .", ". libcue_session_get_var('schedule.year') . "-" .
				libcue_session_get_var('schedule.month')) . libcue_mobe_list_date_user($user_id);
		}
	}
if ( $_REQUEST['delete'] === "date_collect" )
	{
	$current_page = "schedule";
	$sidebar_page_action = "?command=schedule";
	require $LIBCUE_PATH . "sidebar/schedule.php";
	if ( libcue_directory_exists("date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month'),
		$_REQUEST['file']) )
		{
		$date_vars = libcue_fsdb_load_variables("date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month') .
			"/" . $_REQUEST['file']);

		$dday = substr($date_vars['date'], 0, 2);
		$dmonth = substr($date_vars['date'], 3, 2);
		$dyear = substr($date_vars['date'], 6, 4);
		if ( libcue_date_not_in_past( $dyear . $dmonth . $dday) )
			{
			foreach ( explode(",", $date_vars['users']) as $duser )
				{
				if ( libcue_directory_exists("date/" . $layer . $duser . "/" . libcue_session_get_var('schedule.year') . "/" .
					libcue_session_get_var('schedule.month'), $_REQUEST['file']) )
					{
					$usern = libcue_fsdb_load_variable("user/" . $duser, "user_name");
					libcue_fsdb_delete("date/" . $layer . $duser . "/" . libcue_session_get_var('schedule.year') . "/" .
						libcue_session_get_var('schedule.month') . "/" . $_REQUEST['file']);
					$post = $post . libcue_html_paragraph("Lösche Termin für " . $usern );
					}
				}
			libcue_fsdb_delete("date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month') .
				"/" . $_REQUEST['file']);
			}
		}
	$post = $post .  libcue_html_headline("Sammeltermine für " . libcue_session_get_var('schedule.year') . "-" . libcue_session_get_var('schedule.month')) .
			libcue_mobe_list_date_collect();
	}
if ( isset($post) )
	{
	$posts[] = $post;
	}
?>

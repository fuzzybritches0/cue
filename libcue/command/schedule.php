<?php
if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
	{
	$current_page = "schedule";
	$sidebar_page_action = "?command=schedule";
	require $LIBCUE_PATH . "sidebar/schedule.php";
	$schedule_role = libcue_session_get_var('schedule.role');
	$schedule_group = libcue_session_get_var('schedule.group');
	$schedule_month = libcue_session_get_var('schedule.month');
	$schedule_year = libcue_session_get_var('schedule.year');
	$files_all = libcue_fsdb_list("user_name", "role,group", $schedule_role . "," . $schedule_group);
	foreach ( $files_all as $file_all )
		{
		if ( libcue_user_is_active(basename($file_all), "no") )
			{
			$users_active[] = $file_all;
			}
		}
	if ( strlen($schedule_group) > 0 && $schedule_role === "klient" && isset($files[0]) )
		{
		foreach ( $files as $file )
			{
			$users[] = basename($file);
			}
		$user_list = implode(",", $users);
		$overview_link = libcue_html_link( "Gesamtübersicht", $REMOTE_HTTP_PATH . "?command=roster&year=" . $schedule_year .
		  "&month=" . $schedule_month . "&user=" . $user_list);
		}
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "user";
	$options['show_file_name'] = FALSE;
	$options['page_action'] = "?command=schedule";
	$options['char_index'] = FALSE;

	if ( libcue_session_get_var('schedule.role') === "klient" )
		{
		$variables = array( "real_name", "user_name", "role", "group" );
		$var_search = array( "real_name", "user_name", "role", "group" );
		$cell_desc = array( "Name", "Benutzername", "Rolle(n)", "Gruppe(n)", "Begleitübersicht");
		$action[] = "?command=roster&year=" . $schedule_year . "&month=" . $schedule_month  . "&user=";
		$GLOBALS['page_title'] = "K" . " " . $schedule_group .
		  " " . $schedule_month . "-" . $schedule_year;
		$posts[] = libcue_html_headline("Klienten" . " " . $schedule_group .
		  " " . $schedule_year . "-" . $schedule_month) . $overview_link .
		libcue_table_list($users_active ,$options, $variables, $cell_desc, $action, $var_search);
		}
	if ( libcue_session_get_var('schedule.role') === "begleiter" )
		{
		$variables = array( "real_name", "user_name", "initial", "role", "group" );
		$var_search = array( "real_name", "user_name", "initial", "role", "group" );
		$cell_desc = array( "Name", "Benutzername", "Initiale", "Rolle(n)", "Gruppe(n)", "Begleitübersicht");
		$action[] = "?command=roster&year=" . $schedule_year . "&month=" . $schedule_month . "&user=";
		$GLOBALS['page_title'] =  "B " . $schedule_group .
		  " " . $schedule_month . "-" . $schedule_year;
		$posts[] = libcue_html_headline("Begleiter " . $schedule_group .
		  " " . $schedule_year . "-" . $schedule_month) .
		libcue_table_list($users_active ,$options, $variables, $cell_desc, $action, $var_search);
		}
	}
else

	{
	libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
	}
?>

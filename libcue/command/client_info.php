<?php

function client_info_form($date, $client_id, $client_name, $path, $human_readable)
	{
	foreach ( array("1", "2") as $count )
		{
		if ( $count === "1" ) { $count_readable = "FD"; }
		else { $count_readable = "SD"; }
		foreach ( glob($GLOBALS['DATABASE_PATH'] . $path . $count . "*") as $info )
			{
			$file = basename($info);
			$content = libcue_fsdb_load_variable($path, $file);
			$variables[0][] = $count_readable;
			$variables[1][] = libcue_form_text_area($file, $content);
			}
		$variables[0][] = $count_readable;
		$variables[1][] = libcue_form_text_area($count . "next");
		}
	if ( strlen($date) === 8 )
		{
		$get_date = "&day=" . $_REQUEST['day'] . "&month=" . $_REQUEST['month'] . "&year=" . $_REQUEST['year'];
		}
	else
		{
		$get_date = "&day=" . $date . "&mode=weekly";
		}
	$headers = array("Dienst", "Infos");
	return libcue_html_headline("Infotafeln für " . $client_name . ": " . $human_readable) . libcue_form_conclude_input(libcue_table($variables, $headers),
		"?command=client_info&option=save&client=" . $client_id . $get_date); 
	}
$allowed = array("dayly", "1", "2", "3", "4", "5", "6", "7");
$date_req = $_REQUEST['year'] . libcue_zero("2", $_REQUEST['month']) . libcue_zero("2", $_REQUEST['day']);
if ( libcue_directory_exists("user", $_REQUEST['client']) && libcue_user_is_active($_REQUEST['client']) &&
	in_array($_REQUEST['day'], $allowed) && $_REQUEST['mode'] === 'weekly')
	{
	$date = $_REQUEST['day'];
	if ( $date == "dayly" ) { $human_readable = "täglich"; }
	else { $human_readable = human_weekday_long($date); }
	}
elseif ( libcue_directory_exists("user", $_REQUEST['client']) && libcue_user_is_active($_REQUEST['client']) && 
	libcue_mobe_is_valid_date($date_req) && libcue_date_not_in_past($date_req) )
	{
	$human_readable = $date_req;
	$date = $date_req;
	}
if ( isset($date) )	
	{
	if ( ! isset($_REQUEST['year']) ) { $date = $_REQUEST['day']; }
	$client_id = libcue_fsdb_load_variable("user/" . $_REQUEST['client'], 'user_id');
	$client_name = libcue_fsdb_load_variable("user/" . $_REQUEST['client'], 'user_name');
	$current_page = "today";
	$sidebar_page_action = "?command=client_overview&user=" . $client_id;
	require $LIBCUE_PATH . "sidebar/today.php";
	$path = "client_info/"  . $client_id . "/" . $date . "/";
	foreach ( glob($GLOBALS['DATABASE_PATH'] . $path . "*") as $info ) { $infos[] = basename($info); }
	$client_name = libcue_fsdb_load_variable("user/" . $_REQUEST['client'], 'user_name');
	if ( $_REQUEST['option'] === "save" )
		{
		if ( isset($infos[0]) )
			{
			foreach ( $infos as $req_info )
				{
				if ( isset($_REQUEST[$req_info]) )
					{
					$save_vars[$req_info] = $_REQUEST[$req_info];
					}
				}
			}
		foreach ( array("1", "2") as $count )
			{
			if ( isset($_REQUEST[$count . 'next']) )
				{
				$time = utf8_encode(time());
				$save_vars[$count . $time] = $_REQUEST[$count . "next"];
				}
			}
		if ( isset($save_vars) ) { libcue_fsdb_save_variables($path, $save_vars); }
		}
	$posts[] = client_info_form($date, $client_id, $client_name, $path, $human_readable);	
	}

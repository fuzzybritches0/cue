<?php
$current_page = "today";
if ( isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']))
	{
	$client_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], 'user_id');
	$client_name = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], 'user_name');
	$sidebar_page_action = "?command=client_overview&user=" . $_REQUEST['user'];
	require $LIBCUE_PATH . "sidebar/today.php";
	$year = libcue_session_get_var('today.year'); $month = libcue_session_get_var('today.month');
	$variables[0] = libcue_human_list_days_of_month( $month, $year);
	#$client = libcue_mobe_roster_list_client($client_id, $year, $month);
	$headers[] = "Tag"; $headers[] = "Begleitung"; $headers[] = "Infos"; $headers[] = "ändern"; $headers[] = "Termine";
	#$variables = array_merge($variables, $client);
	foreach ( libcue_list_days_of_month($month, $year) as $day )
		{
		$variables[1][$day] = libcue_dates_list_array($client_id, libcue_zero("2", $day), libcue_zero("2", $month), $year, "mobe");
		$variables[2][$day] = libcue_mobe_client_once_roster($client_id, "1", libcue_zero("2", $day), libcue_zero("2", $month), $year) .
				libcue_mobe_client_once_roster($client_id, "2", libcue_zero("2", $day), libcue_zero("2", $month), $year);
		$variables[3][$day] = libcue_html_link("Info", $REMOTE_HTTP_PATH . "?command=client_info&year=" . $year . "&month=" . $month . "&day=" . $day .
					"&client=" . $client_id);
		$variables[4][$day] = libcue_dates_list_array($client_id, libcue_zero("2", $day), libcue_zero("2", $month), $year);
		}
	#$variables = array_merge($variables, $variable);
	$posts[] = libcue_html_headline("Monatsübersicht " . $year . "-" . $month . " für: " . $client_name) .
		libcue_table($variables, $headers);
	}
?>

<?php
$current_page = "today";
if ( isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']))
	{
	$sidebar_page_action = "?command=accompanist_overview&user=" . $_REQUEST['user'];
	require $LIBCUE_PATH . "sidebar/today.php";
	$year = libcue_session_get_var('today.year'); $month = libcue_session_get_var('today.month');
	$variables[] = libcue_human_list_days_of_month( $month, $year);
	$accompanist = libcue_mobe_calculate_accompanist(libcue_fsdb_load_variabl("user/" . $_REQUEST['user'], 'user_id'), $year, $month);
	$variables[] = $accompanist['accompanist_roster_sorted'];
	$headers[] = "Wochentage"; $headers[] = "Dienste";
	$posts[] = libcue_html_headline("Monatsübersicht " . $year . "-" . $month . " für: " . libcue_fsdb_load_variable("user/" . $_REQUEST['user'], 'user_name')) .
		libcue_table($variables, $headers);
	}
?>

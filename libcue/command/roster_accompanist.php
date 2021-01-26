<?php
$current_page = "schedule";
if ( ! isset($_REQUEST['year']) ) { $year = date("Y", time()); }
if ( ! isset($_REQUEST['month']) ) { $month = date("m", time()); }
if ( ! isset($month) ) { $month = $_REQUEST['month']; }
if ( ! isset($year) ) { $year = $_REQUEST['year']; }
if ( ! libcue_mobe_is_valid_date($year . $month . "01") )
	{
	$month = date("m", time()); $year = date("Y", time());
	}
$sidebar_page_action = "?command=roster_accompanist&user=" . $_REQUEST['user'];
require $LIBCUE_PATH . "sidebar/schedule.php";
libcue_session_put_var('schedule.month', $month); libcue_session_put_var('schedule.year', $year);
if ( libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']) )
	{
	$user_id = libcue_session_return_user_id($_REQUEST['user']);
	$user_name = libcue_fsdb_load_variable("user/" . $user_id, "user_name");
	if ( $_REQUEST['option'] === "apply_all_day" )
		{
		$accompanist = libcue_mobe_calculate_accompanist($_REQUEST['user'], $year, $month);
		libcue_mobe_apply_vacation($user_id, $_REQUEST['day_begin'], $_REQUEST['day_end'], $year, $month, $accompanist);
		}
	if ( $_REQUEST['option'] === "set_working_contingent" )
		{
		if ( $_REQUEST['set_working_contingent'] > 10 && $_REQUEST['set_working_contingent'] < 40 )
			{
			$contingents = libcue_fsdb_load_variables( "working_contingent/" . $user_id);
			$contingents[$year . $month] = $_REQUEST['set_working_contingent'];
			libcue_fsdb_save_variables( "working_contingent/" . $user_id, $contingents);
			}
		}
	require $LIBCUE_PATH . "form/apply_vacation.php";
	$GLOBALS['page_title'] = $user_vars['user_name'] . " " . $month . "-" . $year;
	$posts[] = libcue_html_headline("Monatsbericht " . $year . "-" . $month . " für: " . $user_name) .
	  libcue_html_link("Als PDF-Datei öffnen", $GLOBALS[$REMOTE_HTTP_PATH] ."?command=print_view&user=" . $user_vars['user_name'] .
	  "&year=" . $year . "&month=" . $month) .
	  libcue_mobe_calculate_accompanist2($user_id, $year, $month);
	}

?>

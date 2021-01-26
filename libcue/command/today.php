<?php
$current_page = "today";
$posts[] = libcue_html_headline(human_weekday_long(strftime('%u', time())) . ", "  . strftime('%d.%m', time()));
$variables = libcue_mobe_calculate_accompanist($_SESSION['user_id'], date('o', time()), date('m', time()), date('j', time()));
if ( is_array($variables) )
	{
	foreach ( $variables as $attendance )
		{
		$attendances[] = $attendance['client'];
		}
	$attendances = array_unique($attendances);
	}
$sidebar_page_action = "?command=today";
require $LIBCUE_PATH . "sidebar/today.php";
if ( is_array($variables) )
	{
	foreach ( $variables as $attendance )
		{
		$posts[] = libcue_html_headline($attendance['desc'] . libcue_html_link(libcue_fsdb_load_variable("user/" . $attendance['client'], "user_name"),
			"?command=client_overview&year=" . strftime('%Y', time()) . "&month=" . strftime('%m', time()) . "&user=" . $attendance['client']) .
			" - " . $attendance['begin'] . " - " . $attendance['end'], "2") .
			libcue_html_paragraph(libcue_mobe_client_dayly($attendance['client'], $attendance['pos'])) .
			libcue_html_paragraph(libcue_mobe_client_weekly($attendance['client'], $attendance['pos'], strftime('%u', time()))) .
			libcue_html_paragraph(libcue_mobe_client_date($attendance['client'], $attendance['pos'], strftime('%d', time()), strftime('%m', time()),
				strftime('%Y', time()))) .
			libcue_html_paragraph(libcue_mobe_client_once($attendance['client'], $attendance['pos'], strftime('%d', time()), strftime('%m', time()),
				strftime('%Y', time())));
		}
	}
else
	{
	$posts[] = "Genieße deinen freien Tag!";
	}
?>

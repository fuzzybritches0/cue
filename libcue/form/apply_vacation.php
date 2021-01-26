<?php
$variables2 = array( "all_day", "day_begin", "day_end" );
$options2['label'] = "Anwenden";
$options2['action'] = $REMOTE_HTTP_PATH . "?command=roster_accompanist&option=apply_all_day&user=" . $_REQUEST['user'] . "&year=" . $year . "&month=" . $month;
$var_desc2['all_day'] = "Urlaub/Krank"; $var_mode2['all_day'] = array('vacation' => 'Urlaub', 'sick' => 'Krank');
$var_desc2['day_begin'] = "Anwenden von"; $var_mode2['day_begin'] = libcue_list_days_of_month($month, $year);
$var_desc2['day_end'] = "bis einschl."; $var_mode2['day_end'] = libcue_list_days_of_month($month, $year);
$sidebar_sections[] = libcue_html_headline("Urlaub/Krank") . libcue_draw_form($options2, $variables2, $var_desc2, $var_mode2);
$sidebar_sections[] = libcue_html_headline("Arbeitskontingent") . libcue_html_paragraph( "Aktuelles Arbeitskontingent: " .
	libcue_mobe_working_contingent($year, $month, $user_id, TRUE) . "h/Woche" );
$variables3 = array( "set_working_contingent" );
$options3['label'] = "Anwenden";
$options3['action'] = $REMOTE_HTTP_PATH . "?command=roster_accompanist&option=set_working_contingent&user=" . $_REQUEST['user'] . "&year=" . $year . "&month=" . $month;
$var_desc3['set_working_contingent'] = "Kontingent ab " . $month . "-" . $year; $var_mode3['set_working_contingent'] = "text";
$max_length3['set_working_contingent'] = 2;
$sidebar_sections[] = libcue_draw_form($options3, $variables3, $var_desc3, $var_mode3, $max_length3);
?>

<?php
$week_rosters_ = return_files_basename("week_roster/" . $user_id);
if ( isset($week_rosters_) )
	{
	foreach ( $week_rosters_ as $week_roster )
		{
		$week_rosters[$week_roster] = $week_roster;
		}
	$variables2 = array( "week_roster", "day_begin", "day_end" );
	$options2['label'] = "Anwenden";
	$options2['action'] = $REMOTE_HTTP_PATH . "?command=roster&user=" . $users[0] . "&year=" . $year . "&month=" . $month .
		"&option=apply_week_roster";
	$var_desc2['week_roster'] = "Wochentafel"; $var_mode2['week_roster'] = $week_rosters;
	$var_desc2['day_begin'] = "Anwenden von"; $var_mode2['day_begin'] = libcue_list_days_of_month($month, $year);
	$var_desc2['day_end'] = "bis einschl."; $var_mode2['day_end'] = libcue_list_days_of_month($month, $year);
	$sidebar_sections[] = libcue_draw_form($options2, $variables2, $var_desc2, $var_mode2);
	}
?>

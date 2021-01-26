<?php

function libcue_mobe_list_date_client($user, $layer="default")
	{
	$layer = $layer . "/";
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "date/" . $layer . $user . "/" . libcue_session_get_var('today.year'). "/" . libcue_session_get_var('today.month');
	$options['show_file_name'] = FALSE;
	$variables = array( "title", "date", "time_begin", "time_end" );
	$var_search = array( "title", "description" );
	$cell_desc = array( "Titel", "Datum", "Beginn", "Ende", "Ansehen", "Bearbeiten", "Löschen");
	$action[] = "?command=date_client&option=view&user=" . $user . "&file=";
	$action[] = "?command=date_client&user=" . $user . "&file=";
	$action[] = "?command=confirm_delete&delete=date_client&user=" . $user . "&file=";
	$options['page_action'] = "?command=date_client&option=list&user=". $user;
	$options['char_index'] = FALSE;
	$dates = libcue_fsdb_list("date/" . $layer . $user . "/" . libcue_session_get_var('today.year') . "/" . libcue_session_get_var('today.month'));
	if ( isset($dates[0]) )
		{
		foreach ( $dates as $date )
			{
			$files_dates[] = basename($date);
			}
		}
	return libcue_table_list($files_dates ,$options, $variables, $cell_desc, $action, $var_search);
	}
function libcue_mobe_list_date_user($user, $layer="default")
	{
	$layer = $layer . "/";
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "date/" . $layer . $user . "/" . libcue_session_get_var('schedule.year'). "/" . libcue_session_get_var('schedule.month');
	$options['show_file_name'] = FALSE;
	$variables = array( "title", "date", "time_begin", "time_end" );
	$var_search = array( "title", "description" );
	$cell_desc = array( "Titel", "Datum", "Beginn", "Ende", "Ansehen", "Bearbeiten", "Löschen");
	$action[] = "?command=date_accompanist&option=view&user=" . $user . "&file=";
	$action[] = "?command=date_accompanist&user=" . $user . "&file=";
	$action[] = "?command=confirm_delete&delete=date&user=" . $user . "&file=";
	$options['page_action'] = "?command=date_accompanist&option=list&user=". $user;
	$options['char_index'] = FALSE;
	$dates = libcue_fsdb_list("date/" . $layer . $user . "/" . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month'));
	if ( isset($dates[0]) )
		{
		foreach ( $dates as $date )
			{
			$files_dates[] = basename($date);
			}
		}
	return libcue_table_list($files_dates ,$options, $variables, $cell_desc, $action, $var_search);
	}
function libcue_mobe_list_date_collect( $layer="default")
	{
	$layer = $layer . "/";
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month');
	$options['show_file_name'] = FALSE;
	$variables = array( "title", "date", "time_begin", "time_end", "user_names" );
	$var_search = array( "title", "description" );
	$cell_desc = array( "Titel", "Datum", "Beginn", "Ende", "Benutzer", "Ansehen", "Bearbeiten", "Löschen");
	$action[] = "?command=date_accompanist&option=view&file=";
	$action[] = "?command=date_accompanist&file=";
	$action[] = "?command=confirm_delete&delete=date_collect&file=";
	$options['page_action'] = "?command=date_accompanist&option=list";
	$options['char_index'] = FALSE;
	$dates = libcue_fsdb_list("date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month'));
	if ( isset($dates[0]) )
		{
		foreach ( $dates as $date )
			{
			$files_dates[] = basename($date);
			}
		}
	return libcue_table_list($files_dates ,$options, $variables, $cell_desc, $action, $var_search);
	}
// function libcue_mobe_roster_list_client($user, $year, $month, $accompanist_fields_only=FALSE)
// 	{
// 	$days_in_month = libcue_list_days_of_month($month, $year);
// 	$user_id = libcue_session_return_user_id($user);
// 	$days_accompanist = return_days_accompanist( $user_id, $year, $month);
// 	$days_time = return_days_time( $user_id, $year, $month );
// 	foreach ( $days_in_month as $day )
// 		{
// 		if ( $accompanist_fields_only === FALSE )
// 			{
// 			for ( $count=1; $count<=4; $count++ )
// 				{
// 				$return_vars[$count][$day] = "" . $days_time[$day . "_" . $user_id . "_time_" . $count];
// 			}
// 		}
// 		for ( $count=1; $count<=2; $count++ )
// 			{
// 			$initial = libcue_fsdb_load_variable("user/" . $days_accompanist[$day . "_" . $user_id . "_" . $count], "initial");
// 			$user_name = libcue_fsdb_load_variable("user/" . $days_accompanist[$day . "_" . $user_id . "_" . $count], "user_name");
// 			if ( strlen($initial) < 1 && strlen($user_name) > 3 )
// 				{
// 				libcue_error_message_warning("Der Benutzer: " . $user_name . " hat keine Initiale gesetzt!");
// 				$initial = "??";
// 				}
// 			$return_vars[$count+4][$day] = $initial;
// 			}
// 		}
// 	return $return_vars;
// 	}
function libcue_mobe_times_are_valid($dates)
	{
	if ( is_array($dates) )
		{
		foreach ( $dates as $date)
			{
			$hour1 = substr($date[0], 0, 2); $minute1 = substr($date[0], 3, 2);
			$hour2 = substr($date[1], 0, 2); $minute2 = substr($date[1], 3, 2);
			if ( ! isset($date[0]) || ! isset($date[2]) ||
				! is_numeric($hour1 . $hour2 . $minute1 . $minute2) ||
				$hour1 < 0 || $hour2 < 0 || $hour1 > 23 || $hour2 > 23 ||
				$minute1 < 0 || $minute2 < 0 || $minute1 > 59 || $minute2 > 59 ||
				$hour2 . $minute2 < $hour1 . $minute1 )
				{
				return FALSE;
				}
			}
		}
	return TRUE;
	}
function libcue_mobe_parse_dates_array($dates)
	{
	if ( strlen($dates) < 1 ) { return; }
	$dates = preg_replace('/\s\s+/', ' ', $dates);
	$dates_array = explode(",", $dates);
	foreach ( $dates_array as $date)
		{
		$vars = explode(" ", trim($date));
		if ( strlen($trim_vars_2) < 1 ) { unset($trim_vars_2); }
		$dates_r[] = array ( trim($vars[0]), trim($vars[1]), trim($vars[2]) );
		}
	return $dates_r;
	}
function libcue_mobe_diff_dates($dates1,$dates2)
	{
	foreach ($dates1 as $date)
		{
		$found = FALSE;
		foreach ($dates2 as $date2)
			{
			if ( $date[0] === $date2[0] && $date[1] === $date2[1] && $date[2] === $date2[2] )
				{
				$found = TRUE;
				}
			}
		if ( $found === FALSE )
			{
			$return_dates[] = $date;
			}
		}
	return $return_dates;
	}
function libcue_mobe_roster_input_save($user, $year, $month)
	{
	$user_id = libcue_session_return_user_id($user);
	$days_in_month = libcue_list_days_of_month($month, $year);
	foreach ( $days_in_month as $day )
		{
		$dates = libcue_dates_list_array($user_id, $day, $month, $year, "mobe", "list", "mobe_user_id");
		$dates = libcue_mobe_parse_dates_array($dates);
		$_REQUEST_day = trim($_REQUEST[$day], " ,");
		$dates_req = libcue_mobe_parse_dates_array($_REQUEST_day);
		if ( ! libcue_mobe_times_are_valid($dates_req) )
			{
			libcue_error_message("Ungültige oder fehlende Zeitangabe(n) am Tag " . $day);
			return;
			}
		if ( is_array($dates_req) )
			{
				foreach ($dates_req as $req_user)
				{
				if ( strlen($req_user[2]) > 0 && ( ! libcue_directory_exists("user", $req_user[2]) || ! libcue_user_is_active($req_user[2]) ) )
					{
					return;
					}
					$req_users[] = libcue_fsdb_load_variable("user/" . $req_user[2], "user_id");
				}
			}
		if ( is_array($dates) )
			{
			foreach ($dates as $date)
				{
				$date_users[] = libcue_fsdb_load_variable("user/" . $date[2], "user_id");
				}
			}
		$count = 0;
		if ( is_array($date_users) )
			{
			foreach ( $date_users as $date_user)
				{
				$id_dates[] = array($dates[$count][0], $dates[$count][1], $date_user);
				$count++;
				}
			}
		if ( is_array($req_users) )
			{
			$count = 0;
			foreach ( $req_users as $req_user)
				{
				$id_dates_req[] = array($dates_req[$count][0], $dates_req[$count][1], $req_user);
				$count++;
				}
			}
			if ( ! is_array($id_dates) && is_array($id_dates_req) )
				{
				$add_dates = $id_dates_req;
				$remove_dates = array();
				}
			elseif ( is_array($id_dates) && ! is_array($id_dates_req) )
				{
				$remove_dates = $id_dates;
				$add_dates = array();
				}
			elseif ( ! is_array($id_dates) && ! is_array($id_dates_req) )
				{
				$remove_dates = array();
				$add_dates = array();
				}
			else
				{
				$remove_dates = libcue_mobe_diff_dates($id_dates, $id_dates_req);
				$add_dates = libcue_mobe_diff_dates($id_dates_req, $id_dates);
				}
		if ( isset($remove_dates[0]) )
			{
				foreach ( $remove_dates as $remove_date)
				{
				$removed_dates[] = libcue_mobe_remove_date( $user_id, $remove_date[2], $year, $month, $day, array( $remove_date[0], $remove_date[1]));
				}
			}
		if ( isset($add_dates[0]) )
			{
			foreach ( $add_dates as $add_date)
				{
					$added_dates[] = libcue_mobe_add_date( $user_id, $add_date[2], $year, $month, $day, array( $add_date[0], $add_date[1]));
				}
			}
		if ( libcue_error_occurred() )
			{
			if ( isset($added_dates[0]) )
				{
				foreach ( $added_dates as $add_date )
					{
					if ( is_array($add_date) )
						{
							libcue_fsdb_delete($add_date[0]);
							libcue_fsdb_delete($add_date[1]);
						}
					}
				}
			if ( isset($removed_dates[0]) )
				{
				foreach ( $removed_dates as $remove_date)
					{
					libcue_mobe_add_date($remove_date[0], $remove_date[1], $remove_date[2], $remove_date[3], $remove_date[4], $remove_date[5], TRUE);
					}
				}
			}
		unset($id_dates, $id_dates_req, $req_users, $date_users, $remove_dates, $add_dates, $added_dates, $removed_dates);
		}
	}
function libcue_mobe_remove_date( $client_id, $acc_id, $year, $month, $day, $times)
	{
	$day = libcue_zero(2, $day);
	if ( libcue_mobe_permission($acc_id) && libcue_date_not_in_past($year . $month . $day) && ! libcue_error_occurred() )
		{
		$remove = glob($GLOBALS['DATABASE_PATH'] . "date/mobe/" . $client_id . "/" . $year . "/" . $month . "/" . $year . $month . $day .
			substr($times[0], 0, 2) . substr($times[0], 3, 2) . "*");
		if ( isset($client_id) )
			{
			$remove = "date/mobe/" . $client_id . "/" . $year . "/" . $month . "/" . basename($remove[0]);
			$removed = libcue_fsdb_load_variables($remove);
			libcue_fsdb_delete($remove);
			}
		$remove = glob($GLOBALS['DATABASE_PATH'] . "date/mobe/" . $acc_id . "/" . $year . "/" . $month . "/" . $year . $month . $day .
			substr($times[0], 0, 2) . substr($times[0], 3, 2) . "*");
		if ( isset($acc_id) )
			{
			$remove = "date/mobe/" . $acc_id . "/" . $year . "/" . $month . "/" . basename($remove[0]);
			$removed = libcue_fsdb_load_variables($remove);
			libcue_fsdb_delete($remove);
			}
		$removed_date = array( $client_id, $acc_id, $year, $month, $day, array( $removed['time_begin'], $removed['time_end']));
		return $removed_date;
		}
	}
function libcue_mobe_add_date( $user1, $user2, $year, $month, $day, $times, $restore=FALSE)
	{
	if ( libcue_session_user_is($user1, "role", "begleiter") )
		{
		$client_id = $user2; $acc_id = $user1;
		}
	else
		{
		$client_id = $user1; $acc_id = $user2;
		}
	$client_name = libcue_fsdb_load_variable("user/" . $client_id, "user_name");
	$acc_name = libcue_fsdb_load_variable("user/" . $acc_id, "user_name");
	$times[0] = substr($times[0],0,2) . ":" . substr($times[0], 3, 2);
	$times[1] = substr($times[1],0,2) . ":" . substr($times[1], 3, 2);
	$day = libcue_zero(2, $day);
	if ( $restore === TRUE || libcue_mobe_permission($acc_id) &&
		libcue_mobe_client_acc($client_id, $acc_id) &&
		libcue_date_not_in_past($year . $month . $day) &&
		! libcue_error_occurred() )
		{
		if ( ! libcue_date_user_is_free($year, $month, $day, $client_id, $times, "mobe", FALSE) &&
		( ! libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "mobe", FALSE) ||
		! libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "vacation", FALSE) ||
		! libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "sick", FALSE) ||
		! libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "default", FALSE) ) )
			{
			libcue_error_message("Zeile: " . $day . ", " . $acc_name . " und " . $client_name . " sind zu dieser Zeit bereits eingeteilt!");
			return FALSE;
			}
		$client_free = libcue_date_user_is_free($year, $month, $day, $client_id, $times, "mobe", FALSE);
		$acc_free = FALSE;
		if ( libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "mobe", FALSE) &&
		 libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "vacation", FALSE) &&
		 libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "sick", FALSE) &&
		 libcue_date_user_is_free($year, $month, $day, $acc_id, $times, "default", FALSE) )
			{
			$acc_free = TRUE;
			}
		$date['date'] = $day . "." . $month . "." . $year;
		$date['date_id'] = libcue_session_key("dates_collect/mobe/" . $year . "/" . $month, $year . $month . $day . substr($times[0],0,2) .
			substr($times[0], 3, 2));
		$date['day'] = $day; $date['month'] = $month; $date['year'] = $year; $date['time_begin'] = $times[0]; $date['time_end'] = $times[1];
		if ( isset($client_id) )
			{
				if ( ! $client_free )
					{
					libcue_error_message_warning("Zeile: " . $day . ", Der Klient " . $client_name . " wird zu dieser Zeit bereits begleitet!");
					}
				else
					{
					$date['title'] = libcue_fsdb_load_variable("user/" . $acc_id, 'user_name');
					if ( $acc_free ) { $date['mobe_user_id'] = $acc_id; }
					$client_date = "date/mobe/" . $client_id . "/" . $year . "/" . $month . "/" . $date['date_id'];
					libcue_fsdb_save_variables($client_date, $date);
					}
			}
		if ( isset($acc_id) )
			{
			if ( ! $acc_free )
				{
				libcue_error_message_warning("Zeile: " . $day . ", Der Begleiter " . $acc_name . " ist zu dieser Zeit bereits eingeteilt!");
				}
			else
				{
				$date['title'] = libcue_fsdb_load_variable("user/" . $client_id, 'user_name');
				if ( $client_free ) { $date['mobe_user_id'] = $client_id; }
				$acc_date = "date/mobe/" . $acc_id . "/" . $year . "/" . $month . "/" . $date['date_id'];
				libcue_fsdb_save_variables( $acc_date, $date);
				}
			}
		return array($client_date, $acc_date);
		}
	}
function libcue_mobe_client_acc($client_id, $acc_id)
	{
	if ( ! libcue_session_user_is($client_id, 'role', 'klient') && strlen($client_id) > 1 )
		{
		libcue_error_message(libcue_fsdb_load_variable("user/" . $client_id, "user_name") . " ist kein Klient!");
		return FALSE;
		}
	if ( ! libcue_session_user_is($acc_id, 'role', 'begleiter') && strlen($acc_id) > 1 )
		{
		libcue_error_message(libcue_fsdb_load_variable("user/" . $client_id, "user_name") . " ist kein Begleiter!");
		return FALSE;
		}
	return TRUE;
	}
function libcue_mobe_permission($acc)
	{
	if ( $_SESSION['user_id'] === $acc && libcue_session_user_is($_SESSION['user_id'], 'role', 'begleiter') ) { return TRUE; }
	if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') || libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') )
		{ return TRUE; }
	libcue_error_message("Du darfst diese Änderung nicht vornehmen! Zugriff verweigert!");
	return FALSE;
	}
function libcue_mobe_roster_input($user, $year, $month)
	{
	$headers[] = "Wochentage";
	$days_in_month = libcue_list_days_of_month($month, $year);
	$variables[] = libcue_human_list_days_of_month($month, $year);
	$user_id = libcue_session_return_user_id($user);
	$input_fields = empty_input_fields($days_in_month);
	foreach ( $days_in_month as $day )
		{
		$date = $year . libcue_zero(2, $month) . libcue_zero(2, $day);
		$field_content = libcue_dates_list_array($user_id, $day, $month, $year, "mobe", "list", "mobe_user_id");
		$input_fields[$day] = libcue_form_text($day, "60", $field_content);
		}
	$variables[] = $dates;
	$headers[] = $user;
	$variables[] = $input_fields;
	$return_vars['headers'] = $headers;
	$return_vars['variables'] = $variables;
	return $return_vars;
	}
function libcue_mobe_calculate_accompanist($user, $year, $month, $day_input="00")
	{
	if ( $day_input === "00" )
		{
		$days_in_month = libcue_list_days_of_month($month, $year);
		}
	else
		{
		$days_in_month[$day_input] = $day_input;
		}
	$weekdays_of_month = libcue_list_weekdays_of_month($month, $year);
	$user_vars = libcue_fsdb_load_variables( "user/" . $user );
	$initial = $user_vars['initial'];
	$user_id = $user_vars['user_id'];
	$month_working_contingent = libcue_mobe_working_contingent($year, $month, $user_id);
	$accompanist_roster = array();
#	foreach ( libcue_fsdb_list("user_name", "role", "klient") as $client )
#		{
#		$client_id = libcue_session_return_user_id($client);
#		$days_accompanist = return_days_accompanist($client_id, $year, $month);
#		$days_times = return_days_time($client_id, $year, $month);
#		foreach ( $days_in_month as $day )
#			{
#			$date = $year . $month . libcue_zero( "2", $day);
#			for ( $count=1; $count<=2; $count++)
#				{
#				if ( $days_accompanist[$day . "_" . $client_id . "_" . $count] === $user_id )
#					{
#					for ( $countx=1; $countx<=4; $countx++ )
#						{
#						$times[$countx] = $days_times[$day . "_" . $client_id . "_time_" . $countx];
#						}
#					if ( $count === 1 )
#						{
#						$time_begin_end = trim($times[1]) . " - " . trim($times[2]);
#						$time_worked[$day][] = libcue_time_difference( $times[1], $times[2]);
#						if ( $day_input !== "00" )
#							{
#							$var['begin'] = $times[1]; $var['end'] = $times[2];
#							$var['client'] = $client_id; $var['field'] = "1";
#							$var['pos'] = "1";
#							$variables['client_roster_field'][$times[1]] =  $var;
#							}
#						}
#					if ( $count === 2 )
#						{
#						$time_begin_end = trim($times[3]) . " - " . trim($times[4]);
#						$time_worked[$day][] = libcue_time_difference( $times[3], $times[4]);
#						if ( $day_input !== "00" )
#							{
#							$var['begin'] = $times[3]; $var['end'] = $times[4];
#							$var['client'] = $client_id; $var['field'] = "2";
#							$var['pos'] = "2";
#							$variables['client_roster_field'][$times[3]] =  $var;
#
#							}
#						}
#					if ( strlen($time_begin_end) > 2 )
#						{
#						$accompanist_roster[$day][] = "{" . $time_begin_end . ", "
#							. $client . "} ";
#						}
#					}
#				}
#			if ( ! isset($accompanist_roster[$day]) ) { $accompanist_roster[$day] = "";
#			 $time_worked[$day] = ""; }
#			}
#		}
	$time_worked_add = array();
	foreach ( $days_in_month as $day)
		{
		$accompanist_roster_vacation[$day] = libcue_dates_list_array($user_id, $year, $zmonth, $zday, "vacation");
		$accompanist_roster_sick[$day] = libcue_dates_list_array($user_id, $year, $zmonth, $zday, "sick");
		$zmonth = libcue_zero("2", $month); $zday = libcue_zero("2", $day);
		$vacation = glob($GLOBALS['DATABASE_PATH'] . "date/vacation/" . $user_id . "/" . $year . "/" . $zmonth . "/" . $year . $zmonth .
			$zday . "*");
			if ( isset($vacation[0]) )
				{
				if  ( $weekdays_of_month[$day] !== '6' &&  $weekdays_of_month[$day] !== '7')
					{
					$contingent = libcue_mobe_working_contingent( $year, $month, $user_id, TRUE);
					$remainder = $contingent % 5;
					$remainder = $remainder / 5;
					$per_day_contingent = $contingent / 5;
					$hours = $per_day_contingent - $remainder;
					$minutes = $remainder * 60;
					$time_worked[$day][0] = libcue_zero('2', $hours) . ":" . libcue_zero('2', $minutes);
					}
				$accompanist_roster[$day][0] = "URLAUB";
				}
		if ( libcue_directory_exists( "date/default/" . $user_id . "/" . $year, $zmonth) || libcue_directory_exists("date/mobe/" . $user_id . "/" .
			$year, $zmonth) )
			{
			$dates_default = array(); $dates_mobe = array();
			if ( libcue_directory_exists( "date/default/" . $user_id . "/" . $year, $zmonth) )
				{
				$dates_default = glob($GLOBALS['DATABASE_PATH'] . "date/default/" . $user_id . "/" . $year . "/" . $zmonth . "/" . $year . $zmonth .
					$zday . "*");
				}
			if ( libcue_directory_exists( "date/mobe/" . $user_id . "/" . $year, $zmonth) )
				{
				$dates_mobe = glob($GLOBALS['DATABASE_PATH'] . "date/mobe/" . $user_id . "/" . $year . "/" . $zmonth . "/" . $year . $zmonth . $zday . "*");
				}
			$date_files = array_merge($dates_mobe, $dates_default);
			if ( isset($date_files[0]) )
				{
				foreach ( $date_files as $date_id )
					{
					$date_id_vars = libcue_fsdb_load_variables($date_id);
					$time_worked[$day][] = libcue_time_difference( $date_id_vars['time_begin'], $date_id_vars['time_end'] );
					$accompanist_roster[$day][] = "{" . $date_id_vars['time_begin'] . "-" . $date_id_vars['time_end'] . ", " .
						$date_id_vars['title'] . "}";
					if ( $day_input !== "00" )
						{
						$var['begin'] = $date_id_vars['time_begin']; $var['end'] = $date_id_vars['time_end'];
						$var['desc'] = $date_id_vars['title']; $var['field'] = "0";
						$var['client'] = "";
						$variables['client_roster_field'][$var['begin']] =  $var;
						}
					}
				}
			}
		if ( isset($time_worked[$day][0]) )
			{
			foreach ( $time_worked[$day] as $add_this )
				{
				$time_worked_add[$day] = libcue_time_add( $time_worked_add[$day], $add_this);
				}
			}
		else
			{
			$time_worked_add[$day] = "";
			}
		if ( isset($accompanist_roster[$day][0]) )
			{
			sort($accompanist_roster[$day]);
			$accompanist_roster_sorted[$day] = implode("", $accompanist_roster[$day]);
			}
		else
			{
			$accompanist_roster_sorted[$day] = "";
			}
		}
	foreach ( $time_worked_add as $time_worked )
		{
		$total_time = libcue_time_add( $total_time, $time_worked);
		}
	if ( $day_input !== "00" )
		{
		if ( isset($variables['client_roster_field']) )
			{
			ksort($variables['client_roster_field']);
			}
		return $variables['client_roster_field'];
		}
	$variables['accompanist_roster_sorted'] = $accompanist_roster_sorted;
	$variables['accompanist_roster_vacation'] = $accompanist_roster_vacation;
	$variables['accompanist_roster_sick'] = $accompanist_roster_sick;
	$variables['time_worked_add'] = $time_worked_add;
	$variables['total_time'] = $total_time;
	$variables['month_working_contingent'] = $month_working_contingent;
	return $variables;
	}
	function libcue_mobe_working_contingent_day($year, $month, $user_id)
		{
		$contingent = libcue_mobe_working_contingent( $year, $month, $user_id, TRUE);
		$remainder = $contingent % 5;
		$remainder = $remainder / 5;
		$per_day_contingent = $contingent / 5;
		$hours = $per_day_contingent - $remainder;
		$minutes = $remainder * 60;
		return libcue_zero('2', $hours) . ":" . libcue_zero('2', $minutes);
		}
function libcue_mobe_calculate_accompanist2($user_id, $year, $month)
	{
	$month_working_contingent = libcue_mobe_working_contingent($year, $month, $user_id);
	$days_in_month = libcue_list_days_of_month($month, $year);
	$headers[] = "Wochentage"; $headers[] = "Dienstzeiten"; $headers[] = "Gesamt/Tag";
	$user_vars = libcue_fsdb_load_variables( "user/" . $_REQUEST['user'] );
	$variables[] = libcue_human_list_days_of_month($month, $year);
	$days = libcue_list_days_of_month($month, $year);
	foreach ( $days as $day)
		{
		$dates = array();
		$default = libcue_dates_list_array($user_id, $day, $month, $year, "default", "array");
		$mobe = libcue_dates_list_array($user_id, $day, $month, $year, "mobe", "array");
		$vacation = libcue_dates_list_array($user_id, $day, $month, $year, "vacation", "array");
		$sick = libcue_dates_list_array($user_id, $day, $month, $year, "sick", "array");

		if ( isset($sick[0]) )
			{
			$list[$day] = "KRANK";
			$list_total_time[$day] = libcue_time_add($sick[0][0], $sick[0][1]);
			}
		if ( isset($vacation[0]) )
			{
			$list[$day] = "URLAUB";
			$list_total_time[$day] = libcue_mobe_working_contingent_day($year, $month, $user_id);
			}
		if ( isset($mobe[0]) )
			{
			$dates = array_merge($dates, $mobe);
			}
		if ( isset($default[0]) )
			{
			$dates = array_merge($dates, $default);
			}
		if ( isset($dates[0]) )
			{
			$dates = libcue_mobe_sort_dates($dates);
			sort($dates);
			$time_total = "00:00";
			$work_on_off = array();
			foreach ( $dates as $date)
				{
				$work_on_off[] = $date[0]; $work_on_off[] = $date[1];
				$add_time = libcue_time_difference($date[0], $date[1]);
				$time_total = libcue_time_add($time_total, $add_time);
				}
				$list_total_time[$day] = $time_total;
				$flip_flop = array(", ", " - ");
				for ( $i = 0; $i <= count($work_on_off); $i++)
					{
					if ( $work_on_off[$i] !== $work_on_off[$i+1] && $work_on_off[$i] !== $work_on_off[$i-1])
						{
						$temp = $temp . $flip_flop[$i % 2 + $x] . $work_on_off[$i];
						}
					}
				$list[$day] = substr($temp, 1, strlen($temp) - 1);
				$temp = "";
			}
		if ( ! isset($list_total_time[$day]) )
			{
			$list_total_time[$day] = "";
			}
		if ( ! isset($list[$day]) )
			{
			$list[$day] = "";
			}
		}
	foreach ( $list_total_time as $add )
		{
		if ( $add !== "" )
			{
			$total_time_month = libcue_time_add($total_time_month, $add);
			}
		}

	if ( ! isset($total_time_month) )
		{
		$total_time_month = "0";
		}
	else
		{
			$hours = libcue_remove_zero(substr($total_time_month,0,2));
			$minutes = libcue_remove_zero(substr($total_time_month,3,2));
			$minutes = $minutes / 60;
			$total_time_month = $hours + $minutes;
			$total_time_month = round($total_time_month,2,PHP_ROUND_HALF_EVEN);
		}
	$variables[] = $list;
	$variables[] = $list_total_time;

	return libcue_table($variables, $headers) . libcue_html_paragraph("Gesamt/Monat: " . $total_time_month
	  . " von " . $month_working_contingent);
	}

function libcue_mobe_sort_dates($dates)
	{
	foreach ( $dates as $date )
		{
		$return_dates[$date[0]] = $date;
		}
	return $return_dates;
	}

function libcue_mobe_validate_users($users)
	{
	if ( isset($users) )
		{
		foreach ( explode("," ,$users) as $each_user )
			{
			if ( libcue_directory_exists("user", $each_user) && libcue_user_is_active($each_user) )
				{
				$users_return[] = $each_user;
				}
			else
				{
				libcue_error_message( "Benutzer: " . $each_user . " existiert nicht!" );
				}
			}
		}
	return $users_return;
	}

function empty_input_fields($days_in_month)
	{
	foreach ( $days_in_month as $day )
		{
		for ( $count=1;$count<=2; $count++)
			{
			$input_fields[$count][$day] = "";
			}
		}
	return $input_fields;
	}
function libcue_mobe_is_valid_date($date)
	{
	if ( is_numeric($date) && strlen($date) === 8 &&
		checkdate(substr($date,"4","2"), substr($date, "6", "2"), substr($date, "0", "4")) )
		{
		return TRUE;
		}
	else
		{
		libcue_error_message( "Das Datum: " . $date . " ist nicht gültig!" );
		return FALSE;
		}
	}
function libcue_mobe_times_are_valid_and_sound($times)
	{
	if ( strlen($times[1]) > 0 && strlen($times[2]) === 0 ||
	     strlen($times[1]) === 0 && strlen($times[2]) > 0 ||
	     strlen($times[3]) > 0 && strlen($times[4]) === 0 ||
	     strlen($times[3]) === 0 && strlen($times[4]) > 0 )
		{
		libcue_error_message( "Die Zeiten müssen in Paaren angegeben werden!" );
		return FALSE;
		}
	foreach ( $times as $time )
		{
		if ( strlen($time) > 0 )
			{
			$time = trim($time);
			if ( strlen($time) != 5 )
				{
				libcue_error_message( "Angabe der Zeit: " . $time . " nicht gültig!");
				return FALSE;
				}
			if ( ! is_numeric(substr($time, "0", "2") . substr($time, "3", "2")) )
				{
				libcue_error_message( "Die Zeiten: " . $time . " haben ungültige Zeichen!" );
				return FALSE;
				}
			$hour = substr($time, "0", "2"); $minute = substr($time, "3", "2");
			if ( $hour > 24 || $hour < 0 )
				{
				libcue_error_message( " Die Stundenangabe: " . $hour . " ist ungültig!" );
				return FALSE;
				}
			if ( $minute > 60 || $minute < 0 )
				{
				libcue_error_message( "Die Minutenangabe: " . $minute . " ist ungültig!" );
				return FALSE;
				}
			if ( isset( $old_time ) && $old_time >= $hour . $minute )
				{
				libcue_error_message( "Die Zeit: " . $hour . ":" . $minute . " muss größer sein!");
				return FALSE;
				}
			$old_time = $hour . $minute;
			}
		}
	return TRUE;
	}
function libcue_mobe_weekroster_is_valid_and_sound($roster, $filled_out=FALSE)
	{
	for ( $weekday=1; $weekday<=7; $weekday++ )
		{
		unset($times);
		for ( $count=1; $count<=4; $count++ )
			{
			if ( strlen($_REQUEST[$weekday . "_time_" . $count . "_" . $roster]) > 0 )
				{
				$is_filled_out = TRUE;
				}
			$times[$count] = $_REQUEST[$weekday . "_time_" . $count . "_" . $roster];
			}
		if ( isset($times) && ! libcue_mobe_times_are_valid_and_sound($times) )
			{
			for ( $count=1; $count<=4; $count++ )
				{
				$_SESSION['REQUEST_ERROR_' . $weekday . "_time_" . $count . "_" . $roster] =
					$_REQUEST[$weekday . "_time_" . $count . "_" . $roster];
				}
			$with_false=TRUE;
			}
		}
	if ( $with_false === TRUE )
		{
		return FALSE;
		}
	if ( $filled_out === FALSE )
		{
		return TRUE;
		}
	if ( $filled_out === TRUE && $is_filled_out === TRUE )
		{
		return TRUE;
		}
	return FALSE;
	}
function return_days_accompanist( $user_id, $year, $month )
	{
	if ( libcue_directory_exists( "month_roster/" . $user_id, $year . $month) )
		{
		$vars = libcue_fsdb_load_variables("month_roster/" . $user_id . "/" . $year . $month);
		if ( isset($vars) )
			{
			foreach ( $vars as $var_name => $var_content )
				{
				if ( strlen($var_content) > 0 )
					{
					$return_vars[$var_name] = libcue_fsdb_load_variable( "user/" . $var_content, "user_id");
					}
				}
			return $return_vars;
			}
		}
	}
function return_days_time( $user_id, $year, $month )
	{
	if ( libcue_directory_exists( "month_roster_time/" . $user_id, $year . $month) )
		{
		return libcue_fsdb_load_variables("month_roster_time/" . $user_id . "/" . $year . $month);
		}
	}
function return_files_basename($directory)
	{
	$files = glob($GLOBALS['DATABASE_PATH'] . $directory . "/*");
	foreach ( $files as $file )
		{
		$files_bn[] = basename($file);
		}
	return $files_bn;
	}

function libcue_mobe_accompanist_is_free($year, $month, $day, $initial, $times, $client_id_set="", $time_pos="" )
	{
	$month = libcue_zero("2", $month);
	$day = libcue_zero("2", $day);
	$month_nz = libcue_remove_zero($month);
	$day_nz = libcue_remove_zero($day);
	$user_id = libcue_session_return_user_id($initial);
	$user_name = libcue_fsdb_load_variable("user/" . $user_id, "user_name");
	$vacation = libcue_fsdb_load_variable("vacation/" . $user_id . "/" . $year . "/" . $month . "/" . $day, 'vacation');
	if ( isset($vacation) )
		{
		libcue_error_message("Der Benutzer " . $user_name . " ist am " . $day . ". für URLAUB eingetragen!");
		return FALSE;
		}
	if ( ! libcue_date_user_is_free($year, $month, $day, $user_id, $times) )
		{
		return FALSE;
		}
	foreach ( $times as $count => $time )
		{
		$time = trim($time);
		$timex[$count] = substr($time, "0","2") . substr($time, "3", "2");
		}
	$days_in_month = libcue_list_days_of_month($month, $year);
	foreach ( libcue_fsdb_list("user_name", "role", "klient") as $client )
		{
		$client_id = libcue_session_return_user_id($client);
		$days_accompanist = return_days_accompanist($client_id, $year, $month);
		$days_times = return_days_time($client_id, $year, $month);
		if ( isset($days_times[$day_nz . "_" . $client_id . "_time_1"]) ||
		     isset($days_times[$day_nz . "_" . $client_id . "_time_3"]) )
			{
			for ( $count=1; $count<=4; $count++ )
				{
				$times[$count] = $days_times[$day_nz . "_" . $client_id . "_time_" . $count];
				}
			for ( $count=1;$count<=2; $count++)
				{
				if ( $days_accompanist[$day_nz . "_" . $client_id . "_" . $count] === $initial )
					{
					if ( $count === 1 ) { $tcount = "1"; }
					if ( $count === 2 ) { $tcount = "3"; }
					$timexx[0] = $times[$tcount];
					$timexx[1] = $times[$tcount+1];
					foreach ( $timexx as $countx => $time )
						{
						$time = trim($time);
						$timexx[$countx] = substr($time, "0","2") . substr($time, "3", "2");
						}
						$earlier = 0; $later = 0;
						if ( $timexx[0] < $timex[0] && $timexx[1] <= $timex[0] ) { $earlier = 1; }
						if ( $timexx[0] >= $timex[1] && $timexx[1] > $timex[1] ) { $later = 1; }
						if ( $earlier === 0  && $later === 0 )
						{
						if ( $client_id_set != "" )
							{
							if ( $client_id_set != $client_id || $time_pos != $count )
								{
								$client_name = libcue_fsdb_load_variable( "user/" . $client_id, "user_name");
								libcue_error_message( "Der Benutzer " . $user_name . " ist am " . $day . ". von " . $timexx[0] .
								" bis " . $timexx[1] . " bereits bei " . $client_name . " eingeteilt!");
								return FALSE;
								}
							}
						else
							{
							$client_name = libcue_fsdb_load_variable( "user/" . $client_id, "user_name");
							libcue_error_message( "Der Benutzer " . $user_name . " ist am " . $day . ". von " . $timexx[0] . " bis " .
							$timexx[1] . " bereits bei " . $client_name . " eingeteilt!");
							return FALSE;
							}
						}
					}
				}
			}
		}
	return TRUE;
	}
function libcue_mobe_apply_vacation($user_id, $day_begin, $day_end, $year, $month, $accompanist)
	{
	$user_name = libcue_fsdb_load_variable("user/" . $user_id, "user_name");
	for ( $day=$day_begin; $day<=$day_end; $day++ )
		{
		if ( libcue_date_not_in_past($year . $month . libcue_zero('2', $day)) )
			{
			if ( strlen($accompanist['time_worked_add'][$day]) !== 0 && $accompanist['accompanist_roster_sorted'][$day] !== "URLAUB" )
				{
				libcue_error_message("Der Benutzer " . $user_name . " ist am " . $day . ". bereits im Dienst!");
				}
			else
				{
				$vacation = libcue_fsdb_load_variable("vacation/" . $user_id . "/" . $year . "/" . $month . "/" . libcue_zero('2', $day), 'vacation');
				if ( isset($vacation) )
					{
					$variables['vacation'] = "";
					libcue_fsdb_save_variables("vacation/" . $user_id . "/" . $year . "/" . $month . "/" . libcue_zero('2', $day), $variables);
					}
				else
					{
					$variables['vacation'] = "x";
					libcue_fsdb_save_variables("vacation/" . $user_id . "/" . $year . "/" . $month . "/" . libcue_zero('2', $day), $variables);
					}
				}
			}
		}
	}
function libcue_mobe_apply_week_roster($user_id, $week_roster_load, $day_begin, $day_end, $year, $month)
	{
	$weekdays_of_month = libcue_list_weekdays_of_month($month, $year);
	$week_roster = libcue_fsdb_load_variables( "week_roster/" . $user_id . "/" . $week_roster_load);
	$days = libcue_fsdb_load_variables( "month_roster_time/" . $user_id . "/" . $year . $month );
	$days_accompanist = return_days_accompanist($user_id, $year, $month);
	for ( $day=$day_begin; $day<=$day_end; $day++ )
		{
		if ( libcue_date_not_in_past($year . libcue_zero("2",$month) . libcue_zero("2",$day)) )
			{
			for ( $count=1; $count<=4; $count++ )
				{
				if ( $count === 1 || $count === 2 )
					{
					$accompanist_field = 1;
					$first_time = 1; $second_time = 2;
					}
				else
					{
					$accompanist_field = 2;
					$first_time = 3; $second_time = 4;
					}
				if ( ! isset($days_accompanist[$day . "_" . $user_id . "_" . $accompanist_field]) )
					{
					$days[$day . "_" . $user_id . "_time_" . $count] = $week_roster[$weekdays_of_month[$day] .
			  		"_time_" . $count . "_" . $week_roster_load];
					}
				if ( isset($days_accompanist[$day . "_" . $user_id . "_" . $accompanist_field]) &&
					isset($week_roster[$weekdays_of_month[$day] . "_time_" . $count . "_" . $week_roster_load]) )
					{
					if ( libcue_mobe_accompanist_is_free($year, $month, $day, $days_accompanist[$day . "_" .
						$user_id . "_" . $accompanist_field], array($week_roster[$weekdays_of_month[$day]. "_time_" .
						$first_time . "_" . $week_roster_load], $week_roster[$weekdays_of_month[$day] . "_time_" .
						$second_time . "_" . $week_roster_load] ), $user_id, $accompanist_field) )
						{
							$days[$day . "_" . $user_id . "_time_" . $count] = $week_roster[$weekdays_of_month[$day] .
							"_time_" . $count . "_" . $week_roster_load];
						}
					}
				}
			}
		}
	libcue_fsdb_save_variables( "month_roster_time/" . $user_id . "/" . $year . $month, $days );
	}
function libcue_mobe_working_contingent( $year, $month, $user_id, $hours_per_week=FALSE)
	{
	if ( libcue_directory_exists("working_contingent", $user_id) )
		{
		foreach ( glob($GLOBALS['DATABASE_PATH'] . "working_contingent/" . $user_id . "/*") as $each_wc )
			{
			if ( basename($each_wc) <= $year . $month )
				{
				$working_contingent = libcue_fsdb_load_variable("working_contingent/" . $user_id, basename($each_wc));
				}
			}
		}
	else
		{
		return "0";
		}
	if ( $hours_per_week === TRUE )
		{
		return $working_contingent;
		}
	$days = libcue_list_weekdays_of_month( $month, $year );
	$hours_to_work = count($days) * ($working_contingent / 5);
	foreach ( $days as $day => $weekday )
		{
		if ( $weekday == 7 || $weekday == 6 || libcue_date_is_holiday( $year, $month, $day) )
			{
			$hours_to_work = $hours_to_work - ( $working_contingent / 5);
			}
		}
	return $hours_to_work;
	}
function libcue_mobe_client_once($client_id, $pos, $day, $month, $year)
	{
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "client_info/" . $client_id . "/" . $year . $month . $day . "/" . $pos . "*") as $entry )
		{
		$entry = basename($entry);
		$return_string = $return_string .  libcue_html_paragraph(str_replace("\n", "</br>",libcue_fsdb_load_variable("client_info/" . $client_id . "/" .
			$year . $month . $day, $entry)));
		}
	return $return_string;
	}
function libcue_mobe_client_dayly($client_id, $pos)
	{
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "client_info/" . $client_id . "/dayly/" . $pos . "*" ) as $entry )
		{
		$entry = basename($entry);
		$return_string  = $return_string . libcue_html_paragraph(str_replace("\n", "</br>",libcue_fsdb_load_variable("client_info/" . $client_id .
			'/dayly', $entry)));
		}
	return $return_string;
	}
function libcue_mobe_client_weekly($client_id, $pos, $weekday)
	{
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "client_info/" . $client_id . "/" . $weekday . "/" . $pos . "*" ) as $entry )
		{
		$entry = basename($entry);
		$return_string = $return_string . libcue_html_paragraph(str_replace("\n", "</br>",libcue_fsdb_load_variable("client_info/" . $client_id . "/" .
			$weekday, $entry)));
		}
	return $return_string;
	}
function libcue_mobe_client_date($client_id, $pos, $day, $month, $year)
	{
	$time[1] = libcue_fsdb_load_variable("month_roster_time/" . $client_id . "/" . $year . $month, libcue_remove_zero($day) . "_" . $client_id . "_time_1");
	$time[2] = libcue_fsdb_load_variable("month_roster_time/" . $client_id . "/" . $year . $month, libcue_remove_zero($day) . "_" . $client_id . "_time_2");
	$time[3] = libcue_fsdb_load_variable("month_roster_time/" . $client_id . "/" . $year . $month, libcue_remove_zero($day) . "_" . $client_id . "_time_3");
	$time[4] = libcue_fsdb_load_variable("month_roster_time/" . $client_id . "/" . $year . $month, libcue_remove_zero($day) . "_" . $client_id . "_time_4");
	$time[1] = substr($time[1], 0, 2) . substr($time[1], 3, 2); $time[2] = substr($time[2], 0, 2) . substr($time[2], 3, 2);
	$time[3] = substr($time[3], 0, 2) . substr($time[3], 3, 2); $time[4] = substr($time[4], 0, 2) . substr($time[4], 3, 2);
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "date/default/" . $client_id . "/" . $year . "/" . $month . "/" . $year . $month . $day . "*") as $date_id )
		{
		$date = libcue_fsdb_load_variables("date/default/" . $client_id . "/" . $year . "/" . $month . "/" . basename($date_id));
		if ( isset($date['description']) )
			{
			$new_date =  libcue_html_link($date['title'], "?command=date_client&option=view&user=" . $client_id . "&file=" . basename($date_id));
			}
		else
			{
			$new_date = $date['title'];
			}
		$time_begin =  substr($date['time_begin'], 0, 2) . substr($date['time_begin'], 3, 2);
		$cared_for = FALSE;
		if ( ($time[1] <= $time_begin && $time[2] > $time_begin) || ($time[3] <= $time_begin && $time[4] > $time_begin) )
			{
			$cared_for = TRUE;
			}
		if ( ($time[1] <= $time_begin && $time[2] > $time_begin && (int)$pos === 1) || ($time[3] <= $time_begin && $time[4] > $time_begin && (int)$pos === 2) )
			{
			$dates = $dates . $new_date . " " . $date['time_begin'] . "-" .  $date['time_end'] . "</br>";
			}
		if ( ! $cared_for )
			{
			$dates = $dates . "<span style='color: red;'>!!! " . $new_date . " " . $date['time_begin'] . "-" .  $date['time_end'] . " !!!</span></br>";
			}
		}
	return $dates;
	}
function libcue_mobe_client_date_roster($client_id, $day, $month, $year)
	{
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "date/default/" . $client_id . "/" . $year . "/" . $month . "/" . $year . $month . $day . "*") as $date_id )
		{
		$date = libcue_fsdb_load_variables("date/default/" . $client_id . "/" . $year . "/" . $month . "/" . basename($date_id));
		if ( isset($date['description']) )
			{
			$new_date =  libcue_html_link($date['title'], "?command=date_client&option=view&user=" . $client_id . "&file=" . basename($date_id));
			}
		else
			{
			$new_date = $date['title'];
			}
		$dates = $dates . "{" . $new_date . " " . $date['time_begin'] . "-" .  $date['time_end'] . "} ";
		}
	return $dates;
	}
function libcue_mobe_client_once_roster($client_id, $pos, $day, $month, $year)
	{
	if ( $pos === "1" ) { $d = "(FD"; }
	if ( $pos === "2" ) { $d = "(SD"; }
	if ( libcue_fsdb_load_variable("client_info_once/" . $client_id, $year . $month . $day . '_' . $pos) !== NULL )
		{
		return str_replace('\n', ", ",$d . ":" . libcue_fsdb_load_variable("client_info_once/" . $client_id, $year . $month . $day . '_' . $pos) . ")");
		}
	}

#function find_the_right_roster_weekday($week_rosters, $date, $user_id, $weekday)
#	{
#	if ( ! isset($week_rosters) ) { return; }
#	foreach ( $week_rosters as $week_roster )
#		{
#		if ( $week_roster <= $date )
#			{
#			$weekdays_roster = libcue_fsdb_load_variables( "week_roster/" . $user_id . "/" . $week_roster);
#			if ( isset($weekdays_roster[$weekday . $week_roster]) )
#				{
#				return $weekdays_roster[$weekday . $week_roster];
#				}
#			}
#		}
#	}
?>

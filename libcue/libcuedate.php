<?php
function libcue_date_list_dates_collect( $month, $year, $layer="default")
	{
	$month = libcue_zero(2,$month);
	$layer = $layer . "/";
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "date_collect/" . $layer . $year . "/" . $month;
	$options['show_file_name'] = FALSE;
	$variables = array( "title", "date", "time_begin", "time_end", "user_names" );
	$var_search = array( "title", "description" );
	$cell_desc = array( "Titel", "Datum", "Beginn", "Ende", "Benutzer", "Ansehen", "Bearbeiten", "Löschen");
	$action[] = "?command=date&option=view&file=";
	$action[] = "?command=date&file=";
	$action[] = "?command=confirm_delete&delete=date_collect&file=";
	$options['page_action'] = "?command=date&option=list";
	$options['char_index'] = FALSE;
	$dates = libcue_fsdb_list("date_collect/" . $layer . $year . "/" . $month);
	if ( isset($dates[0]) )
		{
		foreach ( $dates as $date )
			{
			$files_dates[] = basename($date);
			}
		}
	return libcue_table_list($files_dates ,$options, $variables, $cell_desc, $action, $var_search);
	}

function libcue_date_list_dates_user($user, $month, $year, $layer="default")
	{
	$month = libcue_zero(2,$month);
	$layer = $layer . "/";
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "date/" . $layer . $user . "/" . $year . "/" . $month;
	$options['show_file_name'] = FALSE;
	$variables = array( "title", "date", "time_begin", "time_end" );
	$var_search = array( "title", "description" );
	$cell_desc = array( "Titel", "Datum", "Beginn", "Ende", "Ansehen", "Bearbeiten", "Löschen");
	$action[] = "?command=date_accompanist&option=view&user=" . $user . "&file=";
	$action[] = "?command=date_accompanist&user=" . $user . "&file=";
	$action[] = "?command=confirm_delete&delete=date&user=" . $user . "&file=";
	$options['page_action'] = "?command=date_accompanist&option=list&user=". $user;
	$options['char_index'] = FALSE;
	$dates = libcue_fsdb_list("date/" . $layer . $user . "/" . $year . "/" . $month);
	if ( isset($dates[0]) )
		{
		foreach ( $dates as $date )
			{
			$files_dates[] = basename($date);
			}
		}
	return libcue_table_list($files_dates ,$options, $variables, $cell_desc, $action, $var_search);
	}
function libcue_dates_list_array($id, $day, $month, $year, $layer="default", $mode="bracket", $value="title")
	{
	if ( $mode === "array") { $dates = array(); }
	$month = libcue_zero(2,$month);
	$day = libcue_zero(2,$day);
	$layer = $layer . "/";
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "date/" . $layer . $id . "/" . $year . "/" . $month . "/" . $year . $month . $day . "*") as $date_id )
		{
		$date = libcue_fsdb_load_variables("date/" . $layer . $id . "/" . $year . "/" . $month . "/" . basename($date_id));
		if ( isset($date['description']) && $mode === "bracket" )
			{
			$new_date =  libcue_html_link($date[$value], "?command=date&option=view&user=" . $id . "&file=" . basename($date_id));
			}
		else
			{
			$new_date = $date[$value];
			}
		if ( libcue_session_validate_key($new_date) )
			{
			$new_date = libcue_fsdb_load_variable("user/" . $new_date, "user_name");
			}
			if ( $mode === "bracket" )
				{
				$dates = $dates . "{" . $date['time_begin'] . "-" .  $date['time_end'] . " " . $new_date . "}, ";
				}
			elseif ( $mode === "array" )
				{
				$dates[] = array( $date['time_begin'], $date['time_end'], $new_date);
				}
			else
				{
				$dates = $dates . $date['time_begin'] . " " .  $date['time_end'] . " " . $new_date . ", ";
				}

		}
	if ( $mode === "array" )
		{
		return $dates;
		}
	else
		{
		return substr($dates, 0, strlen($dates) - 2);
		}
	}
function libcue_date_remove_user_from_date_collect($user_id, $year, $month, $file, $layer="default")
	{
	$month = libcue_zero(2,$month);
	$layer = $layer . "/";
	if ( libcue_directory_exists("date_collect/" . $layer . $year . "/" . $month, $file) )
		{
		$user_name = libcue_fsdb_load_variable("user/" . $user_id, "user_name");
		$date_vars = libcue_fsdb_load_variables("date_collect/" . $layer . $year . "/" . $month . "/" . $file);
		if ( count(explode(",", $date_vars['users'])) <= 2 )
			{
			libcue_fsdb_delete("date_collect/" . $layer . $year . "/" . $month . "/" . $file, $date_vars);
			}
		else
			{
			$date_vars['users'] = implode(",", libcue_remove_from_array($user_id, explode(",", $date_vars['users'])));
			$date_vars['user_names'] = implode(",", libcue_remove_from_array($user_name, explode(",", $date_vars['user_names'])));
			libcue_fsdb_save_variables("date_collect/" . $layer . $year . "/" . $month . "/" . $file, $date_vars);
			}
		}
	}
function libcue_date_save($all_users, $dyear, $dmonth, $layer="default", $conflict_layers="default")
	{
	$layer = $layer . "/";
	if ( ! isset($_REQUEST['user']) && isset($_REQUEST['file']) && libcue_directory_exists("date_collect/" . $layer . $dyear . "/" . $dmonth,
	$_REQUEST['file']) )
		{
		$date_file = $_REQUEST['file'];
		$edit_date = libcue_fsdb_load_variables("date_collect/" . $layer . $dyear . "/" . $dmonth . "/" . $date_file);
		}
	elseif ( isset($_REQUEST['file']) && libcue_directory_exists("date/" . $layer . $all_users[0] . "/" . $dyear . "/" . $dmonth, $_REQUEST['file']) )
		{
		$date_file = $_REQUEST['file'];
		$edit_date = libcue_fsdb_load_variables("date/" . $layer .  $all_users[0] . "/" . $dyear . "/" . $dmonth . "/" . $date_file);
		}
	if ( isset($date_file) )
		{
		if ( libcue_directory_exists("date_collect/" . $layer . $dyear . "/" . $dmonth, $_REQUEST['file']) )
			{
			$is_collect_date = TRUE;
			}
		$edit_day = substr($edit_date['date'], 0, 2);
		$edit_month = substr($edit_date['date'], 3, 2);
		$edit_year = substr($edit_date['date'], 6, 4);
		libcue_date_not_in_past($edit_year . $edit_month . $edit_day);
		}
	if ( ! isset($all_users[0]) )
		{
		libcue_error_message("Kein Benutzer angegeben!");
		}
	if ( strlen($_REQUEST['title']) < 1 )
		{
		libcue_error_message( "Kein Titel angegeben!" );
		}
	libcue_time_pair_is_valid( $_REQUEST['time_begin'], $_REQUEST['time_end'] );
	if ( strlen($_REQUEST['date']) === 10 )
		{
		$day = substr($_REQUEST['date'], 0, 2);
		$month = substr($_REQUEST['date'], 3, 2);
		$year = substr($_REQUEST['date'], 6, 4);
		$begin_hour = substr($_REQUEST['time_begin'], 0, 2);
		$begin_minute = substr($_REQUEST['time_begin'], 3, 2);
		libcue_date_not_in_past($year . $month . $day);
		if ( ! checkdate($month, $day, $year ) )
			{
			libcue_error_message("Das Datum " . $_REQUEST['date'] . " ist nicht gültig!");
			}
		}
	else
		{
		libcue_error_message( "Kein Datum angegeben!" );
		}
	if ( ! libcue_error_occurred() )
		{
		if ( isset($date_file) )
			{
			if ( isset($edit_date['users']) )
				{
				$edit_users = explode(",", $edit_date['users']);
				libcue_fsdb_delete("date_collect/" . $layer . $edit_year . "/" . $edit_month . "/" . $date_file);
				}
			else
				{
				$edit_users[0] = $all_users[0];
				}
			foreach ( $edit_users as $each_user_id )
				{
				libcue_fsdb_delete("date/" . $layer .  $each_user_id . "/" . $edit_year . "/" . $edit_month . "/" . $date_file);
				}
			}
		$var_date['year'] = $year; $var_date['month'] = $month; $var_date['day'] = $day;
		$var_date['time_begin'] = $_REQUEST['time_begin'];
		$var_date['time_end'] = $_REQUEST['time_end'];
		$var_date['title'] = $_REQUEST['title'];
		$var_date['description'] = $_REQUEST['description'];
		$var_date['date'] = $_REQUEST['date'];
		$date_id = libcue_session_key("dates_collect/" . $layer . $year . "/" . $month, $year . $month . $day . $begin_hour . $begin_minute);
		$var_date['date_id'] = $date_id;
		foreach ( $all_users as $id )
			{
			$user_name = libcue_fsdb_load_variable("user/" . $id, "user_name");
			$times[0] = $var_date['time_begin']; $times[1] = $var_date['time_end'];
			foreach ( explode(" ", $conflict_layers) as $cl )
				{
				libcue_date_user_is_free($year, $month, $day, $id, $times, $cl);
				}
			}
		if ( ! libcue_error_occurred() )
			{
			foreach ( $all_users as $id )
				{
				$user_name = libcue_fsdb_load_variable("user/" . $id, "user_name");
				libcue_fsdb_save_variables( "date/" . $layer . $id . "/" . $year . "/" . $month . "/" . $date_id, $var_date);
				$users = $users . libcue_html_paragraph( "Setze Termin für: " . $user_name );
				$user_names[] = $user_name;
				}
			if ( ! isset($all_users[1]) && $is_collect_date )
				{
				libcue_date_remove_user_from_date_collect($all_users[0], $dyear, $dmonth, $_REQUEST['file']);
				}
			if ( isset($all_users[1]) )
				{
				$var_date['users'] = implode(",", $all_users);
				$var_date['user_names'] = implode(",", $user_names);
				libcue_fsdb_save_variables("date_collect/" . $year . "/" . $month . "/" . $date_id, $var_date);
				}
			unset($_REQUEST['users_selected']);
			}
		else
			{
			if ( isset($date_file) )
				{
				if ( isset($edit_date['users']) )
					{
					libcue_fsdb_save_variables("date_collect/" . $layer .  $edit_year . "/" . $edit_month . "/" . $date_file, $edit_date);
					}
				unset($edit_date['users']); unset($edit_date['user_names']);
				foreach ( $edit_users as $each_user_id )
					{
					libcue_fsdb_save_variables("date/" . $layer .  $id . "/" . $edit_year . "/" . $edit_month . "/" . $date_file, $edit_date);
					}
				}
			}
		}
	}

function date_view($file, $year, $month, $id=NULL, $layer="default")
	{
	$layer = $layer . "/";
	if ( $id !== NULL )
		{
		$date = libcue_fsdb_load_variables("date/" . $layer . libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id") . "/" . $year .
			"/" . $month . "/" . $file);
		}
	else
		{

		$date = libcue_fsdb_load_variables("date_collect/" . $layer . $year . "/" . $month . "/" . $file);
		}
	return libcue_html_headline($date['title']) . libcue_html_paragraph($date['time_begin'] . "-" . $date['time_end']) .
		libcue_html_paragraph($date['description']);
	}
function date_form($id, $file, $dyear, $dmonth, $role, $layer="default")
	{
	$layer = $layer . "/";
	if ( isset($file) )
		{
		if ( libcue_directory_exists("date_collect/" . $layer . $dyear . "/" . $dmonth, $file) && ! isset($id[0]) )
			{
			$options['file'] = "date_collect/" . $layer . $dyear . "/" . $dmonth . "/" . $file;
			$file_get = "&file=" . $file;
			$id = explode(",", libcue_fsdb_load_variable($options['file'], 'users'));
			}
		elseif ( isset($id[0]) && libcue_directory_exists("date/" . $layer . $id[0] . "/" . $dyear . "/" . $dmonth, $file) )
			{
			$options['file'] = "date/" . $layer . $id[0] . "/" . $dyear . "/" . $dmonth . "/" . $file;
			$file_get = "&file=" . $file;
			}
		}
	if ( isset($id[0]) )
		{
		foreach( $id as $user )
			{
			$users[] = libcue_fsdb_load_variable("user/" . $user, "user_name");
			}
		$options['var_content']['users_selected'] = implode(",", $users);
		}
	$variables = array("users_selected", "title", "description", "date", "time_begin", "time_end");
	$options["label"] = "Termin festlegen";
	if ( $role === "klient" )
		{
		$options['action'] = $REMOTE_HTTP_PATH . "?command=date_client&option=save_date&user=" . $id[0] . $file_get;
		}
	elseif ( $role === "begleiter" && isset($id[0]) && ! isset($id[1]) )
		{
		$options['action'] = $REMOTE_HTTP_PATH . "?command=date_accompanist&option=save_date&user=" . $id[0] . $file_get;
		}
	elseif ( $role === "begleiter" )
		{
		$options['action'] = $REMOTE_HTTP_PATH . "?command=date_accompanist&option=save_date" . $file_get;
		}
	$var_mode['users_selected'] = "text"; $var_mode['title'] = "text"; $var_mode["description"] = "textareas"; $var_mode["date"] = "text";
	$var_mode["time_begin"] = "text"; $var_mode["time_end"] = "text";
	$var_desc['users_selected'] = "Benutzer"; $var_desc['title'] = "Titel"; $var_desc["description"] = "Beschreibung"; $var_desc["date"] = "Datum [DD.MM.YYYY]";
	$var_desc["time_begin"] = "Termin Anfang [HH:MM]"; $var_desc["time_end"] = "Termin Ende [HH:MM]";
	$maxlength['users_selected'] = "48"; $maxlength['title'] = "160"; $maxlength["date"] = 10; $maxlength["time_begin"] = 5; $maxlength["time_end"] = 5;
	return  libcue_html_headline("Termin festlegen") . libcue_draw_form($options, $variables, $var_desc, $var_mode, $maxlength);
	}

function libcue_time_add( $time1, $time2 )
	{
	$time1 = trim($time1); $time2 = trim($time2);
	$hour1 = substr($time1, "0", "2");
	$hour2 = substr($time2, "0", "2");
	$minute1 = substr($time1, "3", "2");
	$minute2 = substr($time2, "3", "2");
	$hours = $hour1 + $hour2;
	$minutes = $minute1 + $minute2;
	if ( $minutes >= 60 )
		{
		$minutes = $minutes - 60;
		$hours++;
		}
	return libcue_zero("2", $hours) . ":" . libcue_zero("2", $minutes);
	}

function libcue_time_difference( $begin, $end )
	{
	$begin = trim($begin); $end = trim($end);
	$beginh = substr($begin, "0", "2");
	$endh = substr($end, "0", "2");
	$beginm = substr($begin, "3", "2");
	$endm = substr($end, "3", "2");
	$hours = $endh - $beginh;
	if ( $endm < $beginm )
		{
		$endm = $endm + 60;
		$hours--;
		}
	$minutes = $endm - $beginm;
	return libcue_zero("2", $hours) . ":" . libcue_zero("2", $minutes);
	}

#function libcue_mark_sundays($weekday)
#	{
#	if ( $weekday == "Sun" )
#		{
#		return "<b><font color='red'>" . $weekday . "</font></b>";
#		}
#	return $weekday;
#	}

function libcue_list_days_of_month ( $month, $year )
	{
	$day = 1;
	while ( checkdate( $month, $day, $year) )
		{
		$dates[$day] = $day;
		$day = $day + 1;
		}
	return $dates;
	}


function libcue_list_weekdays_of_month ( $month, $year )
	{
	$day = 1;
	while ( checkdate( $month, $day, $year) )
		{
		$weekdays[$day] = strftime( "%u", mktime(0, 0, 0, $month, $day, $year) );
		$day = $day + 1;
		}
	return $weekdays;
	}
function human_weekday_long($weekday)
	{
	$wd = array( "", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag");
	return $wd[$weekday];
	}
function human_weekday($weekday)
	{
	$wd = array( "", "Mon", "Die", "Mit", "Don", "Fre", "Sam", "Son");
	return $wd[$weekday];
	}
function libcue_human_list_days_of_month ( $month, $year)
	{
	$day = 1;
	while ( checkdate( $month, $day, $year) )
		{
		$holiday = "";
		$weekday = strftime( "%u", mktime( 0, 0, 0, $month, $day, $year ) );
		if ( libcue_date_is_holiday($year, $month, $day) && $weekday != 7)
			{
			$holiday = "(F)";
			}
		$weekday = human_weekday($weekday);
		$dates[$day] = $weekday . " " . $day . " " . $holiday;
		$day = $day + 1;
		}
	return $dates;
	}

function libcue_date_not_in_past($date)
	{
	$today = date( "Ymd", time());
	if ( $date < $today )
		{
		libcue_error_message("Das Datum: " . $date . " liegt in der Vergangenheit!");
		return FALSE;
		}
	return TRUE;
	}
function libcue_date_is_holiday( $year, $month, $day)
	{
	$easter_sunday = easter_date("2014")+9600;
	$eastern[0] = $easter_sunday + 86400; # easter monday
	$eastern[1] = $easter_sunday + 86400 * 39; # ascension day
	$eastern[2] = $easter_sunday + 86400 * 50; # whit monday
	$eastern[3] = $easter_sunday + 86400 * 60; # corpus christi
	foreach ( $eastern as $easter )
		{
		$holidays[] = date("Ymd", $easter);
		}
	$holidays[] = $year . "0101";
	$holidays[] = $year . "0106";
	$holidays[] = $year . "0501";
	$holidays[] = $year . "0815";
	$holidays[] = $year . "1026";
	$holidays[] = $year . "1101";
	$holidays[] = $year . "1208";
	$holidays[] = $year . "1224"; # SONDERURLAUB
	$holidays[] = $year . "1225";
	$holidays[] = $year . "1226";
	$holidays[] = $year . "1231"; # SONDERURLAUB
	foreach ( $holidays as $holiday )
		{
		if ( $year . $month . libcue_zero( "2",$day) == $holiday )
			{
			return TRUE;
			}
		}
	return FALSE;
	}
function libcue_time_pair_is_valid($time1, $time2)
	{
	if ( strlen($time1) != 5 || strlen($time2) != 5 )
		{
		libcue_error_message( "Die Zeiten: " . $time1 . " " . $time2 . " sind ungültig!" );
		return FALSE;
		}
	if ( ! is_numeric(substr($time1, "0", "2") . substr($time1, "3", "2")) ||
		! is_numeric(substr($time2, "0", "2") . substr($time2, "3", "2")) )
		{
		libcue_error_message( "Die Zeiten: " . $time1 . " " . $time2 . " enthalten ungültige Zeichen!" );
		return FALSE;
		}
	if ( substr($time1, "0", "2") < 0 || substr($time1, "0", "2") > 24 || substr($time1, "3", "2") < 0 || substr($time1, "3", "2") > 60 ||
		substr($time2, "0", "2") < 0 || substr($time2, "0", "2") > 24 || substr($time2, "3", "2") < 0 || substr($time2, "3", "2") > 60 )
		{
		libcue_error_message( "Die Zeiten: " . $time1 . " " . $time2 . " sind ungültig!" );
		return FALSE;
		}
	if ( substr($time1, "0", "2") . substr($time1, "3", "2") >=  substr($time2, "0", "2") . substr($time2, "3", "2") )
		{
		libcue_error_message( "Die Zeit: " . $time2 . " muss größer sein als: " . $time1 . "!" );
		return FALSE;
		}
	return TRUE;
	}

function libcue_date_user_is_free($year, $month, $day, $user_id, $times, $layer="default", $error=TRUE)
	{
	$layer = $layer . "/";
	if ( ! libcue_directory_exists( "date/" . $layer . $user_id . "/" . $year, $month) )
		{
		return TRUE;
		}
	foreach ( $times as $count => $time )
		{
		$time = trim($time);
		$timex[$count] = substr($time, "0","2") . substr($time, "3", "2");
		}
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "date/" . $layer . $user_id . "/" . $year . "/" . $month . "/" . $year . $month . $day . "*") as $date_id )
		{
		$date = libcue_fsdb_load_variables("date/" . $layer . $user_id . "/" . $year . "/" . $month . "/" . basename($date_id));
		$times[0] = $date['time_begin']; $times[1] = $date['time_end'];
		foreach ( $times as $count => $time )
			{
			$time = trim($time);
			$timexx[$count] = substr($time, "0","2") . substr($time, "3", "2");
			}
		$earlier = 0; $later = 0;
		if ( $timexx[0] < $timex[0] && $timexx[1] <= $timex[0] ) { $earlier = 1; }
		if ( $timexx[0] >= $timex[1] && $timexx[1] > $timex[1] ) { $later = 1; }
		if ( $earlier === 0  && $later === 0 )
			{
			$date_desc = libcue_fsdb_load_variable( "date/" . $layer . $user_id . "/" . $year . "/" . $month . "/" . basename($date_id),
			"title");
			$user_name = libcue_fsdb_load_variable( "user/" . $user_id, 'user_name');
			if ( $error === TRUE )
				{
				libcue_error_message( "Der Benutzer " . $user_name . " ist bereits am " . $day . ". von " . $timexx[0] . " bis " . $timexx[1] .
				" für " . $date_desc . " eingeteilt!");
				}
			return FALSE;
			}
		}
	return TRUE;
	}

?>

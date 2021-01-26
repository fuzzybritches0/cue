<?php
if ( libcue_directory_exists( "user", $user_id) && libcue_user_is_active($user_id) )
	{
	$user_name = libcue_fsdb_load_variable( "user/" . $user_id, "user_name");
	$posts[] = libcue_html_headline("Wochentafeln für: " . $user_name);
	$variables[0] = array( "Name", "Mon", "Die", "Mit", "Don", "Fre", "Sam", "Son" );
	$headers = array( "", "Zeiten");
	foreach( glob($GLOBALS['DATABASE_PATH'] . "week_roster/" . $user_id . "/*") as $roster )
		{
		$roster = basename($roster);
		$variablesl = libcue_fsdb_load_variables("week_roster/" . $user_id . "/" . $roster);
		$variables[1]['name'] = libcue_form_text('name', "20", $roster);
		for ( $weekday=1; $weekday <= 7; $weekday++ )
			{
			for ( $count = 1; $count <= 4; $count++ )
				{
				$cell = $cell . libcue_form_text($weekday . "_time_" . $count . "_" . $roster, "5",
				 $variablesl[$weekday . "_time_" . $count . "_" . $roster]);
				}
			$variables[1][] = $cell;
			unset($cell);
			}
		$posts[] = libcue_form_conclude_input(libcue_table($variables, $headers),  "?command=save_week_roster&user=" .
			$user_id . "&nameg=" . basename($roster));
		unset($variables[1]);
		}
	$variables[1][] = libcue_form_text("name", "20");
	for ( $weekday=1; $weekday <= 7; $weekday++)
		{
		for ( $i=1; $i <= 4; $i++ )
			{
			$cell = $cell . libcue_form_text($weekday . "_time_" . $i . "_", "5", $_REQUEST[$var . "_" . $i . "_"]);
			}
		$variables[1][] = $cell;
		unset($cell);
		}
	$posts[] = libcue_form_conclude_input( libcue_table($variables, $headers), "?command=save_week_roster&user=" . $user_id );
	}
else
	{
	$posts[] = $_REQUEST['user'] . " does not exist!";
	}

?>

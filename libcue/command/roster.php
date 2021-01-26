<?php
if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') ||
			libcue_session_user_is($_SESSION['user_id'], 'role', 'begleiter') )
	{
	$current_page = "schedule";
	if ( libcue_directory_exists("user", $_REQUEST['user']) )
		{
		$user = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_name");
		$user_id = libcue_session_return_user_id($user);
		if ( isset($_REQUEST['month']) && isset($_REQUEST['year']) && libcue_mobe_is_valid_date($_REQUEST['year'] . $_REQUEST['month'] . "01") )
			{
			$month = $_REQUEST['month']; $year = $_REQUEST['year'];
			}
		else
			{
			$month = date("m", time()); $year = date("Y", time());
			}
		$_SESSION['schedule.month' . $GLOBALS['windowid']] = $month; $_SESSION['schedule.year' . $GLOBALS['windowid']] = $year;
		$sidebar_page_action = "?command=roster&user=" . $user_id;
		require $LIBCUE_PATH . "sidebar/schedule.php";
		$final_result = TRUE;
		$GLOBALS['page_title'] = $display_users = $user . " " . $month . "-" . $year;
		if ( $_REQUEST['option'] === "save" )
			{
			libcue_mobe_roster_input_save($user_id, $year, $month);
			}
		$rv = libcue_mobe_roster_input($user, $year, $month);
		$content = libcue_html_paragraph(libcue_html_link("Als PDF-Datei herunterladen", $GLOBALS[$REMOTE_HTTP_PATH] ."?command=print_view&user=" .
			$user . "&year=" . $year . "&month=" . $month));
		$content = $content . libcue_form_conclude_input( libcue_table($rv['variables'], $rv['headers']), "?command=roster&user=" .
			$user . "&year=" . $year . "&month=" . $month . "&option=save");
		$posts[] = libcue_html_headline( "Übersicht: " .  $user ) . $content;
		}
	else
		{
		libcue_error_message("Der Benutzer:" . $_REQUEST['user'] . " existiert nicht!");
		}
	}
else
	{
	libcue_error_message("Zugriff verweigert! Du hast nicht die nötigen Rechte!");
	}
?>

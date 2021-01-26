<?php
if ( ! isset($_REQUEST['layer']) ) $layer = "default";
else $layer = $_REQUEST['layer'];
$layer = $layer . "/";
if ( $_REQUEST['delete'] === "date_collect" )
	{
	$current_page = "schedule";
	$sidebar_page_action = "?command=schedule";
	require $LIBCUE_PATH . "sidebar/schedule.php";
	if ( libcue_directory_exists("date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" . libcue_session_get_var('schedule.month'),
		$_REQUEST['file']) )
		{
		$date_vars = libcue_fsdb_load_variables("date_collect/" . $layer . libcue_session_get_var('schedule.year') . "/" .
		libcue_session_get_var('schedule.month') .
			"/" . $_REQUEST['file']);
		$posts[] = libcue_html_paragraph("Bist du sicher, dass du den Termin '" . $date_vars['description'] . "' löschen möchtest?") .
			libcue_html_paragraph(libcue_html_link("JA, TERMIN LÖSCHEN!!!", $REMOTE_HTTP_PATH . "?command=delete&delete=date_collect&file=" .
			$_REQUEST['file']));
		}
	}
if ( $_REQUEST['delete'] === "date" && isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']))
	{
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	$current_page = "schedule";
	$sidebar_page_action = "?command=schedule";
	require $LIBCUE_PATH . "sidebar/schedule.php";
	if ( libcue_directory_exists("date/" . $layer . $user_id . "/" . libcue_session_get_var('schedule.year') . "/" .
	libcue_session_get_var('schedule.month'),
		$_REQUEST['file']) )
		{
		$date_vars = libcue_fsdb_load_variables("date/" . $layer . $user_id . "/" . libcue_session_get_var('schedule.year') . "/" .
			libcue_session_get_var('schedule.month') . "/" . $_REQUEST['file']);
		$posts[] = libcue_html_paragraph("Bist du sicher, dass du den Termin '" . $date_vars['description'] . "' löschen möchtest?") .
			libcue_html_paragraph(libcue_html_link("JA, TERMIN LÖSCHEN!!!", $REMOTE_HTTP_PATH . "?command=delete&delete=date&file=" .
			$_REQUEST['file'] . "&user=" . $user_id));
		}
	}
if ( $_REQUEST['delete'] === "date_client" && isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) &&
	libcue_user_is_active($_REQUEST['user']))
	{
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	if ( libcue_directory_exists("date/" . $layer . $user_id . "/" . libcue_session_get_var('today.year') . "/" . libcue_session_get_var('today.month'),
		$_REQUEST['file']) )
		{
		$date_vars = libcue_fsdb_load_variables("date/" . $layer . $user_id . "/" . libcue_session_get_var('today.year') . "/" .
			libcue_session_get_var('today.month') . "/" . $_REQUEST['file']);
		$posts[] = libcue_html_paragraph("Bist du sicher, dass du den Termin '" . $date_vars['description'] . "' löschen möchtest?") .
			libcue_html_paragraph(libcue_html_link("JA, TERMIN LÖSCHEN!!!", $REMOTE_HTTP_PATH . "?command=delete&delete=date_client&file=" .
			$_REQUEST['file'] . "&user=" . $user_id));
		}
	}
?>

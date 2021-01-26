<?php
$monthx = date("m", time());
$yearx = date("Y", time());
$get_year = $_REQUEST['year']; $get_month = $_REQUEST['month'];
if ( isset($_REQUEST['group']) ) { libcue_session_put_var('schedule.group', $_REQUEST['group']); }
if ( isset($_REQUEST['role']) ) { libcue_session_put_var('schedule.role', $_REQUEST['role']); }

if ( isset($_REQUEST['year']) ) { libcue_session_put_var('schedule.year', $_REQUEST['year']); }
if ( isset($_REQUEST['month']) ) { libcue_session_put_var('schedule.month', $_REQUEST['month']); }

if ( ! isset($_REQUEST['role']) && libcue_session_get_var('schedule.role') === null ) { libcue_session_put_var('schedule.role', "klient"); }
if ( ! isset($_REQUEST['year']) && libcue_session_get_var('schedule.year') === null ) { libcue_session_put_var('schedule.year', date("Y", time())); }
if ( ! isset($_REQUEST['month']) && libcue_session_get_var('schedule.month') === null ) { libcue_session_put_var('schedule.month', date("m", time())); }
if ( strlen($_REQUEST['year']) < 4 ) { $get_year = libcue_session_get_var('schedule.year'); }
if ( strlen($_REQUEST['month']) < 2 )  { $get_month = libcue_session_get_var('schedule.month'); }

for ( $a=0; $a<=2; $a++)
	{
	$monthx = libcue_zero( "2", $monthx);
	if ( $get_year == $yearx && $get_month == $monthx )
		{
		$month_list = $month_list . libcue_html_link( $yearx . "-" . $monthx, $REMOTE_HTTP_PATH . $sidebar_page_action .
		"&year=". $yearx . "&month=" . $monthx, "_parent", "id='selected'") . "<br>";
		}
	else
		{
		$month_list = $month_list . libcue_html_link( $yearx . "-" . $monthx, $REMOTE_HTTP_PATH . $sidebar_page_action . "&year=". $yearx .
		"&month=" . $monthx) . "<br>";
		}
	$monthx++;
	if ( $monthx == "13" )
		{
		$monthx = "1";
		$yearx++;
		}
	}
$sidebar_sections[] = libcue_html_headline("Monat") . libcue_html_paragraph($month_list);

$get_role = $_REQUEST['role'];
if ( ! isset($get_role) ) { $get_role = libcue_session_get_var('schedule.role'); }
if ( $get_role === "klient" )
	{
	$rr = $rr . libcue_html_link(" Klienten ", "./?command=schedule&role=klient", "_parent", "id='selected'");
	}
else
	{
	$rr = $rr . libcue_html_link(" Klienten ", "./?command=schedule&role=klient");
	}
if ( $get_role === "begleiter" )
	{
	$rr = $rr . libcue_html_link(" Begleiter ", "./?command=schedule&role=begleiter", "_parent", "id='selected'");
	}
else
	{
	$rr = $rr . libcue_html_link(" Begleiter ", "./?command=schedule&role=begleiter");
	}
$sidebar_sections[] = libcue_html_headline("Rollen") . libcue_html_paragraph($rr);

$get_group = $_REQUEST['group'];
if ( ! isset($get_group) ) { $get_group = libcue_session_get_var('schedule.group'); }

if ( $get_group === "" || ! isset($get_group) )
	{
	$gg = $gg . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=schedule&group=", "_parent", "id='selected'");
	}
else
	{
	$gg = $gg . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=schedule&group=");
	}
$groups =  libcue_session_gather("group");

if ( isset($groups) )
	{
	foreach ( $groups as $group )
		{
		if ( $group === $get_group )
			{
			$gg = $gg . libcue_html_link(" " . $group . " ", $REMOTE_HTTP_PATH . "?command=schedule&group=" . $group, "_parent", "id='selected'");
			}
		else
			{
			$gg = $gg . libcue_html_link(" " . $group . " ", $REMOTE_HTTP_PATH . "?command=schedule&group=" . $group);
			}
		}
	}
$files = libcue_fsdb_list("user_name", "role,group", $get_role . "," . $get_group);
if ( isset($files[0]) )
	{
	foreach ( $files as $file )
		{
		if ( libcue_user_is_active($file, "no") )
			{
			$users_group[] = basename($file);
			}
		}
		if ( isset($users_group) ) {
			foreach ( $users_group as $userg )
				{
				$users_sb = $users_sb . libcue_html_link( " " . $userg . " ",
				"?command=roster&year=" . libcue_session_get_var('schedule.year') .
				"&month=" . libcue_session_get_var('schedule.month') . "&user=" . $userg);
				if ( libcue_session_get_var('schedule.role') === "begleiter")
					{
					$users_ac = $users_ac . libcue_html_link( " " . $userg . " ",
					"?command=roster_accompanist&year=" . libcue_session_get_var('schedule.year') .
					"&month=" . libcue_session_get_var('schedule.month') . "&user=" . $userg);
					}
				}
			}
	}
$sidebar_sections[] = libcue_html_headline("Gruppen") . libcue_html_paragraph($gg);
if ( isset($users_sb) ) { $sidebar_sections[] = libcue_html_headline("Begleitübersichten") . libcue_html_paragraph($users_sb); }
if ( isset($users_ac) ) { $sidebar_sections[] = libcue_html_headline("Monatsberichte") . libcue_html_paragraph($users_ac); }
if ( isset($users_wr) ) { $sidebar_sections[] = libcue_html_headline("Wochentafeln") . libcue_html_paragraph($users_wr); }
if ( strlen($get_group) > 0 && isset($users_group) )
	{
	$sidebar_sections[] = libcue_html_link("Sammel-PDF-Datei herunterladen", $GLOBALS[$REMOTE_HTTP_PATH] .
	"?command=print_view&user=" . implode(",", $users_group) . "&year=" . libcue_session_get_var('schedule.year') . "&month=" .
	libcue_session_get_var('schedule.month'));
	}
if ( libcue_session_get_var('schedule.role') === "begleiter" )
	{
	$sidebar_sections[] = libcue_html_headline("Termine") . libcue_html_link( "Sammeltermine verwalten", $REMOTE_HTTP_PATH .
		"?command=date_accompanist&option=list");
	if ( isset($_REQUEST['user']) && libcue_directory_exists("user", $_REQUEST['user']) && libcue_user_is_active($_REQUEST['user']) )
		{
		$user_name = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_name");
		$sidebar_sections[] = libcue_html_paragraph(libcue_html_link($user_name . "'s Termine verwalten", $REMOTE_HTTP_PATH .
			"?command=date_accompanist&option=list&user=" . $_REQUEST['user']));
		}
	}
?>

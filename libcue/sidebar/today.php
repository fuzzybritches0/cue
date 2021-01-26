<?php
$monthx = date("m", time());
$yearx = date("Y", time());
$get_year = $_REQUEST['year']; $get_month = $_REQUEST['month'];
if ( isset($_REQUEST['group']) ) { libcue_session_put_var('today.group', $_REQUEST['group']); }
if ( isset($_REQUEST['role']) ) { libcue_session_put_var('today.role', $_REQUEST['role']); }

if ( isset($_REQUEST['year']) ) { libcue_session_put_var('today.year', $_REQUEST['year']); }
if ( isset($_REQUEST['month']) ) { libcue_session_put_var('today.month', $_REQUEST['month']); }

if ( ! isset($_REQUEST['role']) && libcue_session_get_var('today.role') === null ) { libcue_session_put_var('today.role', "klient"); }
if ( ! isset($_REQUEST['year']) && libcue_session_get_var('today.year') === null ) { libcue_session_put_var('today.year', date("Y", time())); }
if ( ! isset($_REQUEST['month']) && libcue_session_get_var('today.month') === null ) { libcue_session_put_var('today.month', date("m", time())); }
if ( strlen($_REQUEST['year']) < 4 ) { $get_year = libcue_session_get_var('today.year'); }
if ( strlen($_REQUEST['month']) < 2 )  { $get_month = libcue_session_get_var('today.month'); }
if ( libcue_session_user_is($client_id, "role", "klient") )
	{
	$sidebar_sections[] = libcue_html_headline($client_name) . libcue_html_link("Termine verwalten", $REMOTE_HTTP_PATH .
			"?command=date_client&user=" . $client_id) . libcue_html_br() .
	libcue_html_link("Infos täglich", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=dayly&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos montags", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=1&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos dienstags", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=2&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos mittwochs", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=3&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos donnerstags", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=4&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos freitags", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=5&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos samstags", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=6&mode=weekly") . libcue_html_br() .
	libcue_html_link("Infos sonntags", $REMOTE_HTTP_PATH . "?command=client_info&client=" . $client_id . "&day=7&mode=weekly");
	}
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
if ( ! isset($get_role) ) { $get_role = libcue_session_get_var('today.role'); }
if ( $get_role === "klient" )
	{
	$rr = $rr . libcue_html_link(" Klienten ", "./?command=today&role=klient", "_parent", "id='selected'");
	}
else
	{
	$rr = $rr . libcue_html_link(" Klienten ", "./?command=today&role=klient");
	}
if ( $get_role === "begleiter" )
	{
	$rr = $rr . libcue_html_link(" Begleiter ", "./?command=today&role=begleiter", "_parent", "id='selected'");
	}
else
	{
	$rr = $rr . libcue_html_link(" Begleiter ", "./?command=today&role=begleiter");
	}
$sidebar_sections[] = libcue_html_headline("Rollen") . libcue_html_paragraph($rr);

$get_group = $_REQUEST['group'];
if ( ! isset($get_group) ) { $get_group = libcue_session_get_var('today.group'); }

if ( $get_group === "" || ! isset($get_group) )
	{
	$gg = $gg . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=today&group=", "_parent", "id='selected'");
	}
else
	{
	$gg = $gg . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=today&group=");
	}
$groups =  libcue_session_gather("group");

if ( isset($groups) )
	{
	foreach ( $groups as $group )
		{
		if ( $group === $get_group )
			{
			$gg = $gg . libcue_html_link(" " . $group . " ", $REMOTE_HTTP_PATH . "?command=today&group=" . $group, "_parent", "id='selected'");
			}
		else
			{
			$gg = $gg . libcue_html_link(" " . $group . " ", $REMOTE_HTTP_PATH . "?command=today&group=" . $group);
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
		foreach ( $users_group as $user )
			{
			if ( $get_role === "klient" )
				{
				$client_accompanist = "Klienten";
				$users_sb = $users_sb . libcue_html_link( " " . $user . " ", "?command=client_overview&year=" .
		  		libcue_session_get_var('today.year') . "&month=" . libcue_session_get_var('today.month') . "&user=" . $user);
				}
			if ( $get_role === "begleiter" ) 
				{
				$client_accompanist = "Begleiter";
				$users_sb = $users_sb . libcue_html_link( " " . $user . " ",
				"?command=accompanist_overview&year=" . libcue_session_get_var('today.year') .
				"&month=" . libcue_session_get_var('today.month') . "&user=" . $user);
				}
				
			}
	}
$sidebar_sections[] = libcue_html_headline("Gruppen") . libcue_html_paragraph($gg);
if ( isset($users_sb) ) { $sidebar_sections[] = libcue_html_headline($client_accompanist) . libcue_html_paragraph($users_sb); }
?>

<?php
if ( strlen($_REQUEST['role']) === 0 )
	{
	$_SESSION['user.role'] = $_REQUEST['role'];
	}
if ( $_SESSION['user.role'] === "" || ! isset($_SESSION['user.role']) )
	{
	$rr = $rr . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=user&option=list&role=", "_parent", "id='selected'");
	}
else
	{
	$rr = $rr . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=user&option=list&role=");
	}
$roles =  libcue_session_gather("role");

if ( isset($roles) )
	{
	foreach ( $roles as $role )
		{
		if ( $role === $_SESSION['user.role'] )
			{
			$rr = $rr . libcue_html_link(" " . $role, $REMOTE_HTTP_PATH . "?command=user&option=list&role=" . $role, "_parent", "id='selected'");
			}
		else
			{
			$rr = $rr . libcue_html_link(" " . $role . " ", $REMOTE_HTTP_PATH . "?command=user&option=list&role=" . $role);
			}
		}
	}
$sidebar_sections[] = libcue_html_headline("Rollen") . libcue_html_paragraph($rr);

if ( strlen($_REQUEST['group']) === 0 )
	{
	$_SESSION['user.group'] = $_REQUEST['group'];
	}
if ( $_SESSION['user.group'] === "" || ! isset($_SESSION['user.group']) )
	{
	$gg = $gg . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=user&option=list&group=", "_parent", "id='selected'");
	}
else
	{
	$gg = $gg . libcue_html_link(" Alle ", $REMOTE_HTTP_PATH . "?command=user&option=list&group=");
	}
$groups =  libcue_session_gather("group");

if ( isset($groups) )
	{
	foreach ( $groups as $group )
		{
		if ( $group === $_SESSION['user.group'] )
			{
			$gg = $gg . libcue_html_link(" " . $group, $REMOTE_HTTP_PATH . "?command=user&option=list&group=" . $group, "_parent", "id='selected'");
			}
		else
			{
			$gg = $gg . libcue_html_link(" " . $group . " ", $REMOTE_HTTP_PATH . "?command=user&option=list&group=" . $group);
			}
		}
	}
$sidebar_sections[] = libcue_html_headline("Gruppen") . libcue_html_paragraph($gg);
?>

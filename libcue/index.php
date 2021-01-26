<?php
ini_set('session.gc_maxlifetime', 900);
session_start();
date_default_timezone_set('Europe/Berlin');
$PROG_VERSION = "Alpha 54";
$GLOBALS['PROG_NAME'] = "C:UE " . $PROG_VERSION;
$GLOBALS['PROG_DESC'] = "Communication's Useful Environment";
$GLOBALS['DEFAULT_ROLES'] = array("begleiter");
$GLOBALS['BROWSERID_LIMIT'] = "5000";
$GLOBALS['FAIL_GLOBAL_TRESHOLD'] = "15";
$GLOBALS['FAIL_LOGIN_TRESHOLD'] = "20";
$GLOBALS['FAIL_LOGIN_BLOCK_TIME'] = "900";
$GLOBALS['NOTIFY_ADMIN_ONCE_EVERY_SECONDS'] = "1800";
$GLOBALS['SESSION_ID_TIME_MAX'] = 60 * 60 * 24 * 2;
$GLOBALS['BROWSER_ID_TIME_MAX'] = 60 * 60 * 24 * 30;

# PRODUCTION SETTINGS
$GLOBALS['REMOTE_HTTP_PATH'] = $REMOTE_HTTP_PATH = "http://example.co.com/cue/";
$GLOBALS['DATABASE_PATH'] = "/home/fsdb/";
$GLOBALS['LIBCUE_PATH'] = $LIBCUE_PATH = "/lib/cue/libcue/";
# DEVELOPMENT SETTINGS
$GLOBALS['REMOTE_HTTP_PATH'] = $REMOTE_HTTP_PATH = "http://example.co.com/cue/";
$GLOBALS['DATABASE_PATH'] = "/home/fsdb/";
$GLOBALS['LIBCUE_PATH'] = $LIBCUE_PATH = "/lib/cue/libcue/";

require $LIBCUE_PATH . "libcue.php";

if ( libcue_string_contains('Android', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;
if ( libcue_string_contains('iPhone', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;
if ( libcue_string_contains('Windows Phone', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;
if ( libcue_string_contains('RIM', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;
if ( libcue_string_contains('Mobile Safari', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;
if ( libcue_string_contains('MeeGo', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;
if ( libcue_string_contains('Silk', $_SERVER['HTTP_USER_AGENT']) ) $GLOBALS['MOBILE'] = TRUE;

if ( ! isset($_SESSION['user_id']) )
	{
	libcue_session_block_nosession_access_by_('ip');
	if ( $GLOBALS['COMMAND'] === "block_ip" )
		{
		libcue_session_log("FAIL_LOGIN_TRESHOLD REACHED: BLOCKING IP: " . $_SERVER['REMOTE_ADDR'] . " FOR: " .
			$GLOBALS['FAIL_LOGIN_BLOCK_TIME'] . " SECONDS!");
		exec($GLOBALS['LIBCUE_PATH'] . "sh/background block_ip " . $_SERVER['REMOTE_ADDR']);
		exit();
		}
	if ( $GLOBALS['COMMAND'] === "disable_if" )
		{
		libcue_session_log("FAIL_GLOBAL_TRESHOLD REACHED: ASSUMING ATTACK! DISABELING NETWORK FOR: " .
			$GLOBALS['FAIL_LOGIN_BLOCK_TIME'] . " SECONDS!");
		libcue_session_notify_admin("FAIL_GLOBAL_TRESHOLD REACHED: ASSUMING ATTACK! DISABELING NETWORK FOR: " .
			$GLOBALS['FAIL_LOGIN_BLOCK_TIME'] . " SECONDS!");
		exec($GLOBALS['LIBCUE_PATH'] . "sh/background disable_if");
		exit();
		}
	if ( libcue_directory_exists("user", $_REQUEST['user_name']) && $_REQUEST['command'] === "login" )
		{
		libcue_session_block_nosession_access_by_('user_name');
		}
	}

libcue_session_browser_id();
libcue_session_cookie_restore();
#echo "SESSIONID:" . $GLOBALS['session_id'];
#echo "<br>BROWSERID:" . $GLOBALS['browser_id'];
print_r($_COOKIE);
if ( isset($_SESSION['user_id']) )
	{
	libcue_session_heartbeat();
	}
require $LIBCUE_PATH . "libcueexecute.php";
if ( isset($GLOBALS['browser_id']) )
	{
	if ( isset($_SESSION['user_id']) )
		{
		$menuitems[] = libcue_html_menu_item_array("Heute", $REMOTE_HTTP_PATH . "?command=today", "today");
		$menuitems[] = libcue_html_menu_item_array("Post(" . libcue_message_count('new') . ")", $REMOTE_HTTP_PATH . "?command=message", "message");
		if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
			{
			$menuitems[] = libcue_html_menu_item_array("Benutzer", $REMOTE_HTTP_PATH . "?command=user&option=list", "useradmin");
			}
		if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
			{
			$menuitems[] = libcue_html_menu_item_array("Planen", $REMOTE_HTTP_PATH . "?command=schedule", "schedule");
			}
		$menuitems[] = libcue_html_menu_item_array("Abmelden", $REMOTE_HTTP_PATH . "?command=logout", "logout");
		}
	elseif ( $GLOBALS['COMMAND'] !== "blank" && $GLOBALS['COMMAND'] !== "denied" && $_REQUEST['command'] !== "register" &&
		$_REQUEST['command'] !== "password_reset" && $_REQUEST['command'] !== "contact_admin")
		{
		$current_page="login";
		$menuitems[] = libcue_html_menu_item_array("Anmelden", $REMOTE_HTTP_PATH . "?command=login", "login");
		$posts[] =  libcue_html_headline("Melde dich an!") . libcue_form_login() . libcue_html_paragraph(libcue_html_link("Registrieren",
			$REMOTE_HTTP_PATH . "?command=register") . " | " . libcue_html_link("Passwort vergessen", $REMOTE_HTTP_PATH . "?command=password_reset"));
		}
	}
if ( $page_mode !== "none" )
	{
	require $LIBCUE_PATH . "libcuecomposepage.php";
	}
?>

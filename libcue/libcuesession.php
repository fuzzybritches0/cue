<?php
function libcue_session_get_admin_email_address()
	{
	$users = libcue_session_gather_users();
	foreach ( $users as $user )
		{
		if ( libcue_session_user_is($user, "role", "admin") )
			{
			$email = libcue_fsdb_load_variable("user/" . $user, "email");
			if ( isset($email) ) { $admins[] = $email; }
			}
		}
	return $admins;
	}
function libcue_session_increase_fail_count_($var)
	{
	if ( $var === "ip" ) { $var_n = $_SERVER['REMOTE_ADDR']; }
	if ( $var === "user_name" ) { $var_n = $_REQUEST['user_name']; }
	$variables = libcue_fsdb_load_variables("_ZZSYS_" . $var . "_login_fail/" . $var_n);
	if ( ! isset($variables['fails']) ) { $variables['fails'] = 0; }
	$variables['fails']++;
	$variables['fails'] = utf8_encode($variables['fails']);
	$variables['time'] = utf8_encode(time());
	libcue_fsdb_save_variables("_ZZSYS_" . $var . "_login_fail/" . $var_n, $variables);
	}

function libcue_session_block_nosession_access_by_($var)
	{
	if ( $var === "ip" ) { $var_n = $_SERVER['REMOTE_ADDR']; }
	if ( $var === "user_name" ) { $var_n = $_REQUEST['user_name']; }
	$variables = libcue_fsdb_load_variables("_ZZSYS_" . $var  . "_login_fail/" . $var_n);
	if ( $variables['fails'] > $GLOBALS['FAIL_LOGIN_TRESHOLD'] && $variables['time'] + $GLOBALS['FAIL_LOGIN_BLOCK_TIME'] > time() )
		{
		$variables['time'] = utf8_encode(time());
		$GLOBALS['COMMAND'] = "denied";
		if ( $variables['fails'] > $GLOBALS['FAIL_LOGIN_TRESHOLD'] + 1 )
			{
			log_block_nosession_access($var);
			$global_fail = libcue_fsdb_load_variable("_ZZSYS_global_login_fail", "fail");
			$global_fail++;
			$variables_globfail['fail'] = utf8_encode($global_fail);
			libcue_fsdb_save_variables("_ZZSYS_global_login_fail", $variables_globfail);
			}
		$variables['fails']++;
		if ( $variables['fails'] > $GLOBALS['FAIL_LOGIN_TRESHOLD'] + 2 )
			{
			$GLOBALS['COMMAND'] = "block_ip";
			}
		if ( $global_fail > $GLOBALS['FAIL_GLOBAL_TRESHOLD'] )
			{
			$GLOBALS['COMMAND'] = "disable_if";
			libcue_fsdb_delete("_ZZSYS_global_login_fail");
			}
		$variables['fails'] = utf8_encode($variables['fails']);
		libcue_fsdb_save_variables("_ZZSYS_" . $var  . "_login_fail/" . $var_n, $variables);
		}
	elseif ( isset($variables['time']) && $variables['time'] + $GLOBALS['FAIL_LOGIN_BLOCK_TIME'] < time() )
		{
		$global_fail = libcue_fsdb_load_variable("_ZZSYS_global_login_fail", "fail");
		if ( isset($global_fail) && $global_fail > 0 )
			{
			$global_fail--;
			$variables_globfail['fail'] = utf8_encode($global_fail);
			libcue_fsdb_save_variables("_ZZSYS_global_login_fail", $variables_globfail);
			}
		libcue_fsdb_delete("_ZZSYS_" . $var . "_login_fail/" . $var_n);
		}
	}

function log_block_nosession_access($var)
	{
	if ( $var === "ip" ) { $var_n = $_SERVER['REMOTE_ADDR']; }
	if ( $var === "user_name" ) { $var_n = $_REQUEST['user_name']; }
	$record = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
	if ( ! isset($record['country_code']) )
		{
		$record['country_name'] = "UNKNOWN";
		}
	libcue_session_log("FAIL-LOGIN TRESHOLD REACHED FOR: " . $var  . ":" . $var_n . "/" . gethostbyaddr($_SERVER['REMOTE_ADDR'])  . ": "  . $record['country_name']);
	}
function libcue_session_get_var($var_name)
	{
	return libcue_fsdb_load_variable("session/" . $_COOKIE['browser_id'] . "/" . $_SESSION['user_id'], "_VAR_" . $var_name);
	}
function libcue_session_put_var($var_name, $var_cont)
	{
	$variables["_VAR_" . $var_name] = $var_cont;
	libcue_fsdb_save_variables("session/" . $_COOKIE['browser_id'] . "/" . $_SESSION['user_id'], $variables);
	}
#function libcue_session_clean_timed_out_windowids()
#	{
#	foreach ( libcue_array_basename(glob($GLOBALS['DATABASE_PATH'] . "windowid/*")) as $windowid )
#		{
#		$user_id = libcue_fsdb_load_variable("windowid/" . $windowid, "user_id");
#		$time = libcue_fsdb_load_variable("windowid/" . $windowid, "time");
#		$browser_id = libcue_fsdb_load_variable("windowid/" . $windowid, "browser_id");
#		if ( $time + 30 < time() )
#			{
#			libcue_fsdb_unlock_all( "none", $windowid);
#			libcue_fsdb_delete("window_variable/" . $windowid);
#			libcue_fsdb_delete("windowid/" . $windowid);
#			}
#		}
#	}
#function libcue_session_close_windows()
#	{
#	foreach ( libcue_array_basename(glob($GLOBALS['DATABASE_PATH'] . "windowid/*")) as $windowid )
#		{
#		$variables = libcue_fsdb_load_variables("windowid/" . $windowid);
#		if ( $variables['user_id'] === $_SESSION['user_id'] && $variables['browser_id'] === $_COOKIE['browser_id'] )
#			{
#			libcue_fsdb_unlock_all( "none", $windowid);
#			libcue_fsdb_delete("window_variable/" . $windowid);
#			libcue_fsdb_delete("windowid/" . $windowid);
#			}
#		}
#	}
function libcue_session_validate_key($key)
	{
	 if ( preg_match("/^[a-f0-9]{42,42}$/", $key) === 1 )
		{
		return TRUE;
		}
	else
		{
		return FALSE;
		}
	}
#function libcue_session_get_windowid()
#	{
#	foreach ( libcue_array_basename(glob($GLOBALS['DATABASE_PATH'] . "windowid/*")) as $windowid )
#		{
#		$user_id = libcue_fsdb_load_variable("windowid/" . $windowid, "user_id");
#		$browser_id = libcue_fsdb_load_variable("windowid/" . $windowid, "browser_id");
#		if ( $user_id === $_SESSION['user_id'] && $browser_id === $_COOKIE['browser_id'] )
#			{
#			$windowids[] = $windowid;
#			}
#		}
#	if ( count($windowids) > 13 )
#		{
#		libcue_error_message_warning("Du hast zu viele Fenster geöffnet!");
#		}
#	if ( count($windowids) > 16 )
#		{
#		$GLOBALS['COMMAND'] = "blank";
#		}
#	elseif ( isset($_SESSION['user_id']) && ! isset($_REQUEST['windowid']) && count($windowids) < 16)
#		{
#		$GLOBALS['windowid'] = libcue_session_key("windowid");
#		$variables['user_id'] = $_SESSION['user_id'];
#		$variables['time'] = utf8_encode(time());
#		$variables['browser_id'] = $GLOBALS['browser_id'];
#		libcue_fsdb_save_variables("windowid/" . $GLOBALS['windowid'], $variables);
#		}
#	if ( isset($_REQUEST['windowid']) )
#		{
#		if ( libcue_directory_exists("windowid", $_REQUEST['windowid']) )
#			{
#			$time = libcue_fsdb_load_variable("windowid/" . $_REQUEST['windowid'], "time");
#			if ( $time + 30 > time() )
#				{
#				$GLOBALS['windowid'] = $_REQUEST['windowid'];
#				}
#			else
#				{
#				$GLOBALS['windowid'] = libcue_session_key("windowid");
#				$variables['user_id'] = $_SESSION['user_id'];
#				$variables['time'] = utf8_encode(time());
#				$variables['browser_id'] = $GLOBALS['browser_id'];
#				libcue_fsdb_save_variables("windowid/" . $GLOBALS['windowid'], $variables);
#				}
#			}
#		elseif ( count($windowids) < 16 )
#			{
#			$GLOBALS['windowid'] = libcue_session_key("windowid");
#			$variables['user_id'] = $_SESSION['user_id'];
#			$variables['time'] = utf8_encode(time());
#			$variables['browser_id'] = $GLOBALS['browser_id'];
#			libcue_fsdb_save_variables("windowid/" . $GLOBALS['windowid'], $variables);
#			}
#		foreach ( libcue_array_basename(glob($GLOBALS['DATABASE_PATH'] . "windowid/*")) as $windowid )
#			{
#			$user_id = libcue_fsdb_load_variable("windowid/" . $windowid, "user_id");
#			$browser_id = libcue_fsdb_load_variable("windowid/" . $windowid, "browser_id");
#			if ( $user_id === $_SESSION['user_id'] && $browser_id === $GLOBALS['browser_id'] )
#				{
#				$windowids[] = $windowid;
#				}
#			}
#		}
#	if ( isset($GLOBALS['windowid']) && isset($_SESSION['user_id']) )
#		{
#		$user_id = libcue_fsdb_load_variable("windowid/" . $GLOBALS['windowid'], "user_id");
#		if ( $user_id !== $_SESSION['user_id'] || ! libcue_session_validate_key($GLOBALS['windowid']) )
#			{
#			$GLOBALS['COMMAND'] = "blank";
#			}
#		$variables['time'] = utf8_encode(time());
#		libcue_fsdb_save_variables("windowid/" . $GLOBALS['windowid'], $variables);
#		}
#	}
function libcue_session_return_users($array_items, $string)
	{
	$groups = libcue_session_gather("group");
	$roles = libcue_session_gather("role");
	$return_array = array();
	foreach ( $array_items as $item )
		{
		if ( in_array($item, $groups) )
			{
			$groups_search[] = $item;
			}
		elseif ( in_array($item, $roles) )
			{
			$roles_search[] = $item;
			}
		elseif ( libcue_directory_exists( "user", $item) && libcue_user_is_active($item) )
			{
			$return_array[] = libcue_fsdb_load_variable( "user/" . $item, "user_name");
			}
		else
			{
			libcue_error_message($string . " ist keine gültige Eingabe!");
			return FALSE;
			}
		if ( isset($groups_search[0]) )
			{
			$search['group'] = implode(",",$groups_search);
			}
		if ( isset($roles_search[0]) )
			{
			$search['role'] = implode(",",$roles_search);
			}
		}
	if ( isset($roles_search[0]) || isset($groups_search[0]) )
		{
		$add_return = libcue_fsdb_list2("user_name", $search);
		if ( isset($add_return[0]) )
			{
			return array_merge($return_array, $add_return);
			}
		}
	if ( isset($return_array[0]) )
		{
		return $return_array;
		}
	}
function libcue_session_parse_users($string)
	{
	foreach ( explode(",", $string) as $user )
		{
		if ( libcue_directory_exists("user", trim($user)) )
			{
			$return_users[] = libcue_fsdb_load_variable("user/" . trim($user), "user_id");
			}
		}
	return $return_users;
	}
function libcue_session_parse_users_groups_roles($string)
	{
	if ( strlen($string) < 1 )
		{
		libcue_error_message("Keine Benutzer/Gruppen/Rollen angegeben!");
		return FALSE;
		}
	$to_be_removed = array();
	$to_be_add = array();
	$string = str_replace(" ", "", $string);
	$string = trim($string, ",");
	$array_items = explode(",", $string);
	foreach ( $array_items as $array_item )
		{
		if ( strpos($array_item, "-") === 0 && strlen($array_item) > 1 )
			{
			$array_item = ltrim($array_item, "-");
			$array_item = explode("+", $array_item);
			$return = libcue_session_return_users($array_item, $string);
			if ( $return === FALSE )
				{
				return;
				}
			else
				{
				if ( isset($return[0]) )
					{
					$to_be_removed = array_merge($to_be_removed, $return);
					}
				}
			}
		else
			{
			$array_item = ltrim($array_item, "+");
			$array_item = explode("+", $array_item);
			$return = libcue_session_return_users($array_item, $string);
			if ( $return === FALSE )
				{
				return;
				}
			else
				{
				if ( isset($return) )
					{
					$to_be_add = array_merge($to_be_add, $return);
					}
				}
			}
		}
	if ( isset($to_be_add[0]) && ! isset($to_be_removed[0]) )
		{
		return array_unique(libcue_users_ids($to_be_add));
		}
	if ( isset($to_be_add[0]) && isset($to_be_removed[0]) )
		{
		$to_be_add = array_unique(libcue_users_ids($to_be_add));
		$to_be_removed = array_unique(libcue_users_ids($to_be_removed));
		$users_remove = count($to_be_removed);
		foreach ( $to_be_removed as $remove )
			{
			if ( in_array($remove, $to_be_add) )
				{
				$users_remove = $users_remove - 1;
				if ( isset($to_be_add[0]) && ! isset($to_be_add[1]) )
					{
					return;
					}
				$to_be_add = libcue_remove_from_array($remove, $to_be_add);
				}
			}
		if ( $users_remove != 0 )
			{
			libcue_error_message_warning("Nicht alle gewählten Benutzer konnten entfernt werden!");
			}
		return $to_be_add;
		}

	}
function libcue_session_user_is($user, $what, $is, $error_msg="no")
	{
	$user = trim($user);
	if ( ! libcue_directory_exists("user", $user) )
		{
		return FALSE;
		}
	$variable = libcue_fsdb_load_variable( "user/" . $user, $what);
	$variables = explode( ",", $variable);
	foreach ( $variables as $variable )
		{
		$iss = explode( ",", $is);
			foreach ( $iss as $i)
				{
				if ( trim($variable) === trim($i) )
					{
					return TRUE;
					}
				}
		}
	if ( $error_msg != "no" )
		{
		libcue_error_message("Zugriff verweigert! Du besitzt nicht die nötigen Rechte!");
		}
	return FALSE;
	}
function libcue_session_gather_users()
	{
	$users = glob($GLOBALS['DATABASE_PATH'] . "user/00*");
	foreach ( $users as $user )
		{
		$user = basename($user);
		$return_users[] = $user;
		}
	return $return_users;
	}
function libcue_session_gather_users_names()
	{
	$users = glob($GLOBALS['DATABASE_PATH'] . "user/*");
	foreach ( $users as $user )
		{
		$user = basename($user);
		if ( strpos($user, "00") !== 0 )
			{
			$return_users[] = $user;
			}
		}
	return $return_users;
	}
function libcue_session_gather($what)
	{
	$defined_whats = array();
	$users = libcue_fsdb_list( "user_name" );
	if ( isset($users) )
		{
		foreach ( $users as $user )
			{
			$what_list = libcue_fsdb_load_variable( "user_name/" . $user, $what );
			$whats = explode(",", $what_list);
			foreach ( $whats as $what_ )
				{
				$found = FALSE;
				if ( isset($defined_whats) )
					{
					foreach ( $defined_whats as $defined_what )
						{
						if ( $defined_what === trim($what_) )
							{
							$found = TRUE;
							}
						}
					}
				if ( $found !== TRUE )
					{
					if ( strlen( trim($what_)) > 0 )
						{
						$defined_whats[] = trim($what_);
						}
					}
				}
			}
		if ( isset($defined_whats) )
			{
			sort($defined_whats, SORT_NATURAL);
			}
		}
	return $defined_whats;
	}

function libcue_form_login()
{
$options['label'] = "Login";
$options["action"] = "./?command=login";
$variables = array( "user_name", "password" );
$var_desc["user_name"] = "Benutzername"; $var_mode["user_name"] = "text"; $maxlength["user_name"] = "16";
$var_desc["password"] = "Passwort"; $var_mode["password"] = "password";
$options["mode"] = "next";
return libcue_draw_form( $options, $variables, $var_desc, $var_mode, $maxlength );
}
function auth_user($user_name, $password)
{
if ( libcue_directory_exists("user", $user_name) )
	{
	$active = libcue_fsdb_load_variable("user/" . $user_name, "active");
	if ( $active != "active" )
		{
		return FALSE;
		}
	$variables = libcue_fsdb_load_variables( "user/" . basename($user_name) );
	if ( ! isset($variables['password']) )
		{
		libcue_session_log("AUTHENTIFICATION ATTEMPT UNSUCCESSFUL FOR USER WITHOUT LOGIN ENABLED: " . $user_name);
		return FALSE;
		}
	$salts = libcue_fsdb_load_variables( "salt/" . $variables['user_id'] );
	foreach ( $salts as $salt )
		{
		if ( sha1($password . $salt) === $variables['password'] )
			{
			return TRUE;
			}
		}
	}
	libcue_session_log("AUTHENTIFICATION ATTEMPT UNSUCCESSFUL FOR GIVEN USER NAME: " . $user_name);
	return FALSE;
}
function initalize_user_settings($user_name)
{
$_SESSION = libcue_fsdb_load_variables( "user/" . $user_name );
libcue_session_log("loading session information for user");
}
function libcue_session_logout()
{
if ( isset($_SESSION['user_id']) )
	{
	libcue_session_log("logout");
	destroy_session_id();
	}
}

function libcue_user_name_exists($user_name, $max_len=16)
{
	if ( strlen($user_name) >= 1 && strlen($user_name) <= $max_len )
		{
		return is_file($GLOBALS['DATABASE_PATH'] . "user/" . basename($user_name) . "/user_id");
		}
}

function destroy_session_id()
	{
	setcookie("user_name", "", -3600, "/");
	setcookie("session", "", -3600, "/");
	libcue_fsdb_delete( "session/" . $GLOBALS['browser_id'] . "/" . $_SESSION['user_id']);
	unset($_SESSION);
	session_unset();
	}

function libcue_session_key( $directory="none", $add="" )
{
if ( $directory === "none" )
	{
	return $add . "00" . sha1(rand(198629, getrandmax()) . rand(206533, getrandmax()));
	}
else
	{
	$key = $add . "00" . sha1(rand(198629, getrandmax()) . rand(206533, getrandmax()));
	while ( is_dir($GLOBALS['DATABASE_PATH'] . $directory . "/" . $key) )
		{
		$key = $add . "00" . sha1(rand(198629, getrandmax()) . rand(206533, getrandmax()));
		}
	return $key;
	}

}
function create_browser_id()
	{
	$browser_ids = count(glob($GLOBALS['DATABASE_PATH'] . "browser_id/*"));
	if ( $browser_ids < $GLOBALS['BROWSERID_LIMIT'] )
		{
		$cookie = libcue_session_key("session");
		$GLOBALS['browser_id'] = $cookie;
		setcookie("browser_id", $cookie, time() + $GLOBALS['BROWSER_ID_TIME_MAX'], "/");
		$var['time'] = utf8_encode(time());
		libcue_fsdb_save_variables("browser_id/" . $cookie, $var);
		libcue_session_log("issuing new browserid");
		}
	else
		{
		$GLOBALS['COMMAND'] = "denied";
		libcue_session_log("BROWSERID LIMIT REACHED!");
		libcue_session_notify_admin_once("BROWSERID LIMIT REACHED!");
		}
	}
function libcue_session_browser_id_clean()
	{
	foreach ( glob($GLOBALS['DATABASE_PATH'] . "browser_id/*") as $browser_id )
		{
		$time = libcue_fsdb_load_variable("browser_id/" . basename($browser_id), "time");
		if ( $time + $GLOBALS['BROWSER_ID_TIME_MAX'] < time() )
			{
			libcue_fsdb_delete("browser_id/" . basename($browser_id));
			foreach ( glob($GLOBALS['DATABASE_PATH'] . "session/" . basename($browser_id) . "/*") as $session )
				{
				libcue_fsdb_delete("session/" . basename($browser_id) . "/" . basename($session));
				}
			libcue_fsdb_delete("session/" . basename($browser_id));
			}
		}
	}
function libcue_session_browser_id()
	{
	libcue_session_browser_id_clean();
	if ( ! isset($_COOKIE['browser_id']) || ! libcue_session_validate_key($_COOKIE['browser_id']) ||
		! libcue_directory_exists("browser_id", $_COOKIE['browser_id']) )
		{
		create_browser_id();
		}
	if ( isset($_COOKIE['browser_id']) && libcue_directory_exists("browser_id", $_COOKIE['browser_id']) )
		{
		$GLOBALS['browser_id'] = $_COOKIE['browser_id'];
		setcookie("browser_id", $_COOKIE['browser_id'], time() + $GLOBALS['BROWSER_ID_TIME_MAX'], "/");
		$var['time'] = utf8_encode(time());
		libcue_fsdb_save_variables("browser_id/" . $_COOKIE['browser_id'], $var);
		return;
		}
	}
function create_session_ID()
{
$GLOBALS['session_id'] = $variables['session'] = libcue_session_key();
$variables['lastseen'] = utf8_encode(time());
setcookie("user_id", $_SESSION['user_id'], $variables['lastseen'] + $GLOBALS['SESSION_ID_TIME_MAX'], "/");
setcookie("session", $variables['session'], $variables['lastseen'] + $GLOBALS['SESSION_ID_TIME_MAX'], "/");
libcue_fsdb_save_variables( "session/" . $GLOBALS['browser_id'] . "/" . $_SESSION['user_id'], $variables);
}

function libcue_session_heartbeat()
	{
	if ( isset($_COOKIE['session']) ) { $GLOBALS['session_id'] = $_COOKIE['session']; }
	$variables['lastseen'] = utf8_encode(time());
	libcue_fsdb_save_variables( "session/" . $GLOBALS['browser_id'] . "/" . $_SESSION['user_id'], $variables);
	}

function libcue_session_cookie_restore()
{
if ( ! isset($_SESSION['user_id']) && isset($_COOKIE['user_id']) && isset($_COOKIE['browser_id']) &&
      isset($_COOKIE['session']) && libcue_session_validate_key($_COOKIE['user_id']) && libcue_session_validate_key($_COOKIE['browser_id']) &&
	libcue_session_validate_key($_COOKIE['session']) && libcue_directory_exists("user", $_COOKIE['user_id']) && libcue_user_is_active($_COOKIE['user_id']) )
	{
	if ( identify_with_cookie() )
		{
		libcue_session_log("authentification successful with cookie for user");
		initalize_user_settings($_COOKIE['user_id']);
		$GLOBALS['session_id'] = $_COOKIE['session'];
		}
	}
}

function identify_with_cookie()
{
$variables = libcue_fsdb_load_variables("session/" . $_COOKIE['browser_id'] . "/" . $_COOKIE['user_id']);
if ( isset($variables['session']) && $variables['session'] === $_COOKIE['session'] )
	{
	if ( $variables['lastseen'] + $GLOBALS['SESSION_ID_TIME_MAX'] > time() ) return TRUE;
	else return FALSE;
	}
libcue_session_log("WARNING: UNKNOWN COOKIE RECIEVED!");
return FALSE;
}

function libcue_session_return_user_id($user)
	{
	return libcue_fsdb_load_variable("user/" . $user, 'user_id');
	}

function libcue_session_log($log)
	{
	$log_file = date( "Ymd", time());
	if ( isset($_SESSION['user_id']) )
		{
		$entry = date( "H:i:s d.m.Y e") . " - " . $_SESSION['user_id'] . "(" . $_SESSION['user_name'] . "): " . $log . "\n";
		}
	else
		{
		$entry = date( "H:i:s d.m.Y e") . " - NO SESSION: " . $log . "\n";
		}
	if ( ! is_dir( $GLOBALS['DATABASE_PATH'] . "/_ZZSYS_log") )
		{
		if ( ! mkdir( $GLOBALS['DATABASE_PATH'] . "/_ZZSYS_log", 0700, TRUE) )
			{
			libcue_session_notify_admin_once("LIBCUE_SESSION_LOG: FAILED TO CREATE LOG DIRECTORY!");
			return FALSE;
			}
		}
	if ( ! file_put_contents($GLOBALS['DATABASE_PATH'] . "/_ZZSYS_log/" . $log_file, $entry, FILE_APPEND) )
		{
		libcue_session_notify_admin_once('LIBCUE_SESSION_LOG: FAILED TO SAVE LOG ENTRY!');
		}

	}

function libcue_session_notify_admin($note)
	{
	$emails = libcue_session_get_admin_email_address();
	if ( isset($emails[0]) )
		{
		foreach ( $emails as $email )
			{
			mail($email, $GLOBALS['REMOTE_HTTP_PATH'], $note);
			}
		}
	}
function libcue_session_notify_admin_once($info)
	{
	$info_id = str_replace(" ", "_", $info);
	$time = libcue_fsdb_load_variable("_ZZSYS_inform_admin", $info_id);
	if ( ! isset($time) || $time + $GLOBALS['NOTIFY_ADMIN_ONCE_EVERY_SECONDS'] <  time() )
		{
		libcue_session_notify_admin($info);
		libcue_fsdb_save_variables("_ZZSYS_inform_admin", array( $info_id => utf8_encode(time()) ));
		}
	}
?>

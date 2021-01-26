<?php
function libcue_fsdb_delete( $directory )
	{
	foreach ( glob($GLOBALS['DATABASE_PATH'] . $directory . "/*") as $file )
		{
		if ( is_file($file) )
			{
			unlink($file);
			}
		}
	if ( is_dir($GLOBALS['DATABASE_PATH'] . $directory) )
		{
		rmdir($GLOBALS['DATABASE_PATH'] . $directory);
		}
	}
function libcue_fsdb_load_variable( $directory, $variable )
	{
	if ( strpos($directory, $GLOBALS['DATABASE_PATH']) === 0 )
		{
		if ( file_exists($directory . "/" . basename($variable)) )
			{
			return file_get_contents($directory . "/" . basename($variable));
			}
		}
	else
		{
		if ( file_exists($GLOBALS['DATABASE_PATH'] . $directory . "/" . basename($variable)) )
			{
			return file_get_contents($GLOBALS['DATABASE_PATH'] . $directory . "/" . basename($variable));
			}
		}
	}

function libcue_fsdb_load_variables( $directory )
	{
	if ( strpos($directory, $GLOBALS['DATABASE_PATH']) === 0)
		{
		$variables = glob($directory . "/*");
		}
	else
		{
		$variables = glob($GLOBALS['DATABASE_PATH'] . $directory . "/*");
		}
	foreach( $variables as $variable)
		{
		$contents = file_get_contents( $variable );
		$variable_name = basename( $variable );
		$return_variables[$variable_name] = $contents;
		}
	return $return_variables;
	}
#function libcue_fsdb_clean_timed_out_locks()
#	{
#	foreach ( libcue_array_basename(glob($GLOBALS['DATABASE_PATH'] . "sessionid/*")) as $sessionid )
#		{
#		$variables = libcue_fsdb_load_variables("sessionid/" . $sessionid);
#		if ( $variables['time'] + 17 < time() )
#			{
#			libcue_fsdb_unlock_all($variables['user_id'], $sessionid);
#			}
#		}
#	}
#function libcue_fsdb_unlock_all( $user_id="none", $sessionid="none" )
#	{
#	if ( $user_id === "none" && $sessionid !== "none" )
#		{
#		foreach ( glob($GLOBALS['DATABASE_PATH'] . "lock/*") as $locked )
#			{
#			$variables = libcue_fsdb_load_variables("lock/" . basename($locked));
#			if ( $variables['sessionid'] === $sessionid  )
#				{
#				libcue_fsdb_delete("lock/" . basename($locked));
#				}
#			}
#		return;
#		}
#	if ( $user_id === "none" )
#		{
#		foreach ( glob($GLOBALS['DATABASE_PATH'] . "lock/*") as $locked )
#			{
#			$variables = libcue_fsdb_load_variables("lock/" . basename($locked));
#			if ( $variables['user_id'] === $_SESSION['user_id'] && $variables['sessionid'] === $GLOBALS['sessionid'] )
#				{
#				libcue_fsdb_delete("lock/" . basename($locked));
#				}
#			}
#		return;
#		}
#	if ( $user_id !== "none" && $sessionid === "none" )
#		{
#		foreach ( glob($GLOBALS['DATABASE_PATH'] . "lock/*") as $locked )
#			{
#			$variables = libcue_fsdb_load_variables("lock/" . basename($locked));
#			$browser_id = libcue_fsdb_load_variable("sessionid/" . $variables['sessionid'], "browser_id");
#			if ( $variables['user_id'] === $user_id  && $_COOKIE['browser_id'] === $browser_id )
#				{
#				libcue_fsdb_delete("lock/" . basename($locked));
#				}
#			}
#		}
#	if ( $user_id !== "none" && $windowid !== "none" )
#		{
#		foreach ( glob($GLOBALS['DATABASE_PATH'] . "lock/*") as $locked )
#			{
#			$variables = libcue_fsdb_load_variables("lock/" . basename($locked));
#			if ( $variables['user_id'] === $user_id && $windowid === $variables['windowid'] )
#				{
#				libcue_fsdb_delete("lock/" . basename($locked));
#				}
#			}
#		}
#
#	}
#function lock_directory($directory)
#	{
#	$variables['user_id'] = $_SESSION['user_id'];
#	$variables['windowid'] = $GLOBALS['windowid'];
#	libcue_fsdb_save_variables("lock/" . $directory, $variables);
#	}
#function libcue_fsdb_lock($directory)
#	{
#	$directory = str_replace("/", "", $directory);
#	$variables = libcue_fsdb_load_variables("lock/" . $directory);
#	if ( ! isset($variables['user_id']) )
#		{
#		lock_directory($directory);
#		return TRUE;
#		}
#	if ( $variables['user_id'] === $_SESSION['user_id'] && $variables['windowid'] === $GLOBALS['windowid'] )
#		{
#		lock_directory($directory);
#		return TRUE;
#		}
#	if ( $variables['user_id'] === $_SESSION['user_id'] )
#		{
#		libcue_error_message_warning("Sie haben die Daten schon in einem anderen Fenster geöffnet!");
#		}
#	else
#		{
#		libcue_error_message_warning("Die Daten werden zurzeit von jemand anderem bearbeitet!");
#		}
#	return FALSE;
#	}
#function libcue_fsdb_directory_is_locked($directory)
#	{
#	$directory = str_replace("/", "", $directory);
#	$variables = libcue_fsdb_load_variables("lock/" . $directory);
#	if ( ! isset($variables['user_id'] ) )
#		{
#		return FALSE;
#		}
#	if ( $variables['user_id'] === $_SESSION['user_id'] && $variables['windowid'] === $GLOBALS['windowid'] )
#		{
#		return FALSE;
#		}
#	if ( $variables['user_id'] === $_SESSION['user_id'] )
#		{
#		libcue_error_message("DATEN NICHT GESPEICHERT! Sie haben die Daten schon in einem anderen Fenster geöffnet!");
#		}
#	else
#		{
#		libcue_error_message("DATEN NICHT GESPEICHERT! Die Daten werden zurzeit von jemand anderem bearbeitet!");
#		}
#	return TRUE;
#	}
function libcue_fsdb_save_variables( $directory, $variables, $mode="" )
	{
	foreach ( $variables as $var_content )
		{
		if ( mb_convert_encoding($var_content, 'UTF-8', 'UTF-8') !== $var_content && strlen($var_content) > 0 )
			{
			libcue_error_message("libcue_fsdb: Daten wurden nicht gespeichert! Encoding NICHT UTF-8!");
			return FALSE;
			}
		}
	if ( ! is_array($mode) ) { $mode[] = ""; }
	if ( ! is_dir( $GLOBALS['DATABASE_PATH'] . $directory) )
		{
		if ( ! mkdir( $GLOBALS['DATABASE_PATH'] . $directory, 0700, TRUE) )
			{
			libcue_error_message("libcue_fsdb: Verzeichniss konnte nicht angelegt werden! Admin verständigen!");
			return FALSE;
			}
		}
	foreach( $variables as $var_name => $var_content )
		{
		if ( strlen($var_content) < 1 )
			{
			if ( file_exists( $GLOBALS['DATABASE_PATH'] . $directory . "/" . $var_name ) )
				{
				if ( ! unlink( $GLOBALS['DATABASE_PATH'] . $directory . "/" . $var_name) )
					{
					libcue_error_message("libcue_fsdb: Datei konnte nicht gelöscht werden! Admin verständigen!");
					return FALSE;
					}
				}
			}
		else
			{
			if ( ! isset($GLOBALS['REQUEST_ERROR_' . $var_name]) )
				{
				if ( $mode[$var_name] === "html" )
					{
					if ( ! file_put_contents( $GLOBALS['DATABASE_PATH'] . $directory . "/" . $var_name, $var_content ) )
						{
						libcue_error_message("libcue_fsdb: Datei konnte nicht gespeichert werden! Admin verständigen!");
						return FALSE;
						}
					}
				elseif ( $mode[$var_name] === "password" )
					{
					if ( ! file_put_contents( $GLOBALS['DATABASE_PATH'] . $directory . "/" .
						$var_name, sha1(libcue_salt(basename($directory), $var_content)) ) )
						{
						libcue_error_message("libcue_fsdb: Datei konnte nicht gespeichert werden! Admin verständigen!");
						return FALSE;
						}
					}
				else
					{
					if ( ! file_put_contents( $GLOBALS['DATABASE_PATH'] . $directory . "/" . $var_name, htmlspecialchars($var_content) ) )
						{
						libcue_error_message("libcue_fsdb: Datei konnte nicht gespeichert werden! Admin verständigen!");
						return FALSE;
						}
					}
				chmod($GLOBALS['DATABASE_PATH'] . $directory . "/" . $var_name, octdec('600'));
				}
			unset($_REQUEST['var_name']);
			}
		unset($_REQUEST[$var_name]);
		}
	}

function libcue_fsdb_list2($directory, $search_elements=NULL)
	{
	$files = glob($GLOBALS['DATABASE_PATH'] . $directory . "/*");
	if ( ! is_array($files) )
		{
		return;
		}
	if ( $search_elements === NULL || ! is_array($search_elements) )
		{
		return libcue_array_basename($files);
		}
	foreach ( $files as $file )
		{
		unset($allfound);
		foreach ( $search_elements as $search_element => $content )
			{
			$file_content = libcue_fsdb_load_variable($file, $search_element);
			foreach ( explode(",", $content) as $each_content )
				{
				if ( libcue_string_contains(trim($each_content), $file_content) )
					{
					$allfound = $allfound . "t";
					}
				else
					{
					$allfound = $allfound . "f";
					}
				}
			}
		if ( ! libcue_string_contains("f", $allfound) )
			{
			$files_return[] = basename($file);
			}
		}
	return $files_return;
	}

function libcue_fsdb_list($directory, $var_name=NULL, $var_contains=NULL)
	{
	$list = array();
	$elements = glob( $GLOBALS['DATABASE_PATH'] . $directory . "/*" );
	foreach ( $elements as $element )
		{
		$element = basename($element);
		if ( $var_name !== NULL )
			{
			$variables = libcue_fsdb_load_variables( $directory ."/" . $element );
			$var_names = explode( ",", $var_name);
			$var_containss = explode ( ",", $var_contains);
			$count = 0;
			foreach ( $var_names as $var_name_ )
				{
				$add_to_lists[$count] = FALSE;
				foreach ( explode( ",", $variables[trim($var_name_)]) as $var_content_part )
					{
					if ( trim($var_content_part) === trim( $var_containss[$count]) || trim($var_containss[$count]) === "" )
						{
						$add_to_lists[$count] = TRUE;
						}
					}
				$count++;
				}
			foreach ( $add_to_lists as $add_to_list )
				{
				if ( $add_to_list !== TRUE ) { $dont_add = TRUE; }
				}
			if ( $dont_add !== TRUE )
				{
				$list[] = $element;
				}
			unset($add_to_lists);
			unset($dont_add);
			}
		else
			{
			$list[] = $element;
			}
		}
	return $list;
	}

#function libcue_fsdb_search_string($search, $directory, $variables)
#{
#$return_files = array();
#if ( strlen($search) <= 3 )
#	{
#	return $return_files;
#	}
#$variables[] = "file_name";
#$files_long = glob($GLOBALS["DATABASE_PATH"] . $directory . "/*" );
#foreach ( $files_long as $file )
#	{
#	$basename_file = basename($file);
#	$vars = libcue_fsdb_load_variables($directory . "/" . $basename_file);
#	$vars['add_basename_file_to_search'] = $basename_file;
#	foreach ( $variables as $variable )
#		{
#		if ( preg_match( "/" .$search . "/i", $vars[$variable]) )
#			{
#			$return_files[] = $file;
#			break;
#			}
#		}
#	}
#return $return_files;
#}
?>

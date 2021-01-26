<?php
require $LIBCUE_PATH . "libcueerror.php";
require $LIBCUE_PATH . "libcueform.php";
require $LIBCUE_PATH . "libcuehtml2.php";
require $LIBCUE_PATH . "libcuemessage.php";
require $LIBCUE_PATH . "libcuesession.php";
require $LIBCUE_PATH . "libcuetable2.php";
require $LIBCUE_PATH . "libcuevalidateinput.php";
require $LIBCUE_PATH . "libcuedate.php";
require $LIBCUE_PATH . "libcuesalt.php";
require $LIBCUE_PATH . "libcuefsdb.php";
require $LIBCUE_PATH . "libcuemobe.php";

ini_set('mbstring.substitute_character', "none");

function libcue_remove_zero( $string )
	{
	while ( substr($string, 0,1) === "0" )
		{
		$string = substr($string , 1, strlen($string) -1);
		}
	return utf8_encode($string);
	}

function libcue_zero( $len, $string )
	{
	while ( strlen($string) < $len )
		{
		$string = "0" . $string;
		}
	return utf8_encode($string);
	}

function libcue_remove_from_array($remove, $array_elements)
	{
	foreach ( $array_elements as $element )
		{
		if ( $element !== $remove )
			{
			$return_array[] = $element;
			}
		}
	return $return_array;
	}

function libcue_string_contains($needle, $haystack)
	{
	if ( strpos($haystack, $needle) !== FALSE )
		{
		return TRUE;
		}
	return FALSE;
	}

function libcue_trim_array($array_items)
	{
	foreach ( $array_items as $item )
		{
		$items_return[] = trim($item);
		}
	return $items_return;
	}

function libcue_array_basename($files)
	{
	if ( isset($files[0]) )
		{
		foreach ( $files as $file )
			{
			$files_return[] = basename($file);
			}
		return $files_return;
		}
	return array();
	}

function libcue_users_ids($array)
	{
	foreach ( $array as $item )
		{
		$return_array[] = libcue_fsdb_load_variable("user/" . $item, "user_id");
		}
	return $return_array;
	}

#function libcue_remove_spaces_array($array_items)
#	{
#	foreach ( $array_items as $item )
#		{
#		$items_return[] = str_replace(' ', '', $item);
#		}
#	return $items_return;
#	}
?>

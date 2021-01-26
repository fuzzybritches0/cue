<?php

function libcue_error_occurred()
{
if ( $GLOBALS['ERROR_OCCURRED'] === TRUE )
	{
	return TRUE;
	}
return FALSE;
}


function libcue_error_report()
{
if ( isset($GLOBALS['report_messages'][0]) )
	{
	$GLOBALS['ERROR_OCCURRED'] = FALSE;
	$messages = implode("", array_unique($GLOBALS['report_messages'])); 
	unset($GLOBALS['report_messages']);
	return $messages;
	}
}

function libcue_error_message($message)
{
$GLOBALS['ERROR_OCCURRED'] = TRUE;
$GLOBALS['report_messages'][] = "<p style='background-color: #FF7777;' >" . $message . "</p>";
}

function libcue_error_message_warning($message)
{
$GLOBALS['report_messages'][] = "<p style='background-color: #FFFF77;' >" . $message . "</p>";
}
function libcue_debug($message)
{
if ( $GLOBALS['DEBUG'] === TRUE )
	{
	$GLOBALS['report_messages'][] = "<p style='background-color: #FFFF77;' >" . $message . "</p>";
	}
}

#function libcue_error_message_false($boolean, $message)
#
#if ( $boolean === FALSE )
#	{
#	$_SESSION['error_messages'] = $_SESSION['error_messages'] . "<p>" . $message . "</p>";
#	}
#}
#function libcue_error_message_true($boolean, $message)
#{
#if ( $boolean === TRUE )
#	{
#	$_SESSION['error_messages'] = $_SESSION['error_messages'] . "<p>" . $message . "</p>";
#	}
#}
#function libcue_error_message_success($message)
#{
#$GLOBALS['report_messages'][] = "<p>" . $message . "</p>";
#}
?>

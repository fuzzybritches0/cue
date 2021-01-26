<?php
function send_admin_email($subject, $message)
	{
	$message = $message . "\r\n\r\n" . "---" . "\r\n" . "Dies ist eine automatisierte Nachricht und wurde vom System auf " . $GLOBALS['REMOTE_HTTP_PATH'] .
		" versandt. Auf diese Nachricht kann nicht geantwortet werden!";
	$message = htmlspecialchars(wordwrap($message, '80', "\r\n"));
	$emails = libcue_session_get_admin_email_address();
	if ( isset($emails[0]) )
		{
		$count = 0;
		foreach ( $emails as $email )
			{
			if ( ! mail($email, $subject, $message) )
				{
				$count++;
				}
			}
		if ( $count === count($emails) )
			{
			return FALSE;
			}
		return TRUE;
		}
	return FALSE;
	}
function send_system_email($to, $subject, $message)
	{
	$message = $message . "\r\n\r\n" . "---" . "\r\n" . "Dies ist eine automatisierte Nachricht und wurde vom System auf " . $GLOBALS['REMOTE_HTTP_PATH'] .
		" versandt. Auf diese Nachricht kann nicht geantwortet werden!" . "\r\n" . "Solltest du diese Nachricht unerwartet erhalten haben, ".
		"kontaktiere bitte den Administrator auf " . $GLOBALS['REMOTE_HTTP_PATH'] . "?command=contact_admin";
	$message = wordwrap($message, '80', "\r\n");
	return  mail($to, $subject, $message);
	}
function libcue_message_count($list)
	{
	return count(glob($GLOBALS['DATABASE_PATH'] . "/message/" . $_SESSION['user_id'] . "/" . $list . "/*"));
	}
?>

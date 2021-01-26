<?php
function form_email()
	{
	$variables = array("email");
	$options['action'] = $REMOTE_HTTP_PATH . "?command=password_reset&option=submit";
	$desc['email'] = "Email-Addresse"; $maxlength['email'] = "64";
	$options['label'] = "Senden"; $mode['email'] = "text";
	return libcue_html_headline("Passwort neu setzen - Schritt 1/2") . libcue_html_paragraph("Bitte gib deine Email-Addresse an, mit der du dich registriert " .
		"hast. Du bekommst eine Nachricht, in der du erfahrst wie du weiter vorgehen musst!") .
		libcue_draw_form($options, $variables, $desc, $mode, $maxlength);
	}
function form_password_reset()
	{
	$variables = array("password", "password_confirm");
	$options['action'] = $REMOTE_HTTP_PATH . "?command=password_reset&option=save&key=" . $_REQUEST['key'];
	$desc['password'] = "Passwort"; $desc['password_confirm'] = "Passwort wiederholen"; $options['label'] = "Speichern";
	$mode['password'] = "password"; $mode['password_confirm'] = "password";
	return libcue_html_headline("Passwort neu setzen - Schritt 2/2") . libcue_html_paragraph("Nun kannst du ein neues Passwort setzen!") .
		libcue_draw_form($options, $variables, $desc, $mode, $maxlength);
	}
$current_page="login";

if ( ! isset($_SESSION['user_id']) )
	{
	$menuitems[] = libcue_html_menu_item_array("Anmelden", $REMOTE_HTTP_PATH . "?command=login", "login");
	if ( ! isset($_REQUEST['key']) && ! isset($_REQUEST['option']) )
		{
		$posts[] = form_email();
		}
	if ( ! isset($_REQUEST['key']) && $_REQUEST['option'] === "submit" )
		{
		libcue_validate_input("email", "email_adress", "Email-Addresse");
		if ( ! libcue_error_occurred() )
			{
			if ( ! libcue_directory_exists("user", $_REQUEST['email']) )
				{
				libcue_error_message("Die Email-Addresse scheint ungültig zu sein!");
				}
			}
		if ( ! libcue_error_occurred() )
			{
			$variables['user_id'] = libcue_fsdb_load_variable("user/" . $_REQUEST['email'], "user_id");
			$variables['time'] = utf8_encode(time());
			$reset_key = libcue_session_key();
			$message = " Du willst dein Passwort neu setzen! Dies kannst du tun indem du den untenstehenden Link anklickst oder ihn in ".
				"die Addresszeile deines Browsers kopierst!\r\n\r\n" . $GLOBALS['REMOTE_HTTP_PATH'] . "?command=password_reset&key=" . $reset_key;
			if ( send_system_email($_REQUEST['email'], "Passwort neu setzen: " . $GLOBALS['REMOTE_HTTP_PATH'], $message) )
				{
				libcue_fsdb_save_variables("_ZZSYS_password_reset/" . $reset_key, $variables);
				$posts[] = libcue_html_paragraph("Dir wurde soeben eine Email geschickt. Bitte folge den Anweisungen in der Email.");
				}
			}
		else
			{
			libcue_session_increase_fail_count_('ip');
			libcue_error_message("Die Email konnte nicht an die angegebene Email-Addresse übermittelt werden! Überprüfe doch noch einmal deine Angaben! " .
				"Sollte das Problem weiter bestehen, kontaktiere bitte den Administrator!");
			$posts[] = form_email();
			}
		}
	if ( isset($_REQUEST['key']) && ! isset($_REQUEST['option']) )
		{
		if ( libcue_directory_exists("_ZZSYS_password_reset", $_REQUEST['key']) )
			{
			$posts[] = form_password_reset();
			}
		else
			{
			libcue_session_increase_fail_count_('ip');
			}
		}
	if ( isset($_REQUEST['key']) && $_REQUEST['option'] === "save" )
		{
		if ( libcue_directory_exists("_ZZSYS_password_reset", $_REQUEST['key']) )
			{
			$user_id = libcue_fsdb_load_variable("_ZZSYS_password_reset/" . $_REQUEST['key'], "user_id");
			$user_data = libcue_fsdb_load_variables("user/" . $user_id);
			libcue_validate_input("password", "password", "Passwort");
			libcue_validate_input_password($user_data['user_name'], $user_data['real_name'], $_REQUEST['password'], $_REQUEST['password_confirm']);
			if ( ! libcue_error_occurred() )
				{
				$mode['password'] = "password";
				$user['password'] = $_REQUEST['password'];
				libcue_fsdb_save_variables("user/" . $user_id, $user, $mode);
				libcue_fsdb_delete("_ZZSYS_password_reset/" . $_REQUESt['key']);
				$posts[] = libcue_html_paragraph("Dein neues Passwort wurde erfolgreich gesetzt!");
				}
			else
				{
				$posts[] = form_password_reset();
				}
			}
		else
			{
			libcue_session_increase_fail_count_('ip');
			}
		}
	}

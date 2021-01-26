<?php

function form_contact_admin()
	{
	$options['label'] = "Senden";
	$options['action'] = $GLOBALS['REMOTE_HTTP_PATH'] . "?command=contact_admin&option=submit";
	$variables = array( "message" );
	$desc['message'] = "Nachricht";
	$mode['message'] = "textarea";
	return libcue_html_headline("Administrator kontaktieren") . libcue_html_paragraph("Versuche in einfachen Worten und ausführlich zu erklären was dein " .
		"Anliegen ist! Wenn du einen Programm-Fehler melden willst, versuche anzugeben wie man diesen Fehler hervorruft!") .
		libcue_draw_form($options, $variables, $desc, $mode);
	}
function form_contact_admin_no_session()
	{
	$options['label'] = "Senden";
	$options['action'] = $GLOBALS['REMOTE_HTTP_PATH'] . "?command=contact_admin&option=submit";
	$variables = array( "email", "message" );
	$desc['email'] = "Email-Addresse";
	$desc['message'] = "Nachricht";
	$mode['email'] = "text";
	$mode['message'] = "textarea";
	$maxlength['email'] = "64";
	return libcue_html_headline("Administrator kontaktieren") . libcue_html_paragraph("Bitte gib deine Email-Addresse an! Es muss eine gültige " .
		"Email-Addresse sein. Wenn du mit einer Email-Addresse hier registriert bist, gib bitte diese an! Sofern du nicht mit der angegebenen " .
		"Email-Addresse hier registriert bist, wird dir eine Nachricht zugeschickt, mit der du deine Identität bestätigen musst.") .
		libcue_html_paragraph(" Versuche in einfachen Worten und ausführlich zu erklären was dein Anliegen ist! Wenn du einen Programm-Fehler melden " .
		"willst, versuche anzugeben wie man diesen Fehler hervorruft!") . libcue_draw_form($options, $variables, $desc, $mode, $maxlength);
	}
$current_page="login";
if ( ! isset($_SESSION['user_id']) )
	{
	$menuitems[] = libcue_html_menu_item_array("Anmelden", $REMOTE_HTTP_PATH . "?command=login", "login");
	if ( isset( $_REQUEST['key']) )
		{
		if ( libcue_directory_exists("_ZZSYS_contact_admin", $_REQUEST['key']) )
			{
			$variables = libcue_fsdb_load_variables("_ZZSYS_contact_admin/" . $_REQUEST['key']);
			if ( send_admin_email($variables['subject'], $variables['message']) )
				{
				$posts[] = libcue_html_headline("Administrator kontaktieren") . libcue_html_paragraph("Dein Anliegen wurde an den Administrator " .
					"weitergeleitet!");
				}
			else
				{
				libcue_error_message("Leider konnte dein Anliegen nicht an den Administrator weitergeleitet werden!");
				$posts[] = libcue_html_headline("Administrator kontaktieren");
				}
			}
		else
			{
			libcue_session_increase_fail_count_('ip');
			}
		}
	elseif ( $_REQUEST['option'] === "submit" )
		{
		libcue_validate_input("email", "email_adress", "Email-Addresse");
		if ( ! libcue_error_occurred() )
			{
			if ( ! libcue_directory_exists("user", $_REQUEST['email']) )
				{
				libcue_validate_email($_REQUEST['email']);
				}
			}
		if ( ! libcue_error_occurred() )
			{
			if ( libcue_directory_exists("user", $_REQUEST['email']) )
				{
				$message = $_REQUEST['message'];
				$subject = $GLOBALS['REMOTE_HTTP_PATH'] . " Anliegen von: " . libcue_fsdb_load_variable("user/" . $_REQUEST['email'], "real_name");
				if ( send_admin_email($subject, $message) )
					{
					$posts[] = libcue_html_headline("Administrator kontaktieren") . libcue_html_paragraph("Dein Anliegen wurde an den " .
						"Administrator weitergeleitet!");
					}
				else
					{
					libcue_error_message("Leider konnte dein Anliegen nicht an den Administrator weitergeleitet werden!");
					$posts[] = form_contact_admin_no_session();
					}
				}
			else
				{
				$variables['message'] = $_REQUEST['message'];
				$variables['subject'] = $GLOBALS['REMOTE_HTTP_PATH'] . " Anliegen von: " . $_REQUEST['email'];
				$key = libcue_session_key();
				$message = "Um dein Anliegen an den Administrator weiterleiten zu können, musst du den untenstehenden Link anklicken oder in die " .
					"Addresszeile deines Browsers kopieren!" . "\r\n" . $GLOBALS['REMOTE_HTTP_PATH'] . "?command=contact_admin&key=" . $key;
				if ( send_system_email($_REQUEST['email'], "Dein Anliegen auf: " . $GLOBALS['REMOTE_HTTP_PATH'], $message) )
					{
					libcue_fsdb_save_variables("_ZZSYS_contact_admin/" . $key, $variables);
					$posts[] = libcue_html_headline("Administrator kontaktieren") . libcue_html_paragraph("Dir wurde eine " .
						"Bestätigungs-Nachricht zugesendet. Bitte klick auf den dort enthaltenen Link, um den Vorgang abzuschließen!");
					}
				else
					{
					libcue_session_increase_fail_count_('ip');
					libcue_error_message("Die angegebene Email-Addresse schein ungültig zu sein!");
					$posts[] = form_contact_admin_no_session();
					}
				}
			}
		else
			{
			libcue_session_increase_fail_count_('ip');
			$posts[] = form_contact_admin_no_session();
			}
		}
	else
		{
		$posts[] = form_contact_admin_no_session();
		}
	}
if ( isset($_SESSION['user_id']) )
	{
	if ( $_REQUEST['option'] === "submit" )
		{
		if ( send_admin_email("Anliegen von: " . libcue_fsdb_load_variable("user/" . $_SESSION['user_id'], "real_name"), $_REQUEST['message']) )
			{
			$posts[] =  libcue_html_headline("Administrator kontaktieren") . libcue_html_paragraph("Dein Anliegen wurde an den Administrator " .
				"weitergeleitet!");
			}
		else
			{
			libcue_error_message("Leider konnte dein Anliegen nicht an den Administrator weitergeleitet werden!");
			$posts[] = form_contact_admin();
			}
		}
	else
		{
		$posts[] = form_contact_admin();
		}
	}
?>

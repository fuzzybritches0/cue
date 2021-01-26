<?php
function register_new_user()
	{
	$register_key = libcue_session_key();
	$register['email'] = $_REQUEST['email'];
	$register['time'] = utf8_encode(time());
	$message = "Super! Du hast den ersten Schritt erfolgreich abgeschlossen. Jetzt musst du nur noch einen Benutzernamen und ein sicheres Passwort auswählen, " .
			"sowie deinen richtigen Namen angeben. Dies kannst du tun indem du den untenstehenden Link anklickst oder ihn in die Addresszeile deines " .
			"Browsers kopierst!\r\n\r\n" . $GLOBALS['REMOTE_HTTP_PATH'] . "?command=register&key=" . $register_key;
	if ( send_system_email($_REQUEST['email'], "Registrierung: " . $GLOBALS['REMOTE_HTTP_PATH'], $message) )
		{
		libcue_fsdb_save_variables("_ZZSYS_register/" . $register_key, $register);
		}
	else
		{
		libcue_session_increase_fail_count_('ip');
		libcue_error_message("Die Email konnte nicht an die angegebene Email-Addresse übermittelt werden! Überprüfe doch noch einmal deine Angaben! " .
				"Sollte das Problem weiter bestehen, kontaktiere bitte den Administrator!");
		}
	}
function form_register()
	{
	$variables = array("email", "regpass");
	$options['action'] = $REMOTE_HTTP_PATH . "?command=register&option=submit";
	$desc['regpass'] = "Registrierungskennwort"; $desc['email'] = "Email-Addresse";
	$maxlength['regpass'] = "16"; $maxlength['email'] = "64";
	$options['label'] = "Registrieren";
	$mode['regpass'] = "text"; $mode['email'] = "text";
	return libcue_draw_form($options, $variables, $desc, $mode, $maxlength);
	}
function form_register_2()
	{
	$variables = array("real_name", "user_name", "password", "password_confirm");
	$options['action'] = $REMOTE_HTTP_PATH . "?command=register&option=save&key=" . $_REQUEST['key'];
	$desc['real_name'] = "Name"; $desc['user_name'] = "Benutzername"; $desc['password'] = "Passwort";
	$desc['password_confirm'] = "Passwort wiederholen"; $options['label'] = "Speichern";
	$maxlength['user_name'] = "16"; $mode['real_name'] = "text"; $mode['user_name'] = "text";
	$mode['password'] = "password"; $mode['password_confirm'] = "password";
	return libcue_draw_form($options, $variables, $desc, $mode, $maxlength);
	}

$current_page="login";
if ( ! isset($_SESSION['user_id']) )
	{
	$menuitems[] = libcue_html_menu_item_array("Anmelden", $REMOTE_HTTP_PATH . "?command=login", "login");
	if ( $_REQUEST['option'] === "save" && isset($_REQUEST['key']) )
		{
		if ( libcue_directory_exists("_ZZSYS_register", $_REQUEST['key']) )
			{
			$reg_vars_val = array("real_name", "user_name", "password");
			$reg_vars['real_name'] = $_REQUEST['real_name'];
			$reg_vars['user_name'] = $_REQUEST['user_name'];
			$reg_vars['password'] = $_REQUEST['password'];
			$reg_var_mode['user_name'] = "file_name"; $reg_var_mode['password'] = "password";
			$reg_desc['user_name'] = "Benutzername"; $reg_desc['password'] = "Passwort"; $reg_desc['real_name'] = "Name";
			$reg_min_len['user_name'] = "4"; $reg_max_len['user_name'] = "12"; $reg_min_len['real_name'] = "7";
			libcue_validate_input($reg_vars_val, $reg_var_mode, $reg_desc, $reg_min_len, $reg_max_len);
			libcue_validate_input_password($_REQUEST['user_name'], $_REQUEST['real_name'], $_REQUEST['password'], $_REQUEST['password_confirm']);
			if ( libcue_directory_exists( "user", $_REQUEST['user_name']) )
				{
				libcue_error_message("Der Benutzername ist bereits vergeben!");
				}
			if ( libcue_error_occurred() )
				{
				$posts[] = libcue_html_headline("Registrierung - Schritt 2/2") . form_register_2();
				}
			else
				{
				$reg1_vars = libcue_fsdb_load_variables("_ZZSYS_register/" . $_REQUEST['key']);
				$reg_vars['user_id'] = libcue_session_key("user/");
				$reg_vars['email'] = $reg1_vars['email'];
				$reg_vars['active'] = "deactivated";
				$reg_vars['registered'] = "1";
				$reg_vars['password_confirm'] = "";
				libcue_salt_create($reg_vars['user_id']);
				libcue_fsdb_save_variables("user/" . $reg_vars['user_id'], $reg_vars, $reg_var_mode);
				symlink( "./" . $reg_vars['user_id'], $GLOBALS['DATABASE_PATH'] . "user/" . $reg_vars['user_name']);
				symlink( "../user/" . $reg_vars['user_id'], $GLOBALS['DATABASE_PATH'] . "user_name/" . $reg_vars['user_name']);
				symlink( "./" . $reg_vars['user_id'], $GLOBALS['DATABASE_PATH'] . "user/" . $reg_vars['email']);
				$posts[] = libcue_html_headline("Super! Du hast es geschafft!") . libcue_html_paragraph("Du hast dich erfolgreich angemeldet! " .
					"Der Administrator wird sich gleich um deine Anmeldung kümmern und dein Konto freischalten. Du wirst über deine " .
					"Email-Addresse darüber informiert! Bitte habe noch etwas Geduld, bevor du dich anmeldest!");
				libcue_fsdb_delete("_ZZSYS_register/" . $_REQUEST['key']);
				libcue_session_notify_admin("NEW USER REGISTERED: " . $reg_vars['real_name'] . " WITH USERNAME: " . $reg_vars['user_name']);
				libcue_session_log("NEW USER REGISTERED: " . $reg_vars['real_name'] . " WITH USERNAME: " . $reg_vars['user_name']);
				}
			}
		}
	if ( isset($_REQUEST['key']) && $_REQUEST['option'] !== "save" )
		{
		if ( libcue_directory_exists("_ZZSYS_register", $_REQUEST['key']) )
			{
			$posts[] = libcue_html_headline("Registrierung - Schritt 2/2") . form_register_2();
			}
		}
	else
		{
		if ( $_REQUEST['option'] === "submit" )
			{
			$regpass = libcue_fsdb_load_variable("_ZZSYS_reg_settings", 'regpass');
			if ( $regpass === $_REQUEST['regpass'] )
				{
				libcue_validate_input('email', 'email_adress', 'Email-Addresse');
				if ( ! libcue_error_occurred() )
					{
					$registrations=glob($GLOBALS['DATABASE_PATH'] . "/_ZZSYS_register/*");
					foreach ( $registrations as $registration )
						{
						if ( $_REQUEST['email'] === libcue_fsdb_load_variable($registration, "email") )
							{
							libcue_error_message("Die Email-Addresse ist bereits registriert!");
							}
						}
					if ( libcue_directory_exists("user", $_REQUEST['email']) )
						{
						libcue_error_message("Die Email-Addresse ist bereits registriert!");
						}
					}
				if ( ! libcue_error_occurred() )
					{
					libcue_validate_email($_REQUEST['email']);
					}
				if ( ! libcue_error_occurred() )
					{
					register_new_user();
					}
				if ( ! libcue_error_occurred() )
					{
					$posts[] = libcue_html_headline("Registrierung - Schritt 1/2") . libcue_html_headline("Alles ok!") .
						libcue_html_paragraph("Dir wurde soeben eine Nachricht an deine Email-Addresse geschickt. " .
						"In der Nachricht erfährst du wie du weiter vorgehen musst.");
					}
				if ( libcue_error_occurred() )
					{
					libcue_session_increase_fail_count_('ip');
					$posts[] = libcue_html_headline("Registrierung - Schritt 1/2") . form_register();
					}
				}
			else
				{
				libcue_session_increase_fail_count_('ip');
				libcue_error_message("Das Kennwort ist nicht korrekt. Bitte kontaktiere deinen Administrator damit er dir helfen kann!");
				$posts[] = libcue_html_headline("Registrierung - Schritt 1/2") . form_register();
				}
			}
		elseif ( ! isset($_REQUEST['option']) )
			{
			$posts[] = libcue_html_headline("Registrierung - Schritt 1/2") . libcue_html_paragraph("Bitte gib deine Email-Addresse an, mit der du dich
				registrieren willst. Du musst auch das Kennwort angeben, das dir der Administrator verraten hat.") . form_register();
			}
		}
	}

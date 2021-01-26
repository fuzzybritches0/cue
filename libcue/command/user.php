<?php
if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'admin,planen', 'yes') )
	{
	$current_page = "useradmin";
	$sidebar_page_action = "?command=user";
	require $LIBCUE_PATH . "sidebar/user.php";
	$posts[] = libcue_html_headline("Benutzerverwaltung");
	}
function list_users()
	{
	$return_post = libcue_html_paragraph("Benutzer hinzufügen, Benutzer bearbeiten, " .
				"Benutzer löschen") . libcue_html_paragraph( libcue_html_link( "Neuen Benutzer hinzufügen",
				$REMOTE_HTTP_PATH . "?command=user&option=edit") . " | " . libcue_html_link( "Registrierungskennwort ändern",
				$REMOTE_HTTP_PATH . "?command=user&option=regpassform") );
	$options['search'] = $search;
	$options['page'] = $page;
	$options['directory'] = "user_name";
	$options['show_file_name'] = FALSE;
	$variables = array( "real_name", "user_name", "initial", "role", "group", "email", "active" );
	$var_search = array( "real_name", "user_name", "initial", "role", "group", "email", "active" );
	$cell_desc = array( "Name", "Benutzername", "Initiale", "Rolle(n)", "Gruppe(n)", "Email-Adresse", "aktiv", "de/aktivieren", "bearbeiten");
	$action[] = "?command=user&option=deactivate&user=";
	$action[] = "?command=user&option=edit&user=";
	$options['page_action'] = "?command=user&option=list";
	$options['char_index'] = TRUE;
	$users = libcue_fsdb_list("user_name", "role,group", $_SESSION['user.role'] . "," . $_SESSION['user.group']);
	if ( isset($users[0]) )
		{
		foreach ( $users as $user )
			{
			$files[] = libcue_fsdb_load_variable("user/" . $user, "user_name");
			}
		}
	return $return_post . libcue_table_list($files ,$options, $variables, $cell_desc, $action, $var_search);
	}
if ( $_REQUEST['option'] === "edit" )
	{
	require $LIBCUE_PATH . "form/edit_user.php";
	}
elseif ( $_REQUEST['option'] === "saveregpass" && libcue_session_user_is($_SESSION['user_id'],'role', 'admin', 'yes') )
	{
	$reg_var_save['regpass'] = $_REQUEST['regpass'];
	libcue_fsdb_save_variables("_ZZSYS_reg_settings", $reg_var_save);
	}
elseif ( $_REQUEST['option'] === "regpassform" )
	{
	$variables_reg = array("regpass");
	$options_reg['action'] = $REMOTE_HTTP_PATH . "?command=user&option=saveregpass";
	$var_desc_reg['regpass'] = "Registrierungskennwort";
	$max_length_reg['regpass'] = "16";
	$options_reg['file'] = "_ZZSYS_reg_settings";
	$options_reg['label'] = "Speichern";
	$var_mode_reg['regpass'] = "text";
	$posts[] = libcue_draw_form($options_reg, $variables_reg, $var_desc_reg, $var_mode_reg, $max_length_reg);
	}
elseif ( $_REQUEST['option'] === "deactivate" && libcue_directory_exists("user", $_REQUEST['user']) &&
  libcue_session_user_is($_SESSION['user_id'],'role', 'admin') || $_REQUEST['option'] === "deactivate" &&
	libcue_directory_exists("user", $_REQUEST['user']) && libcue_session_user_is($_SESSION['user_id'],'role', 'planen') &&
	libcue_session_user_is($_REQUEST['user'], 'role', 'klient') )
	{
	$user_id = libcue_fsdb_load_variable("user/" . $_REQUEST['user'], "user_id");
	$email = libcue_fsdb_load_variable("user/" . $user_id, "email");
	$status = libcue_fsdb_load_variable("user/" . $user_id, "active");
	$registered = libcue_fsdb_load_variable("user/" . $user_id, "registered");
	if ( $status === "deactivated" )
		{
		if ( $registered === "1" )
			{
			$message = "Dein Konto auf " . $GLOBALS['REMOTE_HTTP_PATH'] . " wurde freigeschaltet! Jetzt kannst du dich anmelden! Viel Spaß!";
			if ( isset($email) )
				{
				if ( ! send_system_email($email, "Kontofreischaltung: " . $GLOBALS['REMOTE_HTTP_PATH'], $message) )
					{
					libcue_log("ERROR SENDING MESSAGE VIA EMAIL TO " . $email);
					libcue_error_message_warning("Die Email zur Aktivierung des Kontos konnte nicht versendet werden!");
					}
				else
					{
					$variables['registered'] = "";
					}
				}
			}
		$new_status = "active";
		}
	elseif ( $status === "active") { $new_status = "deactivated"; }
	$variables['active'] = $new_status;
	libcue_fsdb_save_variables("user/" . $user_id, $variables);
	}
elseif ( $_REQUEST['option'] === "save" && libcue_session_user_is($_SESSION['user_id'],'role', 'admin,planen', 'yes') )
	{
	if ( libcue_directory_exists("user", $_REQUEST['user']) || $_REQUEST['password'] === "" && $_REQUEST['password_confirm'] === ""  )
		{
		$variables = array( "real_name", "user_name", "initial", "role", "group", "email" );
		}
	else
		{
		$variables = array( "real_name", "user_name", "initial", "role", "group", "email", "password", "password_confirm" );
		}
	$description['real_name'] = "Name"; $description['user_name'] = "Benutzername"; $description['initial'] = "Initiale";
	$description['email'] = "Email"; $description['password'] = "Passwort";
	$var_mode['user_name'] = "file_name"; $min_len['user_name'] = "4"; $max_len['user_name'] = "12";
	$var_mode['initial'] = "initial"; $max_len['initial'] = "2";
	$var_mode['email'] = "email_adress_opt";
	$var_mode['password'] = "password";
	$var_mode['password_confirm'] = "password";
	$variables = libcue_validate_input( $variables, $var_mode, $description, $min_len, $max_len );
	if ( libcue_directory_exists("user", $_REQUEST['user']) )
		{
		$old_vars = libcue_fsdb_load_variables( "user/" . $_REQUEST['user']);
		$variables['user_id'] = $old_vars['user_id'];
		}
	else
		{
		libcue_validate_input_password($variables['user_name'], $variables['real_name'], $variables['password'], $variables['password_confirm']);
		}
	if ( in_array($variables['initial'], libcue_session_gather("role")) ||
		in_array($variables['initial'], libcue_session_gather("group")) )
			{
			libcue_error_message("Die Initziale steht nicht zur Verfügung!");
			}
	if ( in_array($variables['user_name'], libcue_session_gather("role")) ||
		in_array($variables['user_name'], libcue_session_gather("group")) )
			{
			libcue_error_message("Benutzername steht nicht zur Verfügung!");
			}
	$role = explode(",", $variables['role']); $group = explode(",", $variables['group']);
	$role = libcue_trim_array($role); $group = libcue_trim_array($group);
	$users = libcue_session_gather_users_names();
	$initials = libcue_session_gather("initial");
	if ( strlen($group[0]) > 0 )
		{
		foreach ( $group as $group_ )
			{
			if ( in_array($group_, $initials) )
				{
				libcue_error_message("Der Gruppenname '" . $group_ . "' steht nicht zur Verfügung!");
				}
			if ( in_array($group_, $users) )
				{
				libcue_error_message("Der Gruppenname '" . $group_ . "' steht nicht zur Verfügung!");
				}
			if ( preg_match("/^[a-zA-Z0-9]{2,15}$/", $group_) !== 1 )
				{
				libcue_error_message("Der Gruppenname '" . $group_ . "' ist nicht gültig! a-Z,0-9, 2-15 Zeichen");
				}
			if ( in_array($group_, libcue_session_gather("role")) )
				{
				libcue_error_message("Der Gruppenname '" . $group_ . "' steht nicht zur Verfügung!");
				}
			if ( trim($variables['user_name']) === $group_ )
				{
				libcue_error_message("Der Gruppenname darf nicht mit dem Benutzername übereinstimmen!");
				}
			if ( $group_ === $variables['initial'] )
				{
				libcue_error_message("Gruppennamen dürfen nicht mit der Initiale übereinstimmen!");
				}
			foreach ( $role as $role_ )
				{
				if ( $role_ === $group_ )
					{
					libcue_error_message("Gruppennamen und Rollennamen dürfen nicht übereinstimmen!");
					}
				}
			}
		}
	if ( strlen($role[0]) > 0 )
		{
		foreach ( $role as $role_ )
			{
			if ( in_array($role_, $initials) )
				{
				libcue_error_message("Der Rollenname '" . $role_ . "' steht nicht zur Verfügung!");
				}
			if ( $role_ === $variables['initial'] )
				{
				libcue_error_message("Rollennamen dürfen nicht mit der Initiale übereinstimmen!");
				}
			if ( in_array($role_, $users) )
				{
				libcue_error_message("Der Rollenname '" . $role_ . "' steht nicht zur Verfügung!");
				}
			if ( preg_match("/^[a-zA-Z0-9]{2,15}$/", $role_) !== 1 )
				{
				libcue_error_message("Der Rollenname '" . $role_ . "' ist nicht gültig! a-Z,0-9, 2-15 Zeichen");
				}
			if ( in_array($role_, libcue_session_gather("group")) )
				{
				libcue_error_message("Der Rollenname '" . $role_ . "' steht nicht zur Verfügung!");
				}
			if ( trim($variables['user_name']) === $role_ )
				{
				libcue_error_message("Der Rollenname darf nicht mit dem Benutzername übereinstimmen!");
				}
			}
		}
	if ( libcue_directory_exists("user", $variables['user_name']) && $old_vars['user_name'] !== $variables['user_name'] )
			{
			libcue_error_message("Benutzername steht nicht zur Verfügung!");
			}
	if ( libcue_directory_exists("user", $variables['initial']) && $old_vars['initial'] !== $variables['initial'] )
			{
			libcue_error_message("Initiale steht nicht zur Verfügung!");
			}
	if ( libcue_directory_exists("user", $variables['email']) && $old_vars['email'] !== $variables['email'] )
			{
			libcue_error_message("Die Email-Adresse ist bereits registriert!");
			}
	if ( libcue_error_occurred() )
		{
		require $LIBCUE_PATH . "/form/edit_user.php";
		}
	else
		{
		if ( libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
			{
			if ( isset($old_vars['role']) && libcue_session_user_is($old_vars['user_id'], 'role', 'klient') )
				{
				$variables['role'] = $old_vars['role'];
				}
			elseif ( ! isset($old_vars['role']) ) { $variables['role'] = "klient"; }
			elseif ( isset($old_vars['role']) && ! libcue_session_user_is($old_vars['user_id'], 'role', 'klient') )
				{
				$variables = $old_vars;
				libcue_error_message("Du kannst die Daten des Benutzers nicht ändern!");
				}
			}
		if ( isset($old_vars['user_name']))
			{
			if ( $old_vars['user_name'] !== $variables['user_name'] )
				{
				unlink($GLOBALS['DATABASE_PATH'] . "user/" . $old_vars['user_name']);
				unlink($GLOBALS['DATABASE_PATH'] . "user_name/" . $old_vars['user_name']);
				}
			if ( $old_vars['initial'] !== $variables['initial'] && libcue_directory_exists("user", $old_vars['initial']) )
				{
				unlink($GLOBALS['DATABASE_PATH'] . "user/" . $old_vars['initial']);
				}
			if ( $old_vars['email'] !== $variables['email'] && libcue_directory_exists("user", $old_vars['email']) )
				{
				unlink($GLOBALS['DATABASE_PATH'] . "user/" . $old_vars['email']);
				}
			}
		else
			{
			$variables['user_id'] = libcue_session_key("user/");
			}
		if ( ! libcue_directory_exists( "salt", $variables['user_id']) && isset($variables['password']) )
			{
			libcue_salt_create($variables['user_id']);
			}
		if ( ! isset($old_vars['active']) )
			{
			$variables['active'] = "deactivated";
			}
		else
			{
			$variables['active'] = $old_vars['active'];
			}
		$variables['password_confirm'] = "";
		libcue_fsdb_save_variables( "user/" . $variables['user_id'], $variables, $var_mode);
		unset($_REQUEST['user']);
		if ( ! libcue_directory_exists("user", $variables['user_name']) )
			{
			symlink( "./" . $variables['user_id'], $GLOBALS['DATABASE_PATH'] . "user/" . $variables['user_name']);
			symlink( "../user/" . $variables['user_id'], $GLOBALS['DATABASE_PATH'] . "user_name/" . $variables['user_name']);
			}
		if ( strlen($variables['initial']) > "0" && ! libcue_directory_exists("user", $variables['initial']) )
			{
			symlink( "./" . $variables['user_id'], $GLOBALS['DATABASE_PATH'] . "user/" . $variables['initial']);
			}
		if ( strlen($variables['email']) > "0" && ! libcue_directory_exists("user", $variables['email']) )
			{
			symlink( "./" . $variables['user_id'], $GLOBALS['DATABASE_PATH'] . "user/" . $variables['email']);
			}
		if ( $_SESSION['user_id'] === $variables['user_id'] )
			{
			unset($_SESSION['initial']);
			$_SESSION = libcue_fsdb_load_variables( "user/" . $variables['user_id']);
			}
		}
	}
	if ( libcue_session_user_is($_SESSION['user_id'],'role', 'admin') || libcue_session_user_is($_SESSION['user_id'], 'role', 'planen') )
		{
		$posts[] = list_users();
		}
?>

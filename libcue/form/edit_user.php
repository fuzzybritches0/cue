<?php
if ( libcue_directory_exists( "user", $_REQUEST['user']) ) 
	{
	$var_existing_user = libcue_fsdb_load_variables( "user/" . $_REQUEST['user']);
	$existing_user = "&user=" . $var_existing_user['user_id'];
	$options['file'] = "user/" . $var_existing_user['user_id'];
	$variables = array( "real_name", "user_name", "initial", "email", "role", "group");
	}
else
	{
	$var_desc['password'] = "Passwort"; $var_mode['password'] = "password";
	$var_desc['password_confirm'] = "Wiederholen"; $var_mode['password_confirm'] = "password";
	$variables = array( "real_name", "user_name", "initial", "email", "role", "group", "password", "password_confirm" );
	}
$options["label"] = "Speichern";
$options["action"] = $REMOTE_HTTP_PATH . "?command=user&option=save" . $existing_user;
$var_desc['real_name'] = "Name"; $var_mode["real_name"] = "text";
$var_desc['user_name'] = "Benutzername"; $var_mode["user_name"] = "text"; $maxlength['user_name'] = "12";
$var_desc['initial'] = "Initiale"; $var_mode["initial"] = "text"; $maxlength['initial'] = "2";
$var_desc['email'] = "Email-Adresse"; $var_mode['email'] = "text";
$var_desc['role'] = "Rolle(n)"; $var_mode['role'] = "text";
$var_desc['group'] = "Gruppe(n)"; $var_mode['group'] = "text";
$posts[] = libcue_draw_form( $options, $variables, $var_desc, $var_mode, $maxlength );
?>

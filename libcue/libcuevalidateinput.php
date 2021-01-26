<?php
function libcue_validate_input_password($user_name, $real_name, $password, $password_confirm)
	{
	if ( $password_confirm !== $password )
		{
		libcue_error_message("Passwort: Keine Übereinstimmung der beiden Felder!");
		return;
		}
	$weak_pass = explode(" ", strtoupper($real_name));
	$weak_pass[] = strtoupper($user_name);
	$weak_pass[] = "PASSW"; $weak_pass[] = "123"; $weak_pass[] = "345"; $weak_pass[] = "567"; $weak_pass[] = "789";
	$weak_pass[] = "987"; $weak_pass[] = "765"; $weak_pass[] = "543"; $weak_pass[] = "321";
	$weak_pass[] = "696"; $weak_pass[] = "WER"; $weak_pass[] = "ASD"; $weak_pass[] = "YXC"; $weak_pass[] = "ZXC";
	$weak_pass[] = "PASW";
	foreach ( $weak_pass as $weak )
		{
		if ( strlen($weak) > 0 )
			{
			if ( strpos(strtoupper($password), $weak) !== FALSE )
				{
				libcue_error_message("Passwort: Das Passwort könnte leicht zu erraten sein! Bitte wähle ein anderes Passwort! " .
					"Versuche leicht zu erratende Teile deines Passwortes mit schwierigen Teilen zu ersetzen.");
				return;
				}
			}
		}
	}

function libcue_validate_email($email)
	{
	$pos = strpos($email, "@");
	$address = substr($email, $pos+1, strlen($email));
	require $GLOBALS['LIBCUE_PATH'] . "shitmail.php";
	foreach ( $shitmail as $shit )
		{
		if ( strtolower($address) == $shit )
			{
			libcue_error_message("Die Email-Addresse die du angegeben hast scheint eine unsichere zu sein! Bitte gib eine Email-Addresse an, " .
				"die sicher ist!");
			return;
			}
		}
	$email_host_ip = gethostbyname($address) . ".";
	if ( $email_host_ip === $address . "." )
		{
		libcue_error_message("Die Email-Addresse die du angegeben hast, scheint nicht zu existieren! " . $address .
			" konnte nicht aufgelöst werden! Bitte kontaktiere den Administrator, sollte das Problem weiter bestehen!");
		}
	}

function libcue_directory_exists( $directory, $directory_basename )
	{
	if ( strlen($directory_basename) > 0  && strlen($directory) > 0 &&
		preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9_-]$|^[a-zA-Z]$/", $directory_basename) === 1 &&
		is_dir($GLOBALS['DATABASE_PATH'] . $directory . "/" . basename($directory_basename)) )
		{
		return TRUE;
		}
	return FALSE;
	}

function libcue_user_is_active( $user, $warning="" )
	{
	$active = libcue_fsdb_load_variable("user/" . $user, "active");
	if ( $active === "active" )
		{
		return TRUE;
		}
	else
		{
		if ( $warning === "" )
			{
			libcue_error_message("Der Benutzer: " . libcue_fsdb_load_variable("user/" . $user, "user_name") . " wurde deaktiviert!");
			}
		return FALSE;
		}
	}

function libcue_validate_input( $variables, $mode, $description, $min_len="", $max_len="" )
{
$err_msg["checkbox"] = "Programmaufruf mit unerwarteter Eingabe!";
$err_msg["password"] = "Passwort: mind. 8 Zeichen, mind. 1 GROSS-, klein-Buchstabe und Ziffer!";
$err_msg["email_adress"] = $err_msg["email_adress_opt"] = "Email-Addresse: ungültige Eingabe!";
$regex["password"] = "/^.*(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,42}$/";
$regex["checkbox"] = "/^checked$|^$/";
$regex["email_adress"] = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-.]+\.[a-zA-Z.]{2,6}$/";
$regex["email_adress_opt"] = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-.]+\.[a-zA-Z.]{2,6}$|^$/";
if ( ! is_array($variables) )
	{
	$variables = array($variables);
	$mode = array($variables[0] => $mode);
	$description = array($variables[0] => $description);
	if ( $min_len != "" ) { $min_len = array($variables[0] => $min_len); }
	if ( $max_len != "" ) { $max_len = array($variables[0] => $max_len); }
	}
foreach( $variables as $array_count => $var_name )
	{
	if ( !isset($min_len[$var_name]) ) { $min_len[$var_name] = 0; }
	if ( !isset($max_len[$var_name]) ) { $max_len[$var_name] = 240; }
	if ( !isset($mode[$var_name]) ) { $mode[$var_name] = "length"; }
	$regex["initial"] = "/^[a-z]{0,2}$/";
	$regex["file_name"] = "/^[a-z0-9]{" . $min_len[$var_name] . "," . $max_len[$var_name] . "}$/";
	$regex["integer"] = "/^[0-9]{" . $min_len[$var_name] . "," . $max_len[$var_name] . "}$/";
	$regex["length"] = "/^[\s\S]{" . $min_len[$var_name] . "," . $max_len[$var_name] . "}$/";
	$err_msg["initial"] = $description[$var_name] . ": Maximal 2 Zeichen (a-z)!";
	$err_msg["file_name"] = $description[$var_name] . ": Erlaubte Zeichen: (a-z0-9)" .
	" mind. " . $min_len[$var_name] . " Zeichen, max. " . $max_len[$var_name] . " Zeichen!";
	$err_msg["integer"] = $description[$var_name] . ": Erlaubte Zeichen: (0-9) " .
	" mind. " . $min_len[$var_name] . " Zeichen, max. " . $max_len[$var_name] . " Zeichen!";
	$err_msg["length"] = $description[$var_name] . ":" . " mind. " . $min_len[$var_name] . " Zeichen, max. " .
	$max_len[$var_name] . " Zeichen!";
	if ( preg_match($regex[$mode[$var_name]], $_REQUEST[$var_name]) === 1 )
		{
		$return_variables[$var_name] = $_REQUEST[$var_name];
		}
	else
		{
		libcue_error_message( $err_msg[$mode[$var_name]]);
		}
	}
return $return_variables;
}
?>

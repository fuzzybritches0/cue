<?php

function make_id($action)
	{
	$qm_pos = strpos($action, "?");
	$action = substr($action, $qm_pos + 1, strlen($action) - ($qm_pos + 1));
	$reqs = explode("&", $action);
	foreach ( $reqs as $req )
		{
		$temp = explode("=", $req);
		$id = $id . $temp[1];
		}
	return $id;
	}
function get_to_post($action)
	{
	$qm_pos = strpos($action, "?");
	$action = substr($action, $qm_pos + 1, strlen($action) - ($qm_pos + 1));
	$reqs = explode("&", $action);
	foreach ( $reqs as $req )
		{
		$temp = explode("=", $req);
		$form = $form . "<input type='hidden' name='" . $temp[0] . "' value='" . $temp[1] . "' />";
		}
	if ( isset($GLOBALS['windowid']) && isset($_SESSION['user_id']) )
		{
		$form = $form . "<input type='hidden' name='content_only' value='true' />";
		$form = $form . "<input type='hidden' name='windowid' value='" . $GLOBALS['windowid'] . "' />";
		}
	return $form;
	}
function libcue_form_text_area( $name, $value="", $cols=26, $rows=4, $wrap="soft" )
	{
	if ( isset($_SESSION['REQUEST_ERROR_' . $name]) )
		{
		$value = $_SESSION['REQUEST_ERROR_' . $name];
		unset($_SESSION['REQUEST_ERROR_' . $name]);
		}
	$color = "white";
	if ( isset($_SESSION['color.' . $name]) ) { $color = $_SESSION['color.' . $name]; }
	return "<textarea name='" . $name . "' cols='" . $cols . "' rows='" . $rows . "' wrap='" . $wrap . "' style='background-color: " . $color . ";'>" .
		$value . "</textarea>";
	}
function libcue_form_text( $name, $maxlength, $value="" )
	{
	if ( isset($_SESSION['REQUEST_ERROR_' . $name]) )
		{
		$value = $_SESSION['REQUEST_ERROR_' . $name];
		unset($_SESSION['REQUEST_ERROR_' . $name]);
		}
	$width = .67 * $maxlength;
	if ( $width > 18) { $width = 18; }
	if ( $width < 2 ) { $width = 2; }
	$color = "white";
	if ( isset($_SESSION['color.' . $name]) ) { $color = $_SESSION['color.' . $name]; }
	return "<input name='" . $name . "' type='text' value='" . $value . "' style=' background-color: " . $color . "; width:" . $width . "em'  maxlength='"
		. $maxlength . "'/>";
	}

function libcue_form_conclude_input($input, $action, $label="Speichern")
	{
	$action_form = get_to_post($action);
	$id = make_id($action);
	return "<form id='" . $id . "' action=\"javascript:sendData('" . $id . "');\">" .
		$input . $action_form . "<input type='submit' value='" . $label . "'/>" . "</form>";
	}
function libcue_draw_form($options, $variables, $var_desc, $var_mode, $maxlength="")
{
if ( ! is_array($variables) )
	{
	$variables[] = $variables;
	$var_desc[] = $var_desc;
	$var_mode[] = $var_mode;
	$maxlength[] = $maxlength;
	}
if ( isset($options['file']) )
	{
	$variables_content = libcue_fsdb_load_variables($options['file']);
	}
if ( is_array($options['var_content']) )
	{
	foreach ( $options['var_content'] as $var_name => $var_content )
		{
		$variables_content[$var_name] = $var_content;
		}
	}
if ( $options['filename_is_name'] === TRUE ) { $variables_content['name'] = basename($options['file']); }
$action = get_to_post($options['action']);
$id = make_id($options['action']);
if ( isset($_SESSION['user_id']) )
	{
	$form = $form . "<form id='" . $id . "' action=\"javascript:sendData('" . $id . "');\">";
	}
else
	{
	$form = $form . "<form method='post' id='" . $id . "' action='" . $GLOBALS['REMOTE_HTTP_PATH'] . "'\>";
	}
$table_open = FALSE;
foreach($variables as $variable)
	{
	unset($ERROR_MSG);
	$content = $_REQUEST[$variable];
	if ( isset($options['file']) || is_array($options['var_content']) )
		{
		$content = $variables_content[$variable];
		}
#	else
#		{
#		$content = $_REQUEST[$variable];
#		}
	if ( isset($GLOBALS['ERROR_MSG_' . $variable]) )
		{
		$content = $GLOBALS['REQUEST_ERROR_' . $variable];
		unset($GLOBALS['REQUEST_ERROR_' . $variable]);
		$ERROR_MSG = "<span style='color=#FF0000;'>" . $GLOBALS['ERROR_MSG_' . $variable] . "</span>";
		unset($GLOBALS['ERROR_MSG_' . $variable]);
		}
	switch ($var_mode[$variable])
		{
		case 'text':
		if ( $table_open === FALSE )
			{
			$form = $form . "<table id='noborder'><tbody>";
			$table_open = TRUE;
			}
		if ( ! isset($maxlength[$variable]) ) { $maxlength[$variable] = 1600; }
		if ( $maxlength[$variable] >= 19 ) { $size = 19; } else { $size = $maxlength[$variable]; }
		$form = $form . "<tr><td id='noborder'><b>" . $var_desc[$variable] . ":</b></td><td id='noborder'><input name='" . $variable .
		"' type='text' value='" . $content . "' size='" . $size . "' maxlength='" . $maxlength[$variable] . "'/></td><td id='noborder'>" . $ERROR_MSG . "</td></tr>";
		break;
		case 'textareas':
		if ( $table_open === TRUE )
			{
			$form = $form . "</tbody></table>";
			$table_open = FALSE;
			}
		$form = $form . "<p><b>" . $var_desc[$variable] . ":</b><br><textarea name='" . $variable .
		"' cols='38' rows='3' wrap='soft'>" . $content . "</textarea></p>" . "<br>" . $ERROR_MSG;
		break;
		case 'textarea':
		if ( $table_open === TRUE )
			{
			$form = $form . "</tbody></table>";
			$table_open = FALSE;
			}
		$form = $form . "<p><b>" . $var_desc[$variable] . ":</b><br><textarea name='" . $variable .
		"' cols='38' rows='15' wrap='soft'>" . $content . "</textarea></p>" . "<br>" . $ERROR_MSG;
		break;
		case 'checkbox':
		if ( $table_open === FALSE )
			{
			$form = $form . "<table id='noborder'><tbody>";
			$table_open = TRUE;
			}
		$form = $form .  "<tr><td id='noborder'><b>" . $var_desc[$variable] . "</b></td><td id='noborder'><input type='checkbox' name='" . $variable .
		"' value='checked' " . $content . "/></td><td id='noborder'>" . $ERROR_MSG . "</td></tr>";
		break;
		case 'password':
		if ( $table_open === FALSE )
			{
			$form = $form . "<table id='noborder'><tbody>";
			$table_open = TRUE;
			}
		$form = $form . "<tr><td id='noborder'><b>" . $var_desc[$variable] . ":</b></td><td id='noborder'><input name='" . $variable .
		"' type='password' value='' size='" . $size . "' maxlength='16'/></td><td id='noborder'>" . $ERROR_MSG . "</td></tr>";
		break;
		default:
		if ( $table_open === FALSE )
			{
			$form = $form . "<table id='noborder'><tbody>";
			$table_open = TRUE;
			}
		$form = $form . "<tr><td id='noborder'><b>" . $var_desc[$variable] . ":</b></td><td id='noborder'><select name='" . $variable . "' size='1'>";
		foreach($var_mode[$variable] as $option_value => $display_value)
			{
			$selected = "";
			if ( $content === $option_value )
				{
				$selected = "selected";
				}
			$form = $form . "<option value='" . $option_value . "' " . $selected . ">" . $display_value . "</option>";
			}
			$form = $form . "</select></td><td id='noborder'>" . $ERROR_MSG . "</td></tr>";
		}
	}

if ( $table_open === TRUE )
	{
	$form = $form . "</tbody></table>";
	}
$form = $form . $action . "<input style='font-size: 1.8em;' type='submit' value='" . $options['label'] . "'/></form>";
return $form;
}
?>

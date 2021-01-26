<?php
function columns_to_rows($variables)
	{
	foreach ( $variables as $variable )
		{
		$counter2 = 0;
		if ( is_array($variable) )
			{
			foreach ( $variable as $cell )
				{
				$variables2[$counter2][$counter1] = $cell;
				$counter2++;
				}
			}
		$counter1++;
		}
	return $variables2;
	}
function libcue_table( $variables, $headers="", $columns_or_rows="columns")
	{
	$table = "<thead><tr>";
	if ( $headers !== "" )
		{
		foreach ( $headers as $header )
			{
			$table = $table .  "<th>" . $header . "</th>";
			}
		$table = $table . "</tr></thead><tbody>";
		}		
	if ( $columns_or_rows === "columns" )
		{
		$variables2 = columns_to_rows($variables);
		}
	else	{ $variables2 = $variables; }
	$count=0;
	foreach ( $variables2 as $variables_row )
		{
		$table = $table . "<tr>";
		foreach ( $variables_row as $variable_column => $content)
			{
			$table = $table . "<td>" . $content . "</td>";
			}
		$table = $table . "</tr>";
		}
	return "<table>" . $table . "<tbody></table>";
	}

function libcue_each_letter_one_line($string)
	{
	for ($i = 0; $i <= strlen($string); $i++)
		{
		$returnstr = $returnstr . mb_substr($string, $i, "1", "utf-8"  ) .  "<br>";
		}
	return $returnstr;
	}

#function libcue_table_form($table_headers, $table_rows, $options, $input_fields_per_cell="1")
#	{
#	$form = "<form method='post' action='" . $options['action'] . "'><table><tr><th>" . $options['row_value'] . "</th>";
#	foreach ($table_headers as $table_header)
#		{
#		$form = $form . "<th>" . libcue_each_letter_one_line($table_header) . "</th>";
#		}
#	$form = $form . "</tr>";
#	$table_row_count = 0;
#	foreach ($table_rows as $table_row)
#		{
#		$table_row_count++;
#		$form = $form . "<tr><td>" . " " . $table_row['day'] . " " . $table_row['weekday'] . "</td>";
#		foreach ($table_headers as $table_header)
#			{
#			$form = $form . "<td><table style='border-width:0px'><tr>";
#			for ( $input_field_nr = 0; $input_field_nr <= $input_fields_per_cell[$table_row_count]; $input_field_nr++)
#				{
#				$form = $form . "<td style='border-width:0px'><input name='" . $table_header . "_" . $table_row['day']  . "_" .
#				$input_field_nr . "' type='text' value='' style='width:1.3em'  maxlength='2'/></td>";
#				}
#			$form = $form . "</tr></table></td>";
#			}
#		$form = $form . "</tr>";
#		}
#	$form = $form . "</table><br><input type='submit' value='Speichern'/>";
#	return $form;
#	}



function libcue_table_list( $file_list, $options, $variables, $cell_desc, $actions, $search_var="" )
{
if ( ! isset($options['page']) )
	{
	if ( isset($_REQUEST['page']) )
		{
		$options['page'] = $_REQUEST['page'];
		}
	else
		{
		$options['page'] = 1;
		}
	}
$entries = 20;
if ( isset($file_list[0]) )
	{
	foreach ( $file_list as $file )
		{
		$files[] = $character = $file;
		$character = strtoupper(substr($character, 0, 1));
		$char_index[$character]++;
		}
	}
$old_value = 1;
if ( isset($char_index) && count($files) > $entries && $options["char_index"] === TRUE )
	{
	$output = "<p>";
	foreach ( $char_index as $character => $amount )
		{
		$output = $output . libcue_html_link($character, $options["page_action"] . "&page=" . ceil($old_value / $entries));	
		$old_value += $amount;
		}
	$output = $output . "</p>";
	}
$file_count = count($files);
$last_page_entries = $file_count % $entries;
$file_count -= $last_page_entries;
$pages = $file_count / $entries;
if ( $last_page_entries !== 0 )
	{
	$pages++;
	}
if ( isset($options['page']) || libcue_validate_input($options["page"], "integer", "Suchanfrage", "1"))
	{
	if ( $options["page"] < 1 || $options["page"] > $pages )
		{
		$options["page"] = 1;
		}
	}
else
	{
	$options["page"] = 1;
	}
$end_count = $options["page"] * $entries;
$begin_count = $end_count - $entries;
$list_page = $options["page"] - 10;
$end_page = $options["page"] +10;
$left_next_page = $list_page - 1;
if ( $left_next_page > 0 )
	{
	$output = $output . libcue_html_link("<<" . $left_next_page, $options["page_action"] . "&page=" . $left_next_page);	
	}
if ( $list_page < 0 )
	{
	$end_page -= $list_page;
	}
if ( $end_page > $pages )
	{
	$list_page -= $end_page - $pages;
	}
for ( $list_page; $list_page<=$end_page; $list_page++ )
	{
	if ( $list_page <= $pages && $list_page > 0 )
		{
		if ( $list_page != $options["page"] )
			{
			$output = $output . libcue_html_link($list_page, $options["page_action"] . "&page=" . $list_page);	
			}
		elseif ( count($files) > $entries )
			{
			$output = $output . "<b>" . $list_page . "</b> ";
			}
		}
	}
$right_next_page = $list_page;
if ( $right_next_page <= $pages )
	{
	$output = $output . libcue_html_link($right_next_page . ">>", $options["page_action"] . "&page=" . $right_next_page);	
	}
if ( isset($files) )
	{
	$output = $output . "<table><thead><tr><th>Zählung</th>";
	foreach ( $cell_desc as $cell )
		{
		$output = $output . "<th>" . $cell . "</th>";
		}
	$output = $output . "</tr></thead><tbody>";
	}
while (  $begin_count !== $end_count && isset($files[$begin_count]) )
	{
	$count_cell = 0;
	$file_var = libcue_fsdb_load_variables($options["directory"] . "/" . $files[$begin_count]);
	$output = $output . "<tr><td>" . $begin_count . "</td>";
	if ( $options["show_file_name"] )
		{
		$output = $output . "<td>" . $files[$begin_count] . "</td>";
		$count_cell++;
		}
	if ( isset($variables) )
		{
		foreach ( $variables as $variable )
			{
			$output = $output . "<td>" . $file_var[$variable] . "</td>";
			$count_cell++;
			}
		}
	if ( isset($actions) )
		{
		foreach ( $actions as $action )
			{
			$output = $output . "<td>" . libcue_html_link($cell_desc[$count_cell], $action . $files[$begin_count]) . "</td>";
			$count_cell++;
			}
		}
	$output = $output . "</tr>";
	$begin_count++;
	}
	if ( ! isset($files[0]) )
		{
		$output = "<br>Keine Einträge vorhanden!";
		}
	else
	{
	$output = $output . "</tbody></table>";
	}
return $output;
}
?>

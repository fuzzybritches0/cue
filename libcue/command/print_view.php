<?php

function Color_Set($color_count)
	{
	if ( $color_count % 2 === 0 )
		{
		$color['r'] = 200;
		$color['g'] = 230;
		$color['b'] = 255;
		}
	else if ( $color_count % 1 === 0 )
		{
		$color['r'] = 255;
		$color['g'] = 255;
		$color['b'] = 255;
		}
	return $color;
	}

function libcue_mobe_collect_print_view( $users, $year, $month)
	{
	$days_in_month[0] = libcue_human_list_days_of_month($month, $year);
	$variables = array();
	foreach ( $users as $user )
		{
		if ( libcue_session_user_is($user, "role", "klient") )
			{
			$client_accompanist = libcue_mobe_roster_list_client($user, $year, $month, $accompanist_fields_only=TRUE);
			$variables = array_merge($variables, $client_accompanist);
			}
		}
	$each_page = array_chunk( $variables, 18);
	$each_user = array_chunk( $users, 9);
	$users_count = 0;
	$pdf = new fpdftable();
	foreach ( $each_page as $page )
		{
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetFont('times','B',18);
		$pdf->Cell(0,10, utf8_decode("Gesamtübersicht " . $year . "-" . $month . " für: " . libcue_session_get_var('schedule.group')),0,1);
		$pdf->SetFont('times', '', 9);
		$page = array_merge($days_in_month, $page);
		$page = columns_to_rows($page);
		$each_user_header[0] = $each_user[$users_count];
		array_unshift($each_user_header[0], "Tag");
		$pdf->Ln();
		$pdf->SetFont('times', 'B', 9);
		$pdf->TableClient("", $each_user_header, array(15,20,20,20,20,20,20,20,20,20));
		$pdf->SetFont('times', '', 9);
		$users_count++;
		$pdf->TableClient("", $page, array(15, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10 ), TRUE);
		}	
	$pdf->Output();
	}



function libcue_mobe_print_view( $users, $year, $month)
	{
	$pdf = new fpdftable();
	foreach ( $users as $user )
		{
		$pdf->AddPage();
		$pdf->AliasNbPages();
		unset($variables); unset($headers);
		$user_vars = libcue_fsdb_load_variables( "user/" . $user );
		$variables[] = libcue_human_list_days_of_month($month, $year);
		if ( libcue_session_user_is($user, "role", "begleiter") )
			{
			$accompanist = libcue_mobe_calculate_accompanist($user, $year, $month);
			$variables[] = $accompanist['accompanist_roster_sorted'];
			$variables[] =  $accompanist['time_worked_add'];
			$headers[] = "Wochentage"; $headers[] = "Dienste"; $headers[] = "Arbeitszeit";
			$pdf->SetFont('times','B',18);
			$pdf->Cell(0,10,utf8_decode("Monatsübersicht " . $year . "-" . $month . " für: " . $user_vars['user_name']),0,1);
			$pdf->Ln();
			$pdf->SetFont('times','', 9);
			$table_lenght = count($variables[0]) + 1;
			$pdf->SetWidths(array( 24, 130, 20));
			$pdf->SetFont('times', 'B', 9);
			$pdf->Row($headers, $headers);
			$pdf->SetFont('times', '', 9);
			for ($i=1; $i<$table_lenght; $i++)
				{
				$pdf->Row(array($variables[0][$i], $variables[1][$i], $variables[2][$i]), $headers);
				}
			$pdf->Cell(0,10,utf8_decode("Gesamtstunden: " . $accompanist['total_time'] . " von " . $accompanist['month_working_contingent']),0,1);
			}
		if ( libcue_session_user_is($user, "role", "klient") )
			{
			$client = libcue_mobe_roster_list_client($user, $year, $month);
			$variables = array_merge($variables, $client);
			$headers[] = "Wochentage"; $headers[] = "FD"; $headers[] = "FD"; $headers[] = "SD"; $headers[] = "SD";
			$headers[] = "FD"; $headers[] = "SD";
			$pdf->SetFont('times','B',18);
			$pdf->Cell(0,10, utf8_decode("Monatsübersicht " . $year . "-" . $month . " für: " . $user_vars['user_name']),0,1);
			$pdf->Ln();
			$pdf->SetFont('times','',9);
			$variables = columns_to_rows($variables);
			$pdf->TableClient($headers, $variables, array( 24, 15, 15, 15, 15, 15, 15 ));
			}
		}

	$pdf->Output();
	}

require($GLOBALS['LIBCUE_PATH'] . "libfpdftable.php");
$page_mode="none";
$users = libcue_mobe_validate_users($_REQUEST['user']);
if ( ! isset($users[0]) ) { $users = libcue_fsdb_list( "user_name" ); }
if ( isset($_REQUEST['month']) && isset($_REQUEST['year']) && libcue_mobe_is_valid_date($_REQUEST['year'] . $_REQUEST['month'] . "01") )
	{
	$month = $_REQUEST['month']; $year = $_REQUEST['year'];
	}
else
	{
	$month = date("m", time()); $year = date("Y", time());
	}
if ( $_REQUEST['option'] === "collect" )
	{
	libcue_mobe_collect_print_view($users, $year, $month);
	}
else
	{
	libcue_mobe_print_view($users, $year, $month);
	}
?>

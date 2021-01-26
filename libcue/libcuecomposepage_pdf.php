<?php
$html = "
		<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'" .
		"'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>" .
		"<html xmlns='http://www.w3.org/1999/xhtml'><head>" .
		"<meta http-equiv='content-type' content='text/html; charset=utf-8' />" .
		"<title>" . $title . "</title>" .
		"<link href='style.css' rel='stylesheet' type='text/css' media='screen' />" . 
		"</head><body>";

if ( isset($posts) )
	{
	foreach ( $posts as $post )
		{
		$html = $html . "<d>" . $post . "</div>";;
		}
	}
$html = $html . "
	</body>
	</html>";
require_once("/etc/php-dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper("A4");
$dompdf->render();
$dompdf->stream("Download.pdf");
?>

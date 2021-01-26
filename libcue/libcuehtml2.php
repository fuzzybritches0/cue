<?php
function jsorientation()
	{
		return "
  function doOnOrientationChange()
  {
    switch(window.orientation)
    {
      case -90:
      case 90:
        document.getElementById('menu-mobile').style.position='absolute';
        break;
      default:
        document.getElementById('menu-mobile').style.position='fixed';
        break;
    }
  }
	window.addEventListener('orientationchange', doOnOrientationChange);";
	}
function jsontouchcontent() {
	return "
	document.addEventListener('click', function (e) {
  var level = 0;
  for (var element = e.target; element; element = element.parentNode) {
    if (element.id === \"menu\" || element.id === \"sidebar\" || element.id === \"menu-mobile\") {
      return;
    }
    level++;
  }
	if ( document.getElementById('menu').style.visibility === \"visible\" ) document.getElementById('menu').style.visibility = 'hidden'
  if ( document.getElementById('sidebar').style.visibility === \"visible\" ) document.getElementById('sidebar').style.visibility = 'hidden'
});";
}
function jsformhandler()
	{
		return "
		function sendData(id)
			{
			// We need to access the form element
			var form = document.getElementById(id);

			var XHR = new XMLHttpRequest();
			XHR.onreadystatechange=function()
				{
				if (XHR.readyState==4 && XHR.status==200)
					{
					document.getElementById('maindiv').innerHTML = XHR.responseText;
					}
				}
			// We bind the FormData object and the form element
			var FD  = new FormData(form);

			// We setup our request
			XHR.open('POST', '" . $GLOBALS['REMOTE_HTTP_PATH'] . "', true);
			// The data sent are the one the user provide in the form
			XHR.send(FD);
			}";
	}

function libcue_html_br()
	{
	return "</br>";
	}

function libcue_html_paragraph($p)
	{
	return "<p>" . $p . "</p>";
	}

function libcue_html_link( $name, $link, $target="_parent", $id="" )
	{
	if ( strpos($link, "?command=") !== false && $target === "_parent" && isset($GLOBALS['windowid']) && isset($_SESSION['user_id']) )
		{
		$link_onclick = "onclick=\"javascript:location.href='" . $link . "&windowid=" . $GLOBALS['windowid'] . "'; return false;\"";
		}
	return "<a " . $id . $link_onclick . "href='" . $link . "' target='" . $target . "'>" . $name . "</a>";
	}

function libcue_html_menu_item_array( $name, $link, $current_page )
	{
	$item['name'] = $name; $item['link'] = $link; $item['current_page'] = $current_page;
	return $item;
	}

function libcue_html_headline( $headline, $size="1" )
	{
	return "<p><h" . $size . ">" . $headline . "</h" . $size . "></p>";
	}

function libcue_html_add_post( $headline, $content )
	{
	return "<h2>" . $headline . "</h2><p>" . $content . "</p>";
	}

function jsheartbeat()
	{
	return "
		function send_heartbeat()
			{
			windowid = new XMLHttpRequest();
			windowid.open('GET', '" . $GLOBALS['REMOTE_HTTP_PATH'] . "windowid/?command=windowid&windowid=" . $GLOBALS['windowid'] . "', true);
			windowid.send();
			}
		var hb = setInterval(function(){send_heartbeat()}, 10000);";
	}

function jsurl()
	{
	return "
		window.onload = function ()
			{
			var currentURL = document.URL;
			var position = currentURL.search('windowid=');
			var positionl = currentURL.search('=logout');
			var positionq = currentURL.search('[?]');
			if ( position == -1 && positionq == -1 )
				{
				history.replaceState( null, null, currentURL + '?windowid=" . $GLOBALS['windowid'] . "');
				}
			else if ( position == -1 && positionq != -1 )
				{
				history.replaceState( null, null, currentURL + '&windowid=" . $GLOBALS['windowid'] . "');
				}
			else if ( position != -1 && positionq != -1 )
				{
				var url_length = currentURL.length;
				var no_windowid = currentURL.substring(0, position - 1);
				var positionq = no_windowid.search('[?]');
				if ( positionq != -1 )
					{
					history.replaceState( null, null, no_windowid + '&windowid=" . $GLOBALS['windowid'] . "');
					}
				else
					{
					history.replaceState( null, null, no_windowid + '?windowid=" . $GLOBALS['windowid'] . "');
					}
				}
			if ( positionl != -1 )
				{
				history.replaceState(null, null, '?');
				}
			}";
	}

function libcue_html_head()
	{
	if ( ! isset($GLOBALS['page_title']) )
		{
		$GLOBALS['page_title'] = $GLOBALS['PROG_NAME'];
		}
	echo "<html><head>";
	if ( $GLOBALS['MOBILE'] === TRUE ) echo "<link type='text/css' rel='stylesheet' href='stylesheetm.css'/>";
	else echo "<link type='text/css' rel='stylesheet' href='stylesheet.css'/>";
		echo "<link rel='icon' type='image/png' href='" . $GLOBALS['REMOTE_HTTP_PATH']  . "image.png'/>
		<link rel='apple-touch-icon' type='image/png' href='" . $GLOBALS['REMOTE_HTTP_PATH']  . "image.png'/>
			<title>" . $GLOBALS['page_title'] . "</title><meta charset='UTF-8'></head>";
		echo "<script type='text/javascript'>";
		echo jsorientation();
		echo jsontouchcontent();
	if ( isset($GLOBALS['windowid']) && $_REQUEST['command'] !== "logout" )
		{
		echo jsheartbeat();
		}
	if ( isset($GLOBALS['windowid']) )
		{
		echo jsurl();
		echo jsformhandler();
		}
	echo	"</script>";
	echo "<body><div id='maindiv'>";
	}

function libcue_html_menu_mobile($selected_name, $selected_link, $sidebar) {
	echo "<div id='menu-mobile'>";
	echo "<a style='color:#333333;float:left;' href='#'";
	echo "onclick=\"javascript:document.getElementById('menu').style.visibility='visible';
		document.getElementById('sidebar').style.visibility='hidden';\">&#9783</a>";
	echo libcue_html_link( $selected_name, $selected_link, "_parent", "style='color:#333333;'");
	if ( $sidebar ) {
		echo "<a style='color:#333333;float:right; background-color: #DDEEFF' href='#'" .
		 "onclick=\"javascript:document.getElementById('sidebar').style.visibility='visible';
		document.getElementById('menu').style.visibility='hidden';\">&#9776</a>";
	}
	echo "</div>";
		echo "<script type='text/javascript'>";
		echo "doOnOrientationChange();";
	echo	"</script>";
}

function libcue_html_menu_begin() {
	echo "<div id='menu'>";
#	if ( $GLOBALS['MOBILE'] == TRUE ) {
#	echo "<a id='menu-item' style='color:#333333;float:left;' href='#'";
#	echo "onclick=\"javascript:document.getElementById('menu').style.visibility='hidden'\">&#10006</a>";
#	}
}

function libcue_html_menu_item($name, $link, $selected="id='menu-item'")
	{
		if ( $GLOBALS['MOBILE'] === TRUE ) {
			echo libcue_html_paragraph(libcue_html_link( $name . " ", $link, "_parent", $selected));
		}
		else {
			echo libcue_html_link( $name . " ", $link, "_parent", $selected);
		}
	}

function libcue_html_menu_end()
	{
	echo "</div>";
	}

function libcue_html_begin_content()
	{
	echo "<div id='content'>";
	}

function libcue_html_end_content()
	{
	echo "</div>";
	}
function libcue_html_footer()
	{
	echo "<div id='footer'><p><strong>" . $GLOBALS['PROG_NAME'] . "</strong> - " . $GLOBALS['PROG_DESC'] .
		" - Copyleft blueblended.com - some rights reserved - " . libcue_html_link("Admin kontaktieren", $GLOBALS['REMOTE_HTTP_PATH'] . "?command=contact_admin",
		"_parent") ."</p></div>";
	}

function libcue_html_begin_sidebar()
	{
		echo "<div id='sidebar'>";
#		if ( $GLOBALS['MOBILE'] == TRUE ) {
#			echo "<a style='color:#333333;float:right;font-size:2em;' href='#'";
#			echo "onclick=\"javascript:document.getElementById('sidebar').style.visibility='hidden'\">&#10006</a>";
#		}
	}

function libcue_html_end_sidebar()
	{
	echo "</div>";
	}

function libcue_html_end_page()
	{
	echo "</div></body></html>";
	}

#function libcue_html_add_sidebar_section( $headline, $content )
#	{
#	return "<h3>" . $headline . "</h3><p>" . $content . "</p>";
#	}
?>

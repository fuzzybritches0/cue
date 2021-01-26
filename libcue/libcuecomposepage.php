<?php
function draw_content($posts)
	{
	if ( isset($posts) )
		{
		foreach ( $posts as $post )
			{
			echo $post;
			}
		}
	}

if ( $_REQUEST['content_only'] === "true" )
	{
	libcue_html_menu_begin();
	if ( isset($menuitems) )
		{
		foreach ( $menuitems as $menuitem )
			{
			$selected = "id='menu-item'";
			if ( $menuitem['current_page'] === $current_page )
				{
				$selected = "id='menu-item-selected'";
				$selected_name = $menuitem['name'];
				$selected_link = $menuitem['link'];
				}
			libcue_html_menu_item($menuitem['name'], $menuitem['link'], $selected);
			}
		}
	libcue_html_menu_end();
	if ( $GLOBALS['MOBILE'] === TRUE ) {
		libcue_html_menu_mobile($selected_name, $selected_link, isset($sidebar_sections));
	}
	libcue_html_begin_content();
	draw_content($posts);
	libcue_html_end_content();
	if ( isset($sidebar_sections) )
		{
		libcue_html_begin_sidebar();
		if ( $GLOBALS['MOBILE'] === TRUE ) {
			$sidebar_sections[] = "<a style='color:#333333;float:right;padding-right:1em;' href='#'" .
				"onclick=\"javascript:document.getElementById('sidebar').style.visibility='hidden'\">&#10006</a>";
		}
		foreach ( $sidebar_sections as $sidebar_section )
			{
			echo $sidebar_section;
			}
		libcue_html_end_sidebar();
		}
	libcue_html_footer();
	}
else
	{
	libcue_html_head();
	libcue_html_menu_begin();
	if ( isset($menuitems) )
		{
		foreach ( $menuitems as $menuitem )
			{
			$selected = "id='menu-item'";
			if ( $menuitem['current_page'] === $current_page )
				{
				$selected = "id='menu-item-selected'";
				$selected_name = $menuitem['name'];
				$selected_link = $menuitem['link'];
				}
			libcue_html_menu_item($menuitem['name'], $menuitem['link'], $selected);
			}
		}
	libcue_html_menu_end();

	if ( $GLOBALS['MOBILE'] === TRUE ) {
		libcue_html_menu_mobile($selected_name, $selected_link, isset($sidebar_sections) );
	}
	libcue_html_begin_content();
	draw_content($posts);
	libcue_html_end_content();
	if ( isset($sidebar_sections) )
		{
		libcue_html_begin_sidebar();
		foreach ( $sidebar_sections as $sidebar_section )
			{
			echo $sidebar_section;
			}
		libcue_html_end_sidebar();
		}
	libcue_html_footer();
	libcue_html_end_page();
	}
?>

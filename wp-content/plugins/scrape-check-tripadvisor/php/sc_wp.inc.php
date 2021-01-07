<?php

// Group Menu Items
function sc_ta_plugin_menus() {
	$iconPath = plugin_dir_url( __FILE__ ) . '../images/sc-ta-icon.png';

    add_menu_page('TripAdvisor', 'TripAdvisor', 'manage_options', 'scrape-check-tripadvisor', 'sc_ta_plugin_menus_main', $iconPath);
    remove_menu_page('edit.php?post_type=sc_url');
    remove_menu_page('edit.php?post_type=sc_queue');
    remove_menu_page('edit.php?post_type=sc_stack');
    remove_menu_page('edit.php?post_type=sc_result');
}


// Default Menu Page

function sc_ta_plugin_menus_main() {
	?>
<div class="wrap">
	<h1>ScrapeCheck Settings</h1>
</div>
	<?php
}


// Menu Item Reordering
function sc_ta_plugin_admin_menu() {
	global $menu;

	foreach($menu as $menuKey=>$menuValue) {

		if ($menuValue[2]=='scrape-check')  {

			$scMenu = $menu[$menuKey];
			
			$menu[$menuKey] = $separator = [
			    0 => '',
			    1 => 'read',
			    2 => 'separator' . $menuKey,
			    3 => '',
			    4 => 'wp-menu-separator'
			];
			
			$menu[$menuKey+1] = $scMenu;
	    	
	    	break;
		}
	}
}

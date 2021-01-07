<?php
/*
Plugin Name: ScrapeCheck
Description: Page scraping and checking functionality.
Author: Tom Storms
Author URI: Island Meets City (https://www.islandmeetscity.com)
Version: 1.0
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;


function sc_plugin_loaded() {
	global $menu;

    // Hook SC menus
	add_action('admin_menu', 'sc_plugin_menus');

    // Include Lib
    include(plugin_dir_path(__FILE__) . 'lib/simplehtmldom/simple_html_dom.php');

    // Include PHP
    include(plugin_dir_path(__FILE__) . 'php/sc_functions.inc.php');
    include(plugin_dir_path(__FILE__) . 'php/sc_wp.inc.php');
    include(plugin_dir_path(__FILE__) . 'php/sc_data-extraction.inc.php');

}
add_action( 'plugins_loaded', 'sc_plugin_loaded' );


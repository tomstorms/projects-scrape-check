<?php
/*
Plugin Name: ScrapeCheck for TripAdvisor
Description: Scraping and checking for TripAdvisor.
Author: Tom Storms
Author URI: Island Meets City (https://www.islandmeetscity.com)
Version: 1.0
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

function sc_ta_plugin_loaded() {
	global $menu;

    // Hook SC menus
	add_action('admin_menu', 'sc_ta_plugin_menus');
    
    // Include Data Processing PHP
    include(plugin_dir_path(__FILE__) . 'php/sc_ta_establishment.inc.php');
    include(plugin_dir_path(__FILE__) . 'php/sc_ta_reviewer.inc.php');
    include(plugin_dir_path(__FILE__) . 'php/sc_ta_review.inc.php');

    // Include PHP
    include(plugin_dir_path(__FILE__) . 'php/sc_wp.inc.php');
    include(plugin_dir_path(__FILE__) . 'php/sc_data-process.inc.php');

}
add_action( 'plugins_loaded', 'sc_ta_plugin_loaded' );

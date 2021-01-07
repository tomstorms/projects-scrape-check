<?php

// Group Menu Items
function sc_plugin_menus() {
    add_menu_page('ScrapeCheck', 'ScrapeCheck', 'manage_options', 'scrape-check', 'sc_plugin_menus_main', 'dashicons-filter');
    remove_menu_page('edit.php?post_type=sc_url');
    remove_menu_page('edit.php?post_type=sc_queue');
    remove_menu_page('edit.php?post_type=sc_stack');
    remove_menu_page('edit.php?post_type=sc_result');
}


// Default Menu Page
function sc_plugin_menus_main() {
	?>
<div class="wrap">
	<h1>Welcome to ScrapeCheck</h1>
</div>
	<?php
}


// Menu Item Reordering
function sc_plugin_menu_order( $menu_ord ) {
    if ( !$menu_ord ) return true;

    return array(
        'index.php', // Dashboard
        'separator1', // First separator
        'edit.php', // Posts
        'upload.php', // Media
        'link-manager.php', // Links
        'edit.php?post_type=page', // Pages
        'edit-comments.php', // Comments
        'separator2', // Second separator
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
        'edit.php?post_type=acf-field-group', // ACF
        'admin.php?page=cptui_manage_post_types', // CPT UI
        'edit.php?post_type=sc_url',
        'admin.php?page=scrape-check-tripadvisor',
        'separator-last', // Last separator
    );
}
add_filter( 'custom_menu_order', 'sc_plugin_menu_order', 10, 1 );
add_filter( 'menu_order', 'sc_plugin_menu_order', 10, 1 );


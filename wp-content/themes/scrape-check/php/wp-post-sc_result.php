<?php

/**
 * REMOVE NEW BUTTON ON POST TYPES
 */
function sc_wp_disable_new_posts() {
    // Post Type
    $postType = 'sc_result';

    // Hide sidebar link
    global $submenu;
    unset($submenu['edit.php?post_type='.$postType][10]);

    // Hide link on listing page
    global $post;
    if (isset($post->post_type) && $post->post_type == $postType) {
        echo '<style type="text/css">';
        // echo '#favorite-actions, .add-new-h2, .tablenav { display:none !important; }'; // Show Delete button
        echo '.page-title-action { display:none !important; }';
        echo '</style>';
    }
}
add_action('admin_head', 'sc_wp_disable_new_posts');


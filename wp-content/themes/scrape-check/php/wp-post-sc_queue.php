<?php


/**
 * UPDATE POST ON SAVE
 */
function sc_wp_save_sc_queue_post($post_id) {

	// Don't process empty POST data
    if ($post_id == null || empty($_POST)) return;

    // Don't process other POST types except sc_queue
    if (!isset( $_POST['post_type'] ) || $_POST['post_type']!='sc_queue')  return; 

    // Set Post ID if not set
    if (wp_is_post_revision($post_id)) $post_id = wp_is_post_revision($post_id);

    global $post;
    if (empty($post)) $post = get_post($post_id);

    // Check if URL is set to update title

    $field = get_field_object('scrape_queue_url_post');
    $fieldKey = (isset($field['key']) ? $field['key'] : '');

    if (isset($_POST['acf'][$fieldKey])) {

        global $wpdb;

        $scURLPost = get_post($_POST['acf'][$fieldKey]);
        $title = $scURLPost->post_title;


        $scheduleType = strtolower(get_field('schedule_type', $post_id));
        if ($scheduleType == 'interval') {
            $title .= ' - Interval Check';
        }
        else if ($scheduleType == 'settime') {
            $title .= ' - Time per Day Check';
        }
        else {
            $title .= ' - Unknown Schedule Check';
        }

        $wpdb->update($wpdb->posts, array('post_title' => $title), array('ID' => $post_id));

    }

}
add_action('save_post', 'sc_wp_save_sc_queue_post', 12 );


/**
 * ADD CUSTOM COLUMNS TO SC_QUEUE
 */
function sc_wp_set_sc_queue_posts_columns($columns) {
	// Rearrange columns
	$newColumns = array();
	foreach($columns as $colKey=>$colVal) {
		if ($colKey == 'date') {
			// Insert new columns after date
            $newColumns['schedule'] = 'Schedule';
            $newColumns['last_run'] = 'Last Run';
		}

		$newColumns[$colKey] = $colVal;

	}
    return $newColumns;
}
add_filter( 'manage_sc_queue_posts_columns', 'sc_wp_set_sc_queue_posts_columns' );


/**
 * ADD CUSTOM COLUMN TO SC_QUEUE
 */
function sc_wp_set_sc_queue_custom_column( $column, $post_id ) {
    switch($column) {
        case 'schedule':

            $isEnabled = (get_post_status($post_id) == 'publish');

            $scheduleType = strtolower(get_field('schedule_type', $post_id));

            if ($scheduleType == 'interval') {
                $scheduleInterval = get_field('schedule_interval', $post_id);
                echo 'Every <b>'.$scheduleInterval.' minutes</b>';
            }
            else if ($scheduleType == 'settime') {
                $scheduleDailyTime = get_field('schedule_daily_time', $post_id);
                echo 'Daily at <b>'.$scheduleDailyTime.'</b>';

            }
            else {
                echo 'Unknown';
            }

            if (!$isEnabled) echo '<br/><span style="color:red">(Disabled)</span>';

            break;

        case 'last_run':

            $lastRun = get_field('schedule_last_run', $post_id);
            if ($lastRun == '') echo 'Not run';
            else echo $lastRun;

            break;
    }
}
add_action( 'manage_sc_queue_posts_custom_column' , 'sc_wp_set_sc_queue_custom_column', 10, 2 );


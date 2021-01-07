<?php


function sc_acf_field_publishedonly( $args, $field, $post_id ) {
	
    // Only show Published Posts on scrape_queue_url_post fields
    $args['post_status'] = 'publish';
    return $args;
    
}
add_filter('acf/fields/post_object/query/name=scrape_queue_url_post', 'sc_acf_field_publishedonly', 10, 3);
add_filter('acf/fields/post_object/query/name=scrape_queue_result_url_post', 'sc_acf_field_publishedonly', 10, 3);


function sc_acf_field_readonly($field) {
	
	// Create ReadOnly Field
	$field['disabled']='1'; 
	return $field;

}
add_filter('acf/load_field/name=schedule_last_run', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_url_post', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_queue_post', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_path_screenshot', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_path_html', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_page_url', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_time_started', 'sc_acf_field_readonly');
add_filter('acf/load_field/name=scrape_queue_result_time_completed', 'sc_acf_field_readonly');


<?php


function sc_ta_run_check($data) {

	$extractedData = $data['data'];
	$scQueueResultPostID = $data['queue_post_id'];

	echo 'scQueueResultPostID: '.$scQueueResultPostID;

	$response = sc_ta_process_data($extractedData, $scQueueResultPostID);

	return $response;

}
add_filter('scrape_check_run_tripadvisor', 'sc_ta_run_check');


function sc_ta_process_data($data, $queueResultPostID) {

	$scQueueResultPost = get_post($queueResultPostID);

	$estPostID = sc_ta_process_data_establishment($data['data_extraction_firstpage'], $scQueueResultPost);
	$estPost = get_post($estPostID);

	if (isset($estPost) && !empty($estPost)) {

		echo 'pass1';

		if (isset($data['pages']) && !empty($data['pages'])) {

		echo 'pass2';

			foreach($data['pages'] as $taPage) {

		echo 'pageloop';


				if (isset($taPage['data_extraction']) && isset($taPage['data_extraction']['reviews_container']) && !empty($taPage['data_extraction']['reviews_container'])) {

		echo 'pass3';

					foreach($taPage['data_extraction']['reviews_container'] as $taPageReviews) {
				
		echo 'pass4';

						sc_ta_process_data_review($taPageReviews, $estPost);


						die('stopepd');
						
					}

				}
			}

		}
	}
	else {
		die('failed to create establishment post');
	}



	die('end');

	return $data;
}

function sc_ta_tripadvisor_url() {
	return 'https://www.tripadvisor.com';
}
<?php 

// ------------------------------------------------------------------
// INIT

// DEBUGGING ONLY
// $_SERVER['HTTP_USER_AGENT'] = 'ScrapeEngine/1.0';

/**
 * Log to File function
 */
function logToFile($data) {
	$logLine  = date('Y-m-d H:i:s').': ';
	$logLine .= $data."\r\n";
	file_put_contents(__DIR__.'/post-log.txt', $logLine, FILE_APPEND);
}

/**
 * Include WordPress functions
 */
include_once('../../wp-load.php');

/**
 * Prepare Response Array
 */
$response = array();


logToFile('Page requested: '.print_r($_SERVER, true));

// ------------------------------------------------------------------
// START

if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']) || 
		 substr($_SERVER['HTTP_USER_AGENT'], 0, strlen('ScrapeEngine/'))!='ScrapeEngine/') {

	// ------------------------------------------------------------------
	// BLOCK ACCESS TO REQUESTS WITHOUT USER AGENT

	logToFile('Block Request. No User Agent.');

	$response['status'] = 'invalid';
	
}
else {

	// ------------------------------------------------------------------
	// USER AGENT OK, CONTINUE REQUEST

	if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST) && !empty($_POST)) {

		// ------------------------------------------------------------------
		// POST REQUEST

		logToFile('Received POST request: '.print_r($_POST, true));

		if (isset($_POST) && !empty($_POST)) {

			logToFile('Valid POST request');

			$response['status'] = 'failed';


			// DEBUGGING ONLY ------------------------
			// $jsonData = '%7B%22timeStarted%22%3A%2220190108-065907%22%2C%22scrape_results%22%3A%5B%7B%22file_screenshot%22%3A%2215_p1.png%22%2C%22file_html%22%3A%2215_p1.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g2162088-d11912954-Reviews-Hollywood_Suites_and_Resort_Marilao-Marilao_Bulacan_Province_Central_Luzon_Region_Lu.html%22%7D%5D%2C%22queue_id%22%3A24%2C%22url_id%22%3A15%2C%22data_path%22%3A%22data%2F15%2F20190108-065907%22%2C%22data_path_full%22%3A%22%2FUsers%2Ftom.s%2FDesktop%2Fscrape-check%2Fscrape-check%2Fdata%2F15%2F20190108-065907%22%2C%22timeCompleted%22%3A%2220190108-065924%22%2C%22status%22%3A%22ok%22%7D';

			// $jsonData = '%7B%22timeStarted%22%3A%2220190108-140754%22%2C%22scrape_results%22%3A%5B%7B%22file_screenshot%22%3A%2216_p1.png%22%2C%22file_html%22%3A%2216_p1.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g11875857-d11871206-Reviews-Cool_Waves_Ranch_and_Water_Park_Resort-Bulacan_Bulacan_Province_Central_Luzon_Regio.html%22%7D%5D%2C%22queue_id%22%3A334%2C%22url_id%22%3A16%2C%22data_path%22%3A%22data%2F16%2F20190108-140754%22%2C%22data_path_full%22%3A%22%2FUsers%2Ftom.s%2FDesktop%2Fscrape-check%2Fscrape-check%2Fdata%2F16%2F20190108-140754%22%2C%22timeCompleted%22%3A%2220190108-140809%22%2C%22status%22%3A%22ok%22%7D';

			// $jsonData = '%7B%22timeStarted%22%3A%2220190108-165334%22%2C%22scrape_results%22%3A%5B%7B%22file_screenshot%22%3A%22403_p1.png%22%2C%22file_html%22%3A%22403_p1.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8618407-Reviews-or15-Belmont_Hotel_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22403_p2.png%22%2C%22file_html%22%3A%22403_p2.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8618407-Reviews-or5-Belmont_Hotel_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22403_p3.png%22%2C%22file_html%22%3A%22403_p3.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8618407-Reviews-or10-Belmont_Hotel_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22403_p4.png%22%2C%22file_html%22%3A%22403_p4.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8618407-Reviews-or15-Belmont_Hotel_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22403_p5.png%22%2C%22file_html%22%3A%22403_p5.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8618407-Reviews-or20-Belmont_Hotel_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%5D%2C%22queue_id%22%3A404%2C%22url_id%22%3A403%2C%22data_path%22%3A%22data%2F403%2F20190108-165335%22%2C%22data_path_full%22%3A%22%2FUsers%2Ftom.s%2FDesktop%2Fscrape-check%2Fscrape-check%2Fdata%2F403%2F20190108-165335%22%2C%22timeCompleted%22%3A%2220190108-165433%22%2C%22status%22%3A%22ok%22%7D';

			$jsonData = '%7B%22timeStarted%22%3A%2220190108-201419%22%2C%22scrape_results%22%3A%5B%7B%22file_screenshot%22%3A%22437_p1.png%22%2C%22file_html%22%3A%22437_p1.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8431164-Reviews-Conrad_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22437_p2.png%22%2C%22file_html%22%3A%22437_p2.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8431164-Reviews-or5-Conrad_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22437_p3.png%22%2C%22file_html%22%3A%22437_p3.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8431164-Reviews-or10-Conrad_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22437_p4.png%22%2C%22file_html%22%3A%22437_p4.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8431164-Reviews-or15-Conrad_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%2C%7B%22file_screenshot%22%3A%22437_p5.png%22%2C%22file_html%22%3A%22437_p5.html%22%2C%22page_url%22%3A%22https%3A%2F%2Fwww.tripadvisor.com.ph%2FHotel_Review-g298452-d8431164-Reviews-or20-Conrad_Manila-Pasay_Metro_Manila_Luzon.html%22%7D%5D%2C%22queue_id%22%3A438%2C%22url_id%22%3A437%2C%22data_path%22%3A%22data%2F437%2F20190108-201419%22%2C%22data_path_full%22%3A%22%2FUsers%2Ftom.s%2FDesktop%2Fscrape-check%2Fscrape-check%2Fdata%2F437%2F20190108-201419%22%2C%22timeCompleted%22%3A%2220190108-201513%22%2C%22status%22%3A%22ok%22%7D';

			$postData = json_decode(urldecode($jsonData), true);
			// END DEBUGGING -------------------------

			// LIVE DATA
			// $postData = json_decode(urldecode($_POST['data']), true);

			logToFile(print_r($postData, true));


			// Update Queue Last Run Timestamp
			$timeCompleted = $postData['timeCompleted'];
			$queueID = $postData['queue_id'];
			update_field('schedule_last_run', $timeCompleted, $queueID);


			// Create Queue Result Posts
			$urlID = $postData['url_id'];
			$scURLPost = get_post($urlID);
			$scQueueResultPostID = wp_insert_post(array(
				'post_title'    => $scURLPost->post_title,
				'post_status'   => 'publish',
				'post_type'     => 'sc_result',
			));


			if (!is_wp_error($scQueueResultPostID)) {

				// Update Post Data

				$timeStart = $postData['timeStarted'];
				$responsePath = realpath($postData['data_path']);

				update_field('scrape_result_url_post', get_post($urlID), $scQueueResultPostID);
				update_field('scrape_result_queue_post', get_post($queueID), $scQueueResultPostID);
				update_field('scrape_result_raw_engine', json_encode($postData), $scQueueResultPostID);
				update_field('scrape_result_time_started', $timeStart, $scQueueResultPostID);
				update_field('scrape_result_time_completed', $timeCompleted, $scQueueResultPostID);
				update_field('scrape_result_data_path', $responsePath, $scQueueResultPostID);


				// Prepare Check Engine
				$scStackPost = get_field('scrape_stack_post', $urlID);
				$scCheckPlatform = strtolower(get_field('check_platform', $scStackPost->ID));

				$scRunFilterName = 'scrape_check_run_'.$scCheckPlatform;

				if (array_key_exists($scRunFilterName, $GLOBALS['wp_filter'])) {

					$extractedData = sc_plugin_run_data_extraction($postData, $scQueueResultPostID);

					update_field('scrape_result_raw_check', json_encode($extractedData), $scQueueResultPostID);

					apply_filters($scRunFilterName, array('data'=>$extractedData, 'queue_post_id'=>$scQueueResultPostID));

					$response['status'] = 'ok';

				}
				else {

					$response['msg'] = 'Unknown Check Platform: '.$scCheckPlatform;

				}
			}
			else {

				$response['msg'] = 'Failed to create results post';

			}
		}
		else {

			logToFile('Fail. Empty POST request');

			$response['status'] = 'invalid_post';

		}
	}
	else if ($_SERVER['REQUEST_METHOD']==='GET') {

		// ------------------------------------------------------------------
		// GET REQUEST

		logToFile('Received GET request');

		// GET SCRAPE URLs

		$scQueueData = get_posts(array(
			'posts_per_page'   => -1,
			'post_type'        => 'sc_queue',
			'post_status'      => 'publish',
		));

		$response = array();
		foreach($scQueueData as $scQueuePost) {

			$addToQueue = false;

			$scQueueLastRun = get_field('schedule_last_run', $scQueuePost->ID);
			if ($scQueueLastRun == '-') {
				// Hasn't run yet
				$addToQueue = true;
			}
			else {

				// Get Schedule Type
				$scQueueScheduleType = get_field('schedule_type', $scQueuePost->ID);

				if ($scQueueScheduleType=='interval') {

					// Run on Interval of minutes

					$scQueueInterval = get_field('schedule_interval', $scQueuePost->ID);
					$addToQueue = true;

				}
				else if ($scQueueScheduleType=='settime') {

					// Run at a set time per day

					$scQueueTimePerDay = get_field('schedule_daily_time', $scQueuePost->ID);

					$addToQueue = true;
				}
				else {
					// Some other method
					// skip
				}
			}

		
			if ($addToQueue) {

				// Process if valid

				$scURLPost = get_field('scrape_queue_url_post', $scQueuePost->ID);
				$scPuppeteerHeadless = get_field('puppeteer_headless', $scQueuePost->ID);
				$scPuppeteerLang = get_field('puppeteer_lang', $scQueuePost->ID);

				$scStackPost = get_field('scrape_stack_post', $scURLPost->ID);
				$scCheckPlatform = get_field('check_platform', $scStackPost->ID);

				$stackEvents = get_field('events', $scStackPost->ID);

				$platformEvents = array();
				foreach($stackEvents as $stackEvent) {

					$platformEventData = array();
					$platformEventLogDescription = '';

					$stackEventType = $stackEvent['event_type'];
					if ($stackEventType == 'click') {

						// Click Event

						foreach($stackEvent['event_elements'] as $stackEventElement) {
							$targetName = trim($stackEventElement['target_name']);
							if ($targetName == '') continue; // skip empty targets
							$platformEventData[] = $targetName;
						}

						$platformEventLogDescription = $stackEvent['event_log_description'];

					}
					else if ($stackEventType == 'wait') {

						// Wait Event

						$waitTime = trim($stackEvent['event_wait_time']);
						if ($waitTime == '') $waitTime = '3000'; // default to 3 secs

						$platformEventData = $waitTime;


					}
					else if ($stackEventType == 'screenshot') {

						// Screenshot Event

						$platformEventLogDescription = 'Taking Screenshot...';

					}
					else if ($stackEventType == 'save_html') {

						// Save HTML Event

						$platformEventLogDescription = 'Saving HTML...';

					}

					$platformData = array();
					$platformData['type'] = $stackEventType;

					if ($platformEventLogDescription!='') {
						$platformData['log_description'] = $platformEventLogDescription;	
					}

					if ($platformEventData!='' && !empty($platformEventData)) {
						$platformData['data'] = $platformEventData;	
					}

					array_push($platformEvents, $platformData);

				}

				$platformPagination = array();

				$stackPaginationEnabled = get_field('pagination_enabled', $scStackPost->ID);
				if ($stackPaginationEnabled) {
					$stackPaginationStart = get_field('pagination_target_primary', $scStackPost->ID);
					$stackPaginationEnd = get_field('pagination_target_end', $scStackPost->ID);
					$platformPagination['start_element'] = $stackPaginationStart;
					$platformPagination['end_element'] = $stackPaginationEnd;

					// Limit Pagination if set under Queue
					$scQueuePaginationLimitOn = get_field('schedule_pagination_limit', $scQueuePost->ID);
					if ($scQueuePaginationLimitOn) {
						$paginateLimit = get_field('schedule_pagination_length', $scQueuePost->ID);
						$platformPagination['paginate_limit'] = $paginateLimit;
					}
				}

				$response[] = array(
					'queue_id'=>$scQueuePost->ID,
					'url_id'=>$scURLPost->ID,
					'title'=>$scURLPost->post_title,
					// 'establishment_type'=>$scStackEstablishmentType,
					'url'=>get_field('scrape_url', $scURLPost->ID),

					'platform_data'=>array(
						'platform'=>$scCheckPlatform,
						'events'=>$platformEvents,
						'pagination'=>$platformPagination,
					),
					'puppeteer_data'=>array(
						'headless'=>$scPuppeteerHeadless,
						'lang'=>$scPuppeteerLang,
					)
				);
			}
		}

	}
	else {
		
		logToFile('Received Invalid Request Type');

		$response['status'] = 'invalid_request';

	}
}


header('Content-Type: application/json');
echo json_encode($response);
die();

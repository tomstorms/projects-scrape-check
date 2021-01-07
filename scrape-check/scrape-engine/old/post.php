<?php 

include('init.inc.php');

if (isset($_POST['data']) && !empty($_POST['data'])) {

	$data = json_decode($_POST['data'], true);

    $queueID = $data['qid'];
    $requestID = $data['rid'];
    $status = $data['status'];
    $timeStarted = $data['time_started'];
    $timeCompleted = $data['time_completed'];
    $fileHTML = $data['file_html'];
    $fileScreenshot = $data['file_screenshot'];

	file_put_contents(__DIR__.'/dump/logs/php_'.time().'.txt', 'DATA: '.print_r($data, true).' POST: '.print_r($_POST, true).' SERVER: '.print_r($_SERVER, true).' query: '."INSERT INTO scrape_request(requestid, filename_html, filename_screenshot) VALUES(:requestID, :fileHTML, :fileScreenshot)");

    // Update Queue
    updateQueue($queueID, $timeStarted, $timeCompleted, $status);

	// Insert Results
	createRequestResult($queueID, $requestID, $fileHTML, $fileScreenshot);

	echo 'ok';

}
else {
	echo 'failed';
}

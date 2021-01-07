<?php

function getRequests($status='queued') {
	global $clsMySQL;

	$requestData = $clsMySQL->query("SELECT q.queueid, q.requestid, r.url, r.name FROM scrape_queue as q, scrape_request as r WHERE q.status = :status AND r.requestid = q.requestid", array('status'=>$status));

	$response = array();

	if (!empty($requestData)) {
		foreach($requestData as $requestItem) {
			$requestItem['metadata'] = getRequestMeta($requestItem['requestid']);
			$requestItem['eventdata'] = getRequestEvent($requestItem['requestid']);
			$response[] = $requestItem;
		}
	}

	return $response;
}

function getRequestMeta($requestID) {
	global $clsMySQL;

	$metaData = $clsMySQL->query("SELECT name, value FROM scrape_request_meta WHERE requestid = :requestid ORDER BY ordering;", array('requestid'=>$requestID));

	$response = array();

	if (!empty($metaData)) {
		foreach($metaData as $metaItem) {
			$response[$metaItem['name']] = $metaItem['value'];
		}
	}

	return $response;

}

function getRequestEvent($requestID) {
	global $clsMySQL;

	$metaData = $clsMySQL->query("SELECT name, value FROM scrape_request_event WHERE requestid = :requestid ORDER BY ordering;", array('requestid'=>$requestID));

	$response = array();

	if (!empty($metaData)) {
		foreach($metaData as $metaItem) {
			$response[$metaItem['name']] = $metaItem['value'];
		}
	}

	return $response;

}

function createQueue($requestID) {
	global $clsMySQL;

	$clsMySQL->query("INSERT INTO scrape_queue (requestid) VALUES(:requestID)", array('requestID'=>$requestID));

}

function updateQueue($queueID, $timeStarted, $timeCompleted, $status) {
	global $clsMySQL;

	$clsMySQL->query("UPDATE scrape_queue SET time_started = :timeStarted, time_completed = :timeCompleted, status = :status WHERE queueID = :queueID", array('queueID'=>$queueID, 'timeStarted'=>$timeStarted, 'timeCompleted'=>$timeCompleted, 'status'=>$status));

}

function createRequestResult($queueID, $requestID, $fileHTML, $fileScreenshot) {
	global $clsMySQL;

	$clsMySQL->query("INSERT INTO scrape_result (queueid, requestid, filename_html, filename_screenshot) VALUES(:queueID, :requestID, :fileHTML, :fileScreenshot)", array('queueID'=>$queueID, 'requestID'=>$requestID, 'fileHTML'=>$fileHTML, 'fileScreenshot'=>$fileScreenshot));

}
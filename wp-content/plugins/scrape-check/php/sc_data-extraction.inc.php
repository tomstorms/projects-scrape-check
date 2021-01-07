<?php

// Force access to WP File
require_once(ABSPATH . 'wp-admin/includes/file.php');

function sc_plugin_run_data_extraction($postData, $resultsPostID) {

	$urlID = $postData['url_id'];
	$scStackPost = get_field('scrape_stack_post', $urlID);
	$scCheckPlatform = get_field('check_platform', $scStackPost->ID);

	$dataPath = $postData['data_path'];
	$dataPathLocal = get_home_path().'scrape-check/'.$postData['data_path'];

	$response = array();

	// -------------------------------------------------------
	// First Page Checks

	$scDataExtractionFirstPage = get_field('check_data_extraction_firstpage', $scStackPost->ID);

	$scrapeResult = $postData['scrape_results'][0];
	$scrapeResultHTMLFile = $scrapeResult['file_html'];

	// Prepare for Regex
	$scrapeResultHTMLFilePath = $dataPathLocal.'/'.$scrapeResultHTMLFile;
	$scrapeResultHTML = file_get_contents($scrapeResultHTMLFilePath, true);

	// Prepare for DOM
	libxml_use_internal_errors(true);
	$scrapeResultDOM = new DOMDocument();
	$scrapeResultDOM->loadHTMLFile($scrapeResultHTMLFilePath);

	if ($scrapeResultHTML!='') {
		$scDataExtracted = sc_plugin_data_extraction($scrapeResultHTML, $scrapeResultDOM, $scDataExtractionFirstPage);

		// $scMetaCheck = processCheck($scrapeResultHTML, $scDataExtractionFirstPage, $scMetaCheck);

		$response['data_extraction_firstpage'] = $scDataExtracted;
	}


	// -------------------------------------------------------
	// Per Page Checks

	$scDataExtraction = get_field('check_data_extraction', $scStackPost->ID);

	for($i=0; $i<count($postData['scrape_results']); $i++) {

		$scrapeResult = $postData['scrape_results'][$i];
		$scrapeResultHTMLFile = $scrapeResult['file_html'];

		// Prepare for Regex
		$scrapeResultHTMLFilePath = $dataPathLocal.'/'.$scrapeResultHTMLFile;
		$scrapeResultHTML = file_get_contents($scrapeResultHTMLFilePath, true);

		// Prepare for DOM
		libxml_use_internal_errors(true);
		$scrapeResultDOM = new DOMDocument();
		$scrapeResultDOM->loadHTMLFile($scrapeResultHTMLFilePath);

		if ($scrapeResultHTML!='') {
			$scDataExtracted = sc_plugin_data_extraction($scrapeResultHTML, $scrapeResultDOM, $scDataExtraction);

			// $scMetaCheck = processCheck($scrapeResultHTML, $scDataExtraction, $scMetaCheck);

			$response['pages'][$i]['data_extraction'] = $scDataExtracted;
			// $response['check_results'][]['meta_check'] = $scDataExtraction;
		}
	}

	return $response; 
}


function sc_plugin_data_extraction($html, $dom, $checkData, $subItemIndex=0) {

	$returnData = array();

	foreach($checkData as $checkDataItem) {

		$metaName = trim($checkDataItem['check_meta_name']);
		if ($metaName == '') continue; // skip empty checks

		if ($checkDataItem['check_extraction_type']=='single_match_regex') {

			// Run Regex
			$regex = $checkDataItem['check_regex'];
			$regexResult = sc_plugin_data_extraction_regex($html, $regex);
			$returnData[$metaName] = $regexResult;

			// if ($metaName=='review_rating_value') {
			// 	echo 'metaName: '.$metaName."\r\n";

			// 	print_r($regex);
			// 	print_r($regexResult);
			// 	echo $html;

			// 	echo  '-----------'."\r\n";
			// }

		}
		else if ($checkDataItem['check_extraction_type']=='single_match_dom_id' ||
				 $checkDataItem['check_extraction_type']=='single_match_dom_class') {

			$subData = array();
			if (isset($checkDataItem['check_sub_extraction']) && !empty($checkDataItem['check_sub_extraction'])) {
				$subData = $checkDataItem['check_sub_extraction'];
			}

			// Run DOM
			$domMatch = $checkDataItem['check_dom_match'];
			$domResult = sc_plugin_data_extraction_dom($dom, $domMatch, $subData, $subItemIndex);
			$returnData[$metaName] = $domResult;

		}
		else if ($checkDataItem['check_extraction_type']=='element_exists_regex') {

			// Run Regex and report if it exists
			$regex = $checkDataItem['check_regex'];
			$regexResult = sc_plugin_extraction_regex_exists($html, $regex);
			$returnData[$metaName] = $regexResult;

		}
		else {
			echo 'Unsupported extraction type: '.$checkDataItem['check_extraction_type'];
			die();
		}
	}

	return $returnData;

}


function sc_plugin_data_extraction_regex($html, $regex) {

	$regexRule = '/'.$regex.'/';

	try {
		preg_match_all($regexRule, $html, $regexMatches);
	}
	catch (Exception $e) {
		echo 'Error with regex rule: '.$regexRule."\r\n";
	    // echo 'Caught exception: ',  $e->getMessage(), "\n";
	    return;
	}

	if (isset($regexMatches[1][0]) && $regexMatches[1][0]!='') {

		$regexValue = $regexMatches[1][0];
		// $regexValue = trim($regexValue);
		// $regexValue = str_replace("&nbsp;", '', $regexValue);
		return $regexValue;
	}

}


function sc_plugin_extraction_regex_exists($html, $regex) {

	$regexRule = '/'.$regex.'/';
	preg_match_all($regexRule, $html, $regexMatches);
	return (isset($regexMatches[1][0]) && $regexMatches[1][0]!='') ? 1 : 0;

}


function sc_plugin_data_extraction_dom($dom, $match, $subExtractionData, $subItemIndex) {

	$xpath = new DOMXPath($dom);
	$domQuery = $xpath->query("//*[@class='" . $match . "']");

	if ($domQuery->length > 0) {

		if (isset($subExtractionData) && !empty($subExtractionData)) {

			$result = array();

			for($i=0; $i<$domQuery->length; $i++) {

				$xpathSub = $domQuery->item($i);
				$subHTML = $dom->saveHTML($xpathSub);

				$subHTML = str_replace(array("\n", "\r"), '', $subHTML);

				$result[] = sc_plugin_data_extraction($subHTML, $dom, $subExtractionData, $i);

			}

			return $result;

		}
		else {
			// Is a single value match
	    	return $domQuery->item($subItemIndex)->nodeValue;
		}

	}

}

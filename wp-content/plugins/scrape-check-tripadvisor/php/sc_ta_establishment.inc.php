<?php


function sc_ta_process_data_establishment($dataEst, $scQueueResultPost) {

	// =================================================
	// PREPARE DATA 

	$jsonPageMetaData = $dataEst['page_meta_data'];
	$pageMetaData = @json_decode($jsonPageMetaData, true);

	if ($pageMetaData === null && json_last_error() !== JSON_ERROR_NONE) {
	    echo "incorrect data";
	}
	else {

		$estType = sc_IssetValue($pageMetaData['@type']);
		$estName = sc_IssetValue($pageMetaData['name']);

		$estURL = '';
		if (isset($pageMetaData['url']) && $pageMetaData['url']!='') $estURL = sc_ta_tripadvisor_url().$pageMetaData['url'];

		$estImage = sc_IssetValue($pageMetaData['image']);
		$estPriceRange = sc_IssetValue($pageMetaData['priceRange']);

		$estAggregateRatingType = '';
		$estAggregateRatingValue = '';
		$estAggregateRatingReviewCount = '';
		if (isset($pageMetaData['aggregateRating'])) {
			$estAggregateRatingType = sc_IssetValue($pageMetaData['aggregateRating']['@type']);
			$estAggregateRatingValue = sc_IssetValue($pageMetaData['aggregateRating']['ratingValue']);
			$estAggregateRatingReviewCount = sc_IssetInt($pageMetaData['aggregateRating']['reviewCount']);
		}

		$estAddress = '';
		$estAddressType = '';
		$estAddressStreet = '';
		$estAddressLocality = '';
		$estAddressRegion = '';
		$estAddressPostcode = '';
		$estAddressCountryType = '';
		$estAddressCountryName = '';
		if (isset($pageMetaData['address'])) {
			$estAddressType = sc_IssetValue($pageMetaData['address']['@type']);
			$estAddressStreet = sc_IssetValue($pageMetaData['address']['streetAddress']);
			$estAddressLocality = sc_IssetValue($pageMetaData['address']['addressLocality']);
			$estAddressRegion = sc_IssetValue($pageMetaData['address']['addressRegion']);
			$estAddressPostcode = sc_IssetValue($pageMetaData['address']['postalCode']);

			if (isset($pageMetaData['address']['addressCountry'])) {
				$estAddressCountryType = sc_IssetValue($pageMetaData['address']['addressCountry']['@type']);
				$estAddressCountryName = sc_IssetValue($pageMetaData['address']['addressCountry']['name']);
			}
		}

	}

	$estPageRatingOverall = sc_IssetValue($dataEst['rating_overall']);
	$estPageReviewCount = sc_IssetInt($dataEst['reviews_count']);
	$estReviewCountFriendly = sc_IssetValue($dataEst['reviews_count_friendly']);

	$estReviewStars5 = sc_IssetInt($dataEst['reviews_rating_5']);
	$estReviewStars4 = sc_IssetInt($dataEst['reviews_rating_4']);
	$estReviewStars3 = sc_IssetInt($dataEst['reviews_rating_3']);
	$estReviewStars2 = sc_IssetInt($dataEst['reviews_rating_2']);
	$estReviewStars1 = sc_IssetInt($dataEst['reviews_rating_1']);

	$estPageName = sc_IssetValue($dataEst['establishment_name']);

	$estPageAddressStreet = sc_IssetValue($dataEst['establishment_address_street']);
	$estPageAddressLocality = sc_IssetValue($dataEst['establishment_address_locality']);
	$estPageAddressCountry = sc_IssetValue($dataEst['establishment_address_country']);


	// =================================================
	// CREATE DATA 

	$estPostName = ($estName!='' ? $estName : $scQueueResultPost->post_title);

	$taEstPostID = wp_insert_post(array(
		'post_title'    => $estPostName,
		'post_status'   => 'publish',
		'post_type'     => 'sc_ta_establishment',
	));

	if (!is_wp_error($taEstPostID)) {

		// Set Queue Post
		update_field('est_queue_result_post', get_post($scQueueResultPost->ID), $taEstPostID);

		// Set Page Data
		update_field('page_meta_type', $estType, $taEstPostID);
		update_field('page_meta_name', $estName, $taEstPostID);
		update_field('page_meta_url', $estURL, $taEstPostID);
		update_field('page_meta_est_image', $estImage, $taEstPostID);
		update_field('page_meta_price_range', $estPriceRange, $taEstPostID);
		update_field('page_meta_agg_rating_type', $estAggregateRatingType, $taEstPostID);
		update_field('page_meta_agg_rating_value', $estAggregateRatingValue, $taEstPostID);
		update_field('page_meta_agg_review_count', $estAggregateRatingReviewCount, $taEstPostID);
		update_field('page_meta_address_type', $estAddressType, $taEstPostID);
		update_field('page_meta_address_street', $estAddressStreet, $taEstPostID);
		update_field('page_meta_address_locality', $estAddressLocality, $taEstPostID);
		update_field('page_meta_address_region', $estAddressRegion, $taEstPostID);
		update_field('page_meta_address_postcode', $estAddressPostcode, $taEstPostID);
		update_field('page_meta_address_country_type', $estAddressCountryType, $taEstPostID);
		update_field('page_meta_address_country_name', $estAddressCountryName, $taEstPostID);
		update_field('page_data_overall_rating', $estPageRatingOverall, $taEstPostID);
		update_field('page_data_review_count', $estPageReviewCount, $taEstPostID);
		update_field('page_data_review_count_friendly', $estReviewCountFriendly, $taEstPostID);
		update_field('page_data_review_count_stars_5', $estReviewStars5, $taEstPostID);
		update_field('page_data_review_count_stars_4', $estReviewStars4, $taEstPostID);
		update_field('page_data_review_count_stars_3', $estReviewStars3, $taEstPostID);
		update_field('page_data_review_count_stars_2', $estReviewStars2, $taEstPostID);
		update_field('page_data_review_count_stars_1', $estReviewStars1, $taEstPostID);
		update_field('page_data_est_name', $estPageName, $taEstPostID);
		update_field('page_data_address_street', $estPageAddressStreet, $taEstPostID);
		update_field('page_data_address_locality', $estPageAddressLocality, $taEstPostID);
		update_field('page_data_address_country', $estPageAddressCountry, $taEstPostID);

		return $taEstPostID;

	}

	return;
}
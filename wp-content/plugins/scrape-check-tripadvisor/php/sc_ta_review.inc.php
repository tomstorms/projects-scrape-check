<?php


function sc_ta_process_data_review($dataReview, $scEstPost) {

	// =================================================
	// PREPARE DATA 

	$reviewTitle = sc_IssetValue($dataReview['review_title']);
	$reviewContent = sc_IssetValue($dataReview['review_content']);

	$reviewURL = sc_IssetValue($dataReview['review_url']);
	$reviewID = sc_IssetValue($dataReview['review_id']);
	$reviewDateVisited = sc_IssetValue($dataReview['review_date_visited']);
	$reviewDate = sc_IssetValue($dataReview['review_date']);
	$reviewHelpfulness = sc_IssetValue($dataReview['review_helpfulness']);
	$reviewOnMobile = sc_IssetValue($dataReview['review_onmobile']);
	$reviewTripType = sc_IssetValue($dataReview['review_trip_type']);
	$reviewPartnerAttrib = sc_IssetValue($dataReview['review_partner_attribution']);
	$reviewRoomTip = sc_IssetValue($dataReview['review_room_tip']);
	$reviewRequiresTranslation = sc_IssetValue($dataReview['review_requires_translation']);
	$reviewRating = sc_IssetValue($dataReview['review_rating']);
	$reviewRatingLocation = sc_IssetValue($dataReview['review_rating_location']);
	$reviewRatingValue = sc_IssetValue($dataReview['review_rating_value']);
	$reviewRatingCleanliness = sc_IssetValue($dataReview['review_rating_cleanliness']);
	$reviewRatingService = sc_IssetValue($dataReview['review_rating_service']);
	$reviewRatingRooms = sc_IssetValue($dataReview['review_rating_rooms']);
	$reviewRatingSleepQuality = sc_IssetValue($dataReview['review_rating_sleepquality']);

	$reviewManagementRespExists = sc_IssetValue($dataReview['review_response_exists']);
	$reviewManagementRespUsername = sc_IssetValue($dataReview['review_response_username']);
	$reviewManagementRespRole = sc_IssetValue($dataReview['review_response_role']);
	$reviewManagementRespConetnt = sc_IssetValue($dataReview['review_response_content']);


	// =================================================
	// CREATE DATA 

	$taReviewPostID = wp_insert_post(array(
		'post_title'    => $reviewTitle,
		'post_content'    => $reviewContent,
		'post_status'   => 'publish',
		'post_type'     => 'sc_ta_review',
	));

	if (!is_wp_error($taReviewPostID)) {

		update_field('establishment_post', $scEstPost, $taReviewPostID);

		$taReviewerPostID = sc_ta_process_data_reviewer($dataReview);
		update_field('reviewer_post', $taReviewerPostID, $taReviewPostID);

		update_field('review_url', $reviewURL, $taReviewPostID);
		update_field('review_id', $reviewID, $taReviewPostID);
		update_field('review_date_visited', $reviewDateVisited, $taReviewPostID);
		update_field('review_date', $reviewDate, $taReviewPostID);
		update_field('review_helpfulness', $reviewHelpfulness, $taReviewPostID);
		update_field('review_on_mobile', $reviewOnMobile, $taReviewPostID);
		update_field('review_trip_type', $reviewTripType, $taReviewPostID);
		update_field('review_partner_attributes', $reviewPartnerAttrib, $taReviewPostID);
		update_field('review_room_tip', $reviewRoomTip, $taReviewPostID);
		update_field('review_requires_translation', $reviewRequiresTranslation, $taReviewPostID);
		update_field('review_rating', $reviewRating, $taReviewPostID);
		update_field('review_location_rating', $reviewRatingLocation, $taReviewPostID);
		update_field('review_value_rating', $reviewRatingValue, $taReviewPostID);
		update_field('review_cleanliness_rating', $reviewRatingCleanliness, $taReviewPostID);
		update_field('review_service_rating', $reviewRatingService, $taReviewPostID);
		update_field('review_room_rating', $reviewRatingRooms, $taReviewPostID);
		update_field('review_sleep_quality_rating', $reviewRatingSleepQuality, $taReviewPostID);

		update_field('management_response_exists', $reviewManagementRespExists, $taReviewPostID);
		update_field('management_response_username', $reviewManagementRespUsername, $taReviewPostID);
		update_field('management_response_role', $reviewManagementRespRole, $taReviewPostID);
		update_field('management_response_content', $reviewManagementRespConetnt, $taReviewPostID);

		return $taReviewPostID;

	}

	return;
}
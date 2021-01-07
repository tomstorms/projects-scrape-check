<?php


function sc_ta_process_data_reviewer($dataReview) {

	// =================================================
	// PREPARE DATA 

	$reviewAuthorUsername = sc_IssetValue($dataReview['author_username']);
	$reviewAuthorLocation = sc_IssetValue($dataReview['author_location']);
	$reviewAvatarURL = sc_IssetValue($dataReview['author_avatar']);
	$reviewContributionCount = sc_IssetValue($dataReview['author_contribution_count']);
	$reviewUserID = sc_IssetValue($dataReview['user_id']);


	// =================================================
	// CREATE DATA 

	$taReviewerPostID = wp_insert_post(array(
		'post_title'    => $reviewAuthorUsername,
		'post_status'   => 'publish',
		'post_type'     => 'sc_ta_reviewer',
	));

	if (!is_wp_error($taReviewerPostID)) {

		update_field('reviewer_user_id', $reviewUserID, $taReviewerPostID);
		update_field('reviewer_username', $reviewAuthorUsername, $taReviewerPostID);
		update_field('reviewer_location', $reviewAuthorLocation, $taReviewerPostID);
		update_field('reviewer_avatar_url', $reviewAvatarURL, $taReviewerPostID);
		update_field('reviewer_contribution_count', $reviewContributionCount, $taReviewerPostID);

		return $taReviewerPostID;

	}

	return;
}
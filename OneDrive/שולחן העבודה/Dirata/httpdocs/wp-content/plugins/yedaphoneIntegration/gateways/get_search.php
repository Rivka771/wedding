<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 15.  קבלת חיפושים שמורים של משתמש לפי caller_id (טלפון)
function yedaphone_get_search_settings( WP_REST_Request $request ) {
    $caller_id = $request->get_param('caller_id');

    if (!$caller_id) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Missing caller_id parameter.'
        ], 400);
    }

    $user_query = new WP_User_Query([
        'meta_key'     => 'phone',
        'meta_value'   => $caller_id,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ]);
    $users = $user_query->get_results();

    if (empty($users)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'User not found for the given phone number.'
        ], 404);
    }

    $user_id = $users[0];

    $searches = get_posts([
        'post_type'   => 'search',
        'post_status' => 'publish',
        'author'      => $user_id,
        'numberposts' => -1,
    ]);

    $formatted_results = [];

    foreach ($searches as $search_post) {
        $formatted_results[] = [
            'search_id'     => $search_post->ID,
            'city'          => get_post_meta($search_post->ID, 'city', true),
            'area'          => get_post_meta($search_post->ID, 'area', true),
            'type_apartment'=> get_post_meta($search_post->ID, 'type_apartment', true),
            'frequency'     => get_post_meta($search_post->ID, 'frequency', true),
            'created_at'    => $search_post->post_date,
        ];
    }

    return new WP_REST_Response([
        'status' => true,
        'data'   => $formatted_results
    ], 200);
}

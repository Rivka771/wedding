<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 4. יצירת חיפוש חדש - הוספת אזור לשדה saved_areas של המשתמש
function yedaphone_handle_search_request( WP_REST_Request $request ) {
    $caller_id      = $request->get_param('caller_id');
    $city           = $request->get_param('city');
    $area           = $request->get_param('area'); // יכול להיות "" אם נבחר "כל העיר"
    $frequency      = $request->get_param('frequency');
    $listing_type = $request->get_param('listing_type');

    if ( ! $caller_id || ! $city || ! $listing_type ) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
    }

    // חיפוש המשתמש לפי טלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $caller_id,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'User not found'), 404);
    }

    $user_id = $users[0];

    // יצירת פוסט חדש מסוג 'search'
    $search_post = array(
        'post_type'    => 'search',
        'post_title'   => 'חיפוש של משתמש #' . $user_id . ' (' . date('Y-m-d H:i:s') . ')',
        'post_status'  => 'publish',
        'post_author'  => $user_id,
    );

    $post_id = wp_insert_post($search_post);

    if (is_wp_error($post_id)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Failed to save search'), 500);
    }

    // שמירת המטא-מידע של החיפוש
    update_post_meta($post_id, 'city', $city);
    update_post_meta($post_id, 'frequency', $frequency);
    update_post_meta($post_id, 'listing_type', $listing_type);
    update_post_meta($post_id, 'user_phone', $caller_id);

    if (!empty($area)) {
        update_post_meta($post_id, 'area', $area);
    } else {
        update_post_meta($post_id, 'area', ''); // או לא לשמור כלל
    }

    return new WP_REST_Response(array(
        'status'     => true,
        'search_id'  => $post_id
    ), 200);
}

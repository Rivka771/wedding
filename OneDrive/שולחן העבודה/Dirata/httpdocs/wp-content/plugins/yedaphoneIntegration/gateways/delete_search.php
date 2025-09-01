<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 6. מחיקת חיפוש
function yedaphone_handle_delete_search_request( WP_REST_Request $request ) {
    $phone     = $request->get_param('phone_number');
    $search_id = intval($request->get_param('search_id'));

    if (!$phone || !$search_id) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Missing phone_number or search_id'
        ], 400);
    }

    // מציאת המשתמש לפי הטלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'User not found'
        ], 404);
    }

    $user_id = $users[0];

    // בדיקה שהחיפוש קיים ומשויך למשתמש
    $search_post = get_post($search_id);
    if (!$search_post || $search_post->post_type !== 'search') {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Search not found'
        ], 404);
    }

    if (intval($search_post->post_author) !== intval($user_id)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Search does not belong to the user'
        ], 403); // forbidden
    }

    // מחיקת הפוסט
    wp_delete_post($search_id, true);

    return new WP_REST_Response([
        'status' => true,
        'message' => 'Search deleted successfully'
    ], 200);
}

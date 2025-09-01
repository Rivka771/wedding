<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 12. סימון דירה
function yedaphone_handle_add_selected_house_request( WP_REST_Request $request ) {
    $phone = $request->get_param('phone_number');
    $house_id = $request->get_param('house_id');

    if (! $phone || ! $house_id) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
    }

    // חיפוש משתמש לפי הטלפון
    $user_query = new WP_User_Query(array(
        'meta_key' => 'phone',
        'meta_value' => $phone,
        'number' => 1,
        'fields' => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'User not found'), 404);
    }

    $user_id = $users[0];
    $saved = get_user_meta($user_id, 'saved_apartments', true);
    if (!is_array($saved)) $saved = [];

    // הוספת הדירה אם לא קיימת
    if (!in_array($house_id, $saved)) {
        $saved[] = $house_id;
        update_user_meta($user_id, 'saved_apartments', $saved);
    }

    return new WP_REST_Response(array('status' => 'success'), 200);
}

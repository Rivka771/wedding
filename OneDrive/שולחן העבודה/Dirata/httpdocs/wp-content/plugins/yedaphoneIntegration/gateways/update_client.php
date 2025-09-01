<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function yedaphone_handle_update_client(WP_REST_Request $request) {
    $phone     = $request->get_param('phone');
    $frequency = $request->get_param('frequency');

    // בדיקה שהשדות קיימים
    if (!$phone || !$frequency) {
        return new WP_REST_Response(array(
            'status' => false,
            'message' => 'Missing phone or frequency'
        ), 400);
    }

    // בדיקת תדירות חוקית – כולל 'no'
    $allowed = array('daily', 'weekly', 'monthly','immidiate', 'no');
    if (!in_array($frequency, $allowed)) {
        return new WP_REST_Response(array(
            'status' => false,
            'message' => 'Invalid frequency value'
        ), 400);
    }

    // חיפוש המשתמש לפי מספר טלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        return new WP_REST_Response(array(
            'status' => false,
            'message' => 'User not found'
        ), 404);
    }

    $user_id = $users[0];

    // עדכון שדה התדירות (בהנחה שזה שדה ACF)
    update_field('notification_frequency', $frequency, 'user_' . $user_id);

    return new WP_REST_Response(array(
        'status' => true,
        'message' => 'Client frequency updated',
    ), 200);
}

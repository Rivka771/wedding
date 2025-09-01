<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 1. בדיקת סטטוס מנוי
function yedaphone_handle_status_request( WP_REST_Request $request ) {
    $phone_number = $request->get_param('phone');
    $user_query = new WP_User_Query(array(
        'number' => 1,
        'meta_key' => 'phone',
        'meta_value' => $phone_number,
        'meta_compare' => '=',
        'fields' => 'ID',
    ));
    $users = $user_query->get_results();
    if ( ! empty( $users ) ) {
        return new WP_REST_Response(array('user_id' => $users[0], 'status' => true), 200);
    } else {
        return new WP_REST_Response(array('status' => false), 200);
    }
}

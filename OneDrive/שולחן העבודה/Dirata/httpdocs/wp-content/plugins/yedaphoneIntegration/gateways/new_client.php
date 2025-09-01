<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 2. רישום לקוח חדש
function yedaphone_handle_new_client_request( WP_REST_Request $request ) {
    $phone = $request->get_param('phone');
    $name = $request->get_param('name');
    $record = $request->get_param('record_name');

    if ( ! $phone || ! $name || ! $record ) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
    }

    $user_query = new WP_User_Query(array(
        'meta_key' => 'phone',
        'meta_value' => $phone,
        'meta_compare' => '=',
        'number' => 1,
        'fields' => 'ID',
    ));
    $users = $user_query->get_results();

    if ( empty( $users ) ) {
        $user_id = wp_insert_user(array(
            'user_login' => 'user_' . sanitize_user($phone),
            'user_pass'  => wp_generate_password(),
            'display_name' => $name,
            'role' => 'phone_registered_only',
        ));
        if ( is_wp_error( $user_id ) ) {
            return new WP_REST_Response(array('status' => false, 'message' => 'User creation failed'), 500);
        }
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'record_name', $record);
    } else {
        $user_id = $users[0];
        update_user_meta($user_id, 'record_name', $record);
        update_user_meta($user_id, 'name', $name);
    }

    return new WP_REST_Response(array('status' => true, 'id' => $user_id), 200);
}

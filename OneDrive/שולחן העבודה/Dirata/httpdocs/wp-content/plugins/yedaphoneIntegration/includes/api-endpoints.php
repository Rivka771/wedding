<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'rest_api_init', 'register_virtual_numbers_api_routes' );
function register_virtual_numbers_api_routes() {
    register_rest_route( 'myplugin/v1', '/assign-phone', array(
        'methods' => 'GET',
        'callback' => 'api_assign_phone_number',
        'args' => array(
            'user_id' => array(
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ),
        ),
    ));

    register_rest_route( 'myplugin/v1', '/import-phones', array(
        'methods' => 'POST',
        'callback' => 'api_import_phone_numbers',
        'args' => array(
            'phones' => array(
                'required' => true,
                'sanitize_callback' => 'sanitize_text_field'
            ),
        ),
    ));
}

function api_import_phone_numbers( WP_REST_Request $request ) {
    $phones_raw = $request->get_param('phones');
    $new_numbers = array_filter( array_map( 'trim', explode( ',', $phones_raw ) ) );

    if ( empty( $new_numbers ) ) {
        return new WP_REST_Response(array('status' => 'no_numbers_provided'), 400);
    }

    $pool = get_field('virtual_phone_pool', 'option');
    if ( ! is_array($pool) ) {
        $pool = array();
    }
    $existing_numbers = wp_list_pluck( $pool, 'virtual_phone_number' );
    $added_count = 0;

    foreach ($new_numbers as $number) {
        if ( ! in_array($number, $existing_numbers) ) {
            $pool[] = array(
                'virtual_phone_number' => $number,
                'assigned_user_id'     => '',
                'assigned_date'        => '',
            );
            $added_count++;
        }
    }

    update_field('virtual_phone_pool', $pool, 'option');

    log_virtual_phone_action('import', 0, '', "Added {$added_count} new numbers via API.");
    return new WP_REST_Response(array('status' => 'success', 'added' => $added_count), 200);
}

function api_assign_phone_number( WP_REST_Request $request ) {
    $user_id = intval( $request->get_param('user_id') );

    $pool = get_field('virtual_phone_pool', 'option');

    // Check if user already has a number.
    foreach ($pool as $row) {
        if ($row['assigned_user_id'] == $user_id) {
            return new WP_REST_Response(array('status' => 'already_assigned', 'number' => $row['virtual_phone_number']), 200);
        }
    }

    // Find an available number and assign it.
    $assigned = false;
    foreach ($pool as &$row) {
        if ( empty($row['assigned_user_id']) ) {
            $row['assigned_user_id'] = $user_id;
            $row['assigned_date'] = current_time('mysql');
            update_field('virtual_phone_pool', $pool, 'option');
            log_virtual_phone_action('assigned', $user_id, $row['virtual_phone_number'], 'Assigned via API.');
            return new WP_REST_Response(array('status' => 'success', 'number' => $row['virtual_phone_number']), 200);
        }
    }

    // If no numbers are available.
    wp_mail(get_option('admin_email'), 'Virtual Phone Number Pool Depleted', 'The virtual phone number pool is empty. Please add more numbers.');
    log_virtual_phone_action('out_of_numbers', 0, '', 'Attempted to assign a number via API, but none were available.');
    return new WP_REST_Response(array('status' => 'no_available_numbers'), 404);
}

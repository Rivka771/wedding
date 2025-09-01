<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Handle the /get_broker_number/{number} REST API request.
 * Finds a user by the provided office phone number and returns their main phone number.
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response|WP_Error Response object on success, WP_Error object on failure.
 */
function yedaphone_handle_get_broker_number_request( WP_REST_Request $request ) {
    $office_number = $request->get_param('number');

    if ( empty( $office_number ) ) {
        return new WP_REST_Response( array( 'status' => false ), 400 );
    }

    $user_query = new WP_User_Query( array(
        'meta_key'     => 'office_phone', // Assuming 'office_phone' meta key
        'meta_value'   => $office_number,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ) );

    $users = $user_query->get_results();

    if ( ! empty( $users ) ) {
        $user_id = $users[0];
        $forwarding_number = get_user_meta( $user_id, 'phone', true ); // Assuming 'phone' is the forwarding number

        if ( ! empty( $forwarding_number ) ) {
            return new WP_REST_Response( array( 'status' => true, 'data' => $forwarding_number ), 200 );
        }
    }
    
    return new WP_REST_Response( array( 'status' => false ), 404 );
}

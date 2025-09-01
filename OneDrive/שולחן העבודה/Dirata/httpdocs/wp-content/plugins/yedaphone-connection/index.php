<?php
/**
 * Plugin Name:       Yedaphone Integration
 * Description:       Connects the Yedaphone telephone system with the Dirata real estate portal.
 * Version:           1.0.0
 * Author:            shal3v
 * Author URI:        https://shal3v.com/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'YEDAPHONE_INTEGRATION_VERSION', '1.0.0' );

// Add plugin code below this line 

/**
 * Register custom REST API routes.
 * This action hook ensures our routes are registered at the correct time.
 */
add_action( 'rest_api_init', 'yedaphone_register_api_routes' );

/**
 * Defines the function that registers the REST API routes.
 * All register_rest_route calls for this plugin should happen inside this function.
 */
function yedaphone_register_api_routes() {
    register_rest_route( 'yedaphone/v1', '/status', array(
        'methods'             => 'GET',
        'callback'            => 'yedaphone_handle_status_request',
        'permission_callback' => '__return_true', // Public endpoint - consider adding security later if needed
        'args'                => array(
            'phone' => array(
                'required'          => true,
                'validate_callback' => function( $param, $request, $key ) {
                    // Basic validation: check if it's a non-empty string
                    // More specific phone validation could be added here if needed.
                    return is_string( $param ) && ! empty( trim( $param ) );
                },
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ) );

    // Note: The temporary /test route has been removed. Add it back inside this function if needed for testing.
}

/**
 * Handle the /status REST API request.
 * Finds a user by the provided phone number (ACF field 'phone') and returns their status.
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response|WP_Error Response object on success, WP_Error object on failure.
 */
function yedaphone_handle_status_request( WP_REST_Request $request ) {
    $phone_number = $request->get_param('phone');

    // Debug Log: Request received
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( '[Yedaphone Integration] Status endpoint hit. Phone number received: ' . $phone_number );
    }

    // Ensure ACF is active, otherwise we can't query the field.
    if ( ! function_exists( 'get_field' ) ) {
         // Debug Log: ACF not active
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            error_log( '[Yedaphone Integration] Error: ACF plugin is not active.' );
        }
        // Return a WP_Error for REST API
        return new WP_Error( 'acf_not_active', 'Advanced Custom Fields plugin is not active.', array( 'status' => 503 ) ); // 503 Service Unavailable might be appropriate
    }

    $args = array(
        'number'       => 1, // We only need to find one user
        'meta_key'     => 'phone',
        'meta_value'   => $phone_number,
        'meta_compare' => '=',
        'fields'       => 'ID', // We only need the user ID
    );

    // Debug Log: User query arguments
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( '[Yedaphone Integration] Querying users with args: ' . print_r( $args, true ) );
    }

    $user_query = new WP_User_Query( $args );
    $users      = $user_query->get_results();

    if ( ! empty( $users ) ) {
        // User found
        $user_id       = $users[0];
        $response_data = array(
            'user_id' => $user_id,
            'status'  => true,
        );
        // Debug Log: User found
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            error_log( '[Yedaphone Integration] User found. ID: ' . $user_id . '. Response: ' . print_r( $response_data, true ) );
        }
        return new WP_REST_Response( $response_data, 200 );
    } else {
        // No user found with that phone number
        $response_data = array(
            'status' => false,
        );
        // Debug Log: User not found
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            error_log( '[Yedaphone Integration] No user found for phone ' . $phone_number . '. Response: ' . print_r( $response_data, true ) );
        }
        // It's not technically an *error* that no user was found,
        // so we return a 200 OK status with status: false in the body.
        return new WP_REST_Response( $response_data, 200 );
    }
} 
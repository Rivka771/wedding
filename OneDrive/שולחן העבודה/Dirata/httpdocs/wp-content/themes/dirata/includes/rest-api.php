<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('yedaphone/v1', '/check-subscription/', [
        'methods'  => 'GET',
        'callback' => 'yedaphone_check_subscription',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/register-client/', [
        'methods'  => 'POST',
        'callback' => 'yedaphone_register_client',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/get-houses/', [
        'methods'  => 'GET',
        'callback' => 'yedaphone_get_houses',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/create-search/', [
        'methods'  => 'POST',
        'callback' => 'yedaphone_create_search',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/delete-search/(?P<search_id>\d+)', [
        'methods'  => 'DELETE',
        'callback' => 'yedaphone_delete_search',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/get-searches/', [
        'methods'  => 'GET',
        'callback' => 'yedaphone_get_searches',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/sign-mediation/', [
        'methods'  => 'POST',
        'callback' => 'yedaphone_sign_mediation',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/update-interaction/', [
        'methods'  => 'POST',
        'callback' => 'yedaphone_update_interaction',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/send-property-update/', [
        'methods'  => 'POST',
        'callback' => 'external_send_property_update',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/send-search-reminder/', [
        'methods'  => 'POST',
        'callback' => 'external_send_search_reminder',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('yedaphone/v1', '/send-otp-call/', [
        'methods'  => 'POST',
        'callback' => 'external_send_otp_call',
        'permission_callback' => '__return_true',
    ]);

});
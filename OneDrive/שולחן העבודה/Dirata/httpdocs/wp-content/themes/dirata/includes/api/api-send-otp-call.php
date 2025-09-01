<?php
function external_send_otp_call($request) {
    $body = json_decode($request->get_body(), true);

    if (!isset($body['client_id'], $body['phone'], $body['otp_code'])) {
        return new WP_Error('missing_params', 'Client ID, Phone, and OTP Code are required', ['status' => 400]);
    }

    // Define the external API endpoint
    $external_api_url = "https://external-api.com/send-otp-call/";

    // Prepare the request data
    $request_data = [
        'body'    => json_encode([
            'client_id' => $body['client_id'],
            'phone' => $body['phone'],
            'otp_code' => $body['otp_code']
        ]),
        'headers' => ['Content-Type' => 'application/json'],
        'method'  => 'POST'
    ];

    // Send the request to the external API
    $response = wp_remote_post($external_api_url, $request_data);

    // Check for errors
    if (is_wp_error($response)) {
        return new WP_Error('external_api_error', 'Failed to send request to external API', ['status' => 500]);
    }

    // Return the response from the external API
    return json_decode(wp_remote_retrieve_body($response), true);
}

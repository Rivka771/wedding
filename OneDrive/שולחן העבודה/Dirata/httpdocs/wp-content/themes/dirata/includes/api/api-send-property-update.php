<?php
function external_send_property_update($request) {
    $body = json_decode($request->get_body(), true);

    if (!isset($body['client_id'], $body['property_id'], $body['extension'])) {
        return new WP_Error('missing_params', 'Client ID, Property ID, and Extension are required', ['status' => 400]);
    }

    // Define the external API endpoint
    $external_api_url = "https://external-api.com/send-property-update/";

    // Prepare the request data
    $request_data = [
        'body'    => json_encode([
            'client_id' => $body['client_id'],
            'property_id' => $body['property_id'],
            'extension' => $body['extension']
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
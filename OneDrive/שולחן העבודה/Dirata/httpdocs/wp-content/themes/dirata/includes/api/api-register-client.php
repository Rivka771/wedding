<?php
function matsanu_register_client($request) {
    $body = json_decode($request->get_body(), true);

    if (!isset($body['phone'], $body['name'], $body['record_name'])) {
        return new WP_Error('missing_params', 'Phone, name, and record_name are required', ['status' => 400]);
    }

    return [
        "id" => rand(1000, 9999),
        "status" => true
    ];
}
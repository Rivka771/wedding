<?php
function matsanu_check_subscription($request) {
    $phone = $request->get_param('phone');

    if (!$phone) {
        return new WP_Error('missing_phone', 'Phone number is required', ['status' => 400]);
    }

    $mock_data = [
        "0548439972" => ["id" => 1234, "status" => true],
        "0500000000" => ["status" => false]
    ];

    return isset($mock_data[$phone]) ? $mock_data[$phone] : ["status" => false];
}
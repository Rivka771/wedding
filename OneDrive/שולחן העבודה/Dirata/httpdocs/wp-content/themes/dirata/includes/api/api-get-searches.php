<?php
function matsanu_get_searches($request) {
    $client_id = $request->get_param('client_id');

    if (!$client_id) {
        return new WP_Error('missing_client_id', 'Client ID is required', ['status' => 400]);
    }

    return [
        ["search_id" => 836, "city" => "בני ברק", "rooms" => 4.5, "floor" => 2],
        ["search_id" => 837, "city" => "ירושלים", "rooms" => 3, "floor" => 1]
    ];
}

<?php
function matsanu_create_search($request) {
    $body = json_decode($request->get_body(), true);

    if (!$body) {
        return new WP_Error('missing_params', 'Search parameters are required', ['status' => 400]);
    }

    return [
        "search_id" => rand(100, 999)
    ];
}

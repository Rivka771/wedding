<?php
function matsanu_delete_search($request) {
    $search_id = $request->get_param('search_id');

    if (!$search_id) {
        return new WP_Error('missing_search_id', 'Search ID is required', ['status' => 400]);
    }

    return ["status" => "success"];
}

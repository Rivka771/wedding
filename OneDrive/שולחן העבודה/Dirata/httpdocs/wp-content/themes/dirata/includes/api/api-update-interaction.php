<?php
function matsanu_update_interaction($request) {
    $body = json_decode($request->get_body(), true);

    if (!isset($body['client_id'], $body['house_id'], $body['call_record'])) {
        return new WP_Error('missing_params', 'Client ID, House ID, and Call Record are required', ['status' => 400]);
    }

    return ["status" => "success", "interaction_updated" => true];
}

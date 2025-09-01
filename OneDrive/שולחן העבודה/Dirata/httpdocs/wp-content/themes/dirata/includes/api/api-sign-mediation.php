<?php
function matsanu_sign_mediation($request) {
    $body = json_decode($request->get_body(), true);

    if (!isset($body['client_id'], $body['house_id'])) {
        return new WP_Error('missing_params', 'Client ID and House ID are required', ['status' => 400]);
    }

    return ["status" => "success", "mediation_signed" => true];
}

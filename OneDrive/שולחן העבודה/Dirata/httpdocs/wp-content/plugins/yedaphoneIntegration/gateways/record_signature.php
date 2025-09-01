<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function yedaphone_handle_record_signature_request(WP_REST_Request $request) {
    $params = $request->get_json_params();

    $caller_id = isset($params['caller_id']) ? sanitize_text_field($params['caller_id']) : '';
    $house_id = isset($params['house_id']) ? intval($params['house_id']) : 0;
    $id_number = isset($params['id_number']) ? sanitize_text_field($params['id_number']) : '';
    $record_file_base64 = $params['record_file'] ?? null;

    // רק שלושת שדות החובה נבדקים
    if (empty($caller_id) || empty($house_id) || empty($id_number)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Missing required parameters'
        ], 400);
    }

    $file_url = null;
    $file_path = null;

    if (!empty($record_file_base64)) {
        // ניקוי הקידומת אם קיימת
        if (preg_match('/^data:\w+\/[\w\-\+]+;base64,/', $record_file_base64)) {
            $record_file_base64 = preg_replace('/^data:\w+\/[\w\-\+]+;base64,/', '', $record_file_base64);
        }

        $decoded_file = base64_decode($record_file_base64);

        if ($decoded_file !== false && strlen($decoded_file) > 0) {
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['path'];
            $upload_url = $upload_dir['url'];

            $file_name = 'recording_' . time() . '.wav';
            $full_path = trailingslashit($upload_path) . $file_name;

            $saved = file_put_contents($full_path, $decoded_file);

            if ($saved !== false) {
                $file_url = trailingslashit($upload_url) . $file_name;
                $file_path = $full_path;
            }
        }
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'signatures';

    $inserted = $wpdb->insert(
        $table_name,
        array(
            'caller_id'   => $caller_id,
            'house_id'    => $house_id,
            'id_number'   => $id_number,
            'file_url'    => $file_url,
            'file_path'   => $file_path,
            'uploaded_at' => current_time('mysql')
        ),
        array('%s', '%d', '%s', '%s', '%s', '%s')
    );

    if ($inserted === false) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Database insert failed'
        ], 500);
    }

    return new WP_REST_Response([
        'status' => true,
        'message' => 'Signature saved successfully',
        'file_uploaded' => !empty($file_url)
    ], 200);
}

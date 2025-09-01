<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 10. פניה למתווך (כולל הקלטה ותמיכה ב-Base64)
function yedaphone_handle_calling_broker_request( WP_REST_Request $request ) {
    error_log('yedaphone_handle_calling_broker_request: Function started.');

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $phone_number = $request->get_param('phone_number');
    $caller_id = $request->get_param('caller_id');
    $record_base64 = $request->get_param('record_base64'); // פרמטר חדש לקובץ Base64

    error_log('yedaphone_handle_calling_broker_request: Request params: phone_number=' . $phone_number . ', caller_id=' . $caller_id . ', record_base64=' . (empty($record_base64) ? 'empty' : 'present') . ', record_file=' . (empty($_FILES['record_file']) ? 'empty' : 'present'));

    if (! $phone_number || ! $caller_id || (empty($_FILES['record_file']) && empty($record_base64)) ) {
        error_log('yedaphone_handle_calling_broker_request: Missing data or file.');
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing data or file'), 400);
    }

    $attachment_id = 0;

    // טיפול בקובץ שהועלה ישירות
    if (!empty($_FILES['record_file'])) {
        error_log('yedaphone_handle_calling_broker_request: Handling direct file upload.');
        $attachment_id = media_handle_upload('record_file', 0);
        if (is_wp_error($attachment_id)) {
            error_log('yedaphone_handle_calling_broker_request: File upload failed: ' . $attachment_id->get_error_message());
            return new WP_REST_Response(array('status' => false, 'message' => 'File upload failed: ' . $attachment_id->get_error_message()), 500);
        }
        error_log('yedaphone_handle_calling_broker_request: Direct file upload successful. Attachment ID: ' . $attachment_id);
    }
    // טיפול בקובץ Base64
    elseif (!empty($record_base64)) {
        error_log('yedaphone_handle_calling_broker_request: Handling Base64 file upload.');
        
        $mime_type = 'audio/wav'; // Default MIME type
        $file_data_string = $record_base64;

        // Check if the string is a data URI and parse it
        if (strpos($file_data_string, ';base64,') !== false) {
            list($type, $file_data_string) = explode(';base64,', $file_data_string, 2);
            // Get the mime type from the first part if it exists
            if (strpos($type, 'data:') === 0) {
                $mime_type = substr($type, 5);
            }
        }

        // Decode the data, using strict mode to catch errors
        $decoded_data = base64_decode($file_data_string, true);

        // Ensure decoding was successful and the file is not empty
        if ($decoded_data === false || empty($decoded_data)) {
            error_log('yedaphone_handle_calling_broker_request: Failed to decode Base64 string or the result is empty.');
            return new WP_REST_Response(array('status' => false, 'message' => 'Invalid or empty Base64 data provided.'), 500);
        }

        error_log('yedaphone_handle_calling_broker_request: Base64 MIME type: ' . $mime_type);

        // זיהוי סיומת לפי MIME
        switch ($mime_type) {
            case 'audio/mpeg':
            case 'audio/mp3':
                $file_extension = 'mp3';
                break;
            case 'audio/wav':
            case 'audio/x-wav':
                $file_extension = 'wav';
                break;
            case 'audio/ogg':
                $file_extension = 'ogg';
                break;
            case 'audio/webm':
                $file_extension = 'webm';
                break;
            default:
                $file_extension = 'wav'; // ברירת מחדל
        }
        error_log('yedaphone_handle_calling_broker_request: Determined file extension: ' . $file_extension);

        // יצירת שם קובץ זמני
        $upload_dir = wp_upload_dir();
        $file_name = 'call_record_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir['path'] . '/' . $file_name;

        // שמירת הקובץ הזמני
        file_put_contents($file_path, $decoded_data);
        error_log('yedaphone_handle_calling_broker_request: Saved temporary file to: ' . $file_path);

        // הכנה להעלאה למדיה של וורדפרס
        $file_array = array(
            'name'     => $file_name,
            'type'     => $mime_type,
            'tmp_name' => $file_path,
            'error'    => 0,
            'size'     => filesize($file_path),
        );

        // העלאה למדיה ליברירי
        // $attachment_id = media_handle_sideload($file_array, 0);
        $upload_overrides = array( 'test_form' => false );
        $movefile = wp_handle_sideload( $file_array, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            $attachment = array(
                'guid'           => $movefile['url'],
                'post_mime_type' => $movefile['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment, $movefile['file'] );
        } else {
            $attachment_id = new WP_Error( 'upload_error', $movefile['error'] );
        }


        // מחיקת הקובץ הזמני
        if ( file_exists( $file_path ) ) {
            unlink($file_path);
        }

        if (is_wp_error($attachment_id)) {
            error_log('yedaphone_handle_calling_broker_request: Base64 file upload failed: ' . $attachment_id->get_error_message());
            return new WP_REST_Response(array('status' => false, 'message' => 'Base64 file upload failed: ' . $attachment_id->get_error_message()), 500);
        }
        error_log('yedaphone_handle_calling_broker_request: Base64 file upload successful. Attachment ID: ' . $attachment_id);
    }

    error_log('yedaphone_handle_calling_broker_request: Creating broker_call post with attachment ID: ' . $attachment_id);
    $call_id = wp_insert_post(array(
        'post_type'  => 'broker_call',
        'post_status' => 'publish',
        'meta_input' => array(
            'broker_phone' => $phone_number,
            'caller_id'    => $caller_id,
            'call_record'  => $attachment_id,
        )
    ));

    if (is_wp_error($call_id)) {
        error_log('yedaphone_handle_calling_broker_request: Failed to create broker call post: ' . $call_id->get_error_message());
        return new WP_REST_Response(array('status' => false, 'message' => 'Failed to create broker call post: ' . $call_id->get_error_message()), 500);
    }
    error_log('yedaphone_handle_calling_broker_request: Successfully created broker call post. Post ID: ' . $call_id);

    return new WP_REST_Response(array('status' => 'success', 'id' => $call_id), 200);
}

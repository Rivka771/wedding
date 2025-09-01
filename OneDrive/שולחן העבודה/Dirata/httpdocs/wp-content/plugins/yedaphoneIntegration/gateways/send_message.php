<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 11. שליחת הודעה למנהל
function yedaphone_handle_send_message_request( WP_REST_Request $request ) {
    error_log('yedaphone_handle_send_message_request started');

    $phone_number = $request->get_param('phone_number');
    $record_base64 = $request->get_param('files'); // תמיכה גם ב-Base64

    error_log('Phone number: ' . $phone_number);
    error_log('Record Base64 provided: ' . (empty($record_base64) ? 'No' : 'Yes'));
    error_log('Record File provided: ' . (empty($_FILES['record_file']) ? 'No' : 'Yes'));

    if (!$phone_number || (empty($_FILES['record_file']) && empty($record_base64))) {
        error_log('Missing phone number or file');
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing phone number or file'), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attachment_id = 0;

    //קובץ רגיל
    if (!empty($_FILES['record_file'])) {
        $attachment_id = media_handle_upload('record_file', 0);
        if (is_wp_error($attachment_id)) {
            error_log('Upload failed: ' . $attachment_id->get_error_message());
            return new WP_REST_Response(array('status' => false, 'message' => 'Upload failed: ' . $attachment_id->get_error_message()), 500);
        }
        error_log('Attachment created from file upload. ID: ' . $attachment_id);
    }

    //Base64
    elseif (!empty($record_base64)) {
        error_log('Processing Base64 record');
        // ניתוח פורמט Base64
        list($type, $data) = explode(';', $record_base64);
        list(, $data) = explode(',', $data);
        $decoded_data = base64_decode($data);

        // זיהוי MIME
        preg_match('/data:(.*?);/', $record_base64, $matches);
        $mime_type = $matches[1];
        error_log('MIME type: ' . $mime_type);

        // המרת MIME לסיומת
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

        // יצירת קובץ זמני
        $upload_dir = wp_upload_dir();
        $file_name = 'admin_message_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir['path'] . '/' . $file_name;
        file_put_contents($file_path, $decoded_data);
        error_log('Temporary file created: ' . $file_path);

        // הכנה להעלאה ל־Media
        $file_array = array(
            'name'     => $file_name,
            'type'     => $mime_type,
            'tmp_name' => $file_path,
            'error'    => 0,
            'size'     => filesize($file_path),
        );
        error_log('File array for sideload: ' . print_r($file_array, true));

        // $attachment_id = media_handle_sideload($file_array, 0);
        $upload_overrides = array( 'test_form' => false );
        $movefile = wp_handle_sideload( $file_array, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            error_log('File sideload successful: ' . print_r($movefile, true));
            $attachment = array(
                'guid'           => $movefile['url'],
                'post_mime_type' => $movefile['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment, $movefile['file'] );
            error_log('Attachment created from Base64. ID: ' . $attachment_id);
        } else {
            error_log('File sideload error: ' . $movefile['error']);
            $attachment_id = new WP_Error( 'upload_error', $movefile['error'] );
        }


        // מחיקת קובץ זמני
        if ( file_exists( $file_path ) ) {
            unlink($file_path);
            error_log('Temporary file deleted: ' . $file_path);
        }

        if (is_wp_error($attachment_id)) {
            error_log('Base64 file upload failed: ' . $attachment_id->get_error_message());
            return new WP_REST_Response(array('status' => false, 'message' => 'Base64 file upload failed: ' . $attachment_id->get_error_message()), 500);
        }
    }

    //יצירת פוסט מסוג admin_message
    error_log('Creating admin_message post with attachment ID: ' . $attachment_id);
    $message_id = wp_insert_post(array(
        'post_type'  => 'admin_message',
        'post_status' => 'publish',
        'meta_input' => array(
            'from_phone'     => $phone_number,
            'message_record' => $attachment_id,
        )
    ));

    if (is_wp_error($message_id)) {
        error_log('Failed to create message post: ' . $message_id->get_error_message());
        return new WP_REST_Response(array('status' => false, 'message' => 'Failed to create message post: ' . $message_id->get_error_message()), 500);
    }

    error_log('Successfully created admin_message post. ID: ' . $message_id);
    return new WP_REST_Response(array('status' => 'success', 'id' => $message_id), 200);
}

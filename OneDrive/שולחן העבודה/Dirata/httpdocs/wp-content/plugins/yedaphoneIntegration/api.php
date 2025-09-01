<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'rest_api_init', 'yedaphone_register_api_routes' );

function yedaphone_register_api_routes() {
    register_rest_route( 'yedaphone/v1', '/status', array(
        'methods'  => 'GET',
        'callback' => 'yedaphone_handle_status_request',
        'permission_callback' => '__return_true',
        'args' => array(
            'phone' => array(
                'required' => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    register_rest_route( 'yedaphone/v1', '/new_client', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_new_client_request',
        'permission_callback' => '__return_true',
    ));

    // 3. קבלת דירות לפי חיפוש
    register_rest_route( 'yedaphone/v1', '/get_search_data', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_get_search_data_request',
        'permission_callback' => '__return_true',
    ));

    // 4. יצירת חיפוש חדש
    register_rest_route( 'yedaphone/v1', '/search', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_search_request',
        'permission_callback' => '__return_true',
    ));

    // 5. עדכון חיפוש קיים
    register_rest_route( 'yedaphone/v1', '/update_search', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_update_search_request',
        'permission_callback' => '__return_true',
    ));

    // 6. מחיקת חיפוש
    register_rest_route( 'yedaphone/v1', '/delete_search', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_delete_search_request',
        'permission_callback' => '__return_true',
    ));


    // 7. קבלת פרטים על דירה מסוימת
    register_rest_route( 'yedaphone/v1', '/get_data_house/(?P<house_id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'yedaphone_handle_get_data_house_request',
        'permission_callback' => '__return_true',
    ));

    // 8. קבלת רשימת ערים
      register_rest_route('yedaphone/v1', '/get_cities', array(
        'methods'  => 'GET',
        'callback' => 'yedaphone_handle_get_cities_request',
        'permission_callback' => '__return_true',
    ));


    // 9. חתימה קולית על תיווך
    register_rest_route( 'yedaphone/v1', '/record_signature', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_record_signature_request',
        'permission_callback' => '__return_true',
    ));

    // 10. פניה למתווך (כולל הקלטה)
    register_rest_route( 'yedaphone/v1', '/calling_broker', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_calling_broker_request',
        'permission_callback' => '__return_true',
    ));

    // 11. שליחת הודעה למנהל
    register_rest_route( 'yedaphone/v1', '/send_message', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_send_message_request',
        'permission_callback' => '__return_true',
    ));


    // 12. סימון דירה
    register_rest_route( 'yedaphone/v1', '/add_selected_house', array(
        'methods'  => 'POST',
        'callback' => 'yedaphone_handle_add_selected_house_request',
        'permission_callback' => '__return_true',
    ));

    // 13. שליפת הדירות שסומנו
    register_rest_route( 'yedaphone/v1', '/get_selected_house/(?P<phone_number>[\d\-+]+)', array(
        'methods'  => 'GET',
        'callback' => 'yedaphone_handle_get_selected_house_request',
        'permission_callback' => '__return_true',
    ));
    // 14. קבלת רשימת אזורים לפי עיר
    register_rest_route( 'yedaphone/v1', '/get_areas_by_city', array(
        'methods'  => 'GET',
        'callback' => 'yedaphone_handle_get_areas_by_city_request',
        'permission_callback' => '__return_true',
    ));
    
      // 15.  קבלת חיפושים שמורים של משתמש לפי caller_id (טלפון)
     register_rest_route('yedaphone/v1', '/get_search/(?P<caller_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'yedaphone_get_search_settings',
        'permission_callback' => '__return_true',
    ));

    //16.שיחת אימות (OTP) להקראת קוד קולי, ללקוח שנרשם בטלפון
       register_rest_route('yedaphone/v1', '/generate_otp/(?P<phone>\d+)', array(
        'methods' => 'GET',
        'callback' => 'yedaphone_generate_otp_code',
        'permission_callback' => '__return_true',
        'args' => [
            'phone' => [
                'type' => 'string',
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param) && strlen($param) >= 6;
                }
            ]
        ]
    ));

    //17. סינון דירה לפי פרמטר של שכירות או מכירה
    register_rest_route('yedaphone/v1', '/get_apartments_by_type', array(
        'methods' => 'GET',
        'callback' => 'yedaphone_get_apartments_by_type',
        'permission_callback' => '__return_true',
        'args' => array(
            'listing_type' => array(
                'required' => true,
                'sanitize_callback' => function($param) {
                    return trim(sanitize_text_field($param));
                },
                'validate_callback' => function($param) {
                    return in_array(trim($param), ['rent', 'sale']);
                }
            )
        )
    ));
    

    
        register_rest_route('yedaphone/v1', '/client/', array(
            'methods'             => 'PUT',
            'callback'            => 'yedaphone_handle_update_client',
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'yedaphone/v1', '/get_broker_number/(?P<number>[\d\-+]+)', array(
            'methods'  => 'GET',
            'callback' => 'yedaphone_handle_get_broker_number_request',
            'permission_callback' => '__return_true',
        ));
}

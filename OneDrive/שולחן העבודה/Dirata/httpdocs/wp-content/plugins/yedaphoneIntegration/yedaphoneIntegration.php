<?php
/**
 * Plugin Name:       Yedaphone Integration
 * Description:       Connects the Yedaphone telephone system with the Dirata real estate portal.
 * Version:           1.0.0
 * Author:            shal3v
 * Author URI:        https://shal3v.com/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'YEDAPHONE_INTEGRATION_VERSION', '1.0.0' );

// Add plugin code below this line 

/**
 * Register custom REST API routes.
 * This action hook ensures our routes are registered at the correct time.
 */

add_action( 'rest_api_init', 'yedaphone_register_api_routes' );
/**
 * Defines the function that registers the REST API routes.
 * All register_rest_route calls for this plugin should happen inside this function.
 */
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

}
/**
 * Handle the /status REST API request.
 * Finds a user by the provided phone number (ACF field 'phone') and returns their status.
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response|WP_Error Response object on success, WP_Error object on failure.
 */

// 1. בדיקת סטטוס מנוי
function yedaphone_handle_status_request( WP_REST_Request $request ) {
    $phone_number = $request->get_param('phone');
    $user_query = new WP_User_Query(array(
        'number' => 1,
        'meta_key' => 'phone',
        'meta_value' => $phone_number,
        'meta_compare' => '=',
        'fields' => 'ID',
    ));
    $users = $user_query->get_results();
    if ( ! empty( $users ) ) {
        return new WP_REST_Response(array('user_id' => $users[0], 'status' => true), 200);
    } else {
        return new WP_REST_Response(array('status' => false), 200);
    }
}

// 2. רישום לקוח חדש
function yedaphone_handle_new_client_request( WP_REST_Request $request ) {
    $phone = $request->get_param('phone');
    $name = $request->get_param('name');
    $record = $request->get_param('record_name');

    if ( ! $phone || ! $name || ! $record ) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
    }

    $user_query = new WP_User_Query(array(
        'meta_key' => 'phone',
        'meta_value' => $phone,
        'meta_compare' => '=',
        'number' => 1,
        'fields' => 'ID',
    ));
    $users = $user_query->get_results();

    if ( empty( $users ) ) {
        $user_id = wp_insert_user(array(
            'user_login' => 'user_' . sanitize_user($phone),
            'user_pass'  => wp_generate_password(),
            'display_name' => $name,
            'role' => 'phone_registered_only',
        ));
        if ( is_wp_error( $user_id ) ) {
            return new WP_REST_Response(array('status' => false, 'message' => 'User creation failed'), 500);
        }
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'record_name', $record);
    } else {
        $user_id = $users[0];
        update_user_meta($user_id, 'record_name', $record);
        update_user_meta($user_id, 'name', $name);
    }

    return new WP_REST_Response(array('status' => true, 'id' => $user_id), 200);
}

// 3. קבלת דירות לפי חיפוש
// function yedaphone_handle_get_search_data_request( WP_REST_Request $request ) {
//     $caller_id = $request->get_param('caller_id');
//     $city = $request->get_param('city');
//     $area = $request->get_param('area');
//     $rooms = $request->get_param('rooms');
//     $floor = $request->get_param('floor');

//     $meta_query = array('relation' => 'AND');
//     if ($city) $meta_query[] = array('key' => 'city', 'value' => $city);
//     if ($area) $meta_query[] = array('key' => 'area', 'value' => $area);
//     if ($rooms) $meta_query[] = array('key' => 'rooms', 'value' => $rooms);
//     if ($floor) $meta_query[] = array('key' => 'floor', 'value' => $floor);

//     $query = new WP_Query(array(
//         'post_type' => 'apartment',
//         'posts_per_page' => -1,
//         'meta_query' => $meta_query,
//     ));

//     $results = array();
//     foreach ($query->posts as $post) {
//         $results[] = array(
//             'id' => $post->ID,
//             'city' => get_field('city', $post->ID),
//             'gate' => get_field('area', $post->ID),
//             'elevator' => get_field('elevator', $post->ID),
//             'phone' => get_field('phone', $post->ID),
//             'create_at' => get_the_date('Y-m-d', $post->ID),
//         );
//     }

//     return new WP_REST_Response($results, 200);
// }
function yedaphone_handle_get_search_data_request( WP_REST_Request $request ) {
    $caller_id = $request->get_param('caller_id');
    $city = $request->get_param('city');
    $area = $request->get_param('area');
    $rooms = $request->get_param('rooms');
    $floor = $request->get_param('floor');
    $listing_type = $request->get_param('listing_type'); // למשל: "מכירה" או "שכירות"

    // תנאי חיפוש לפי שדות מותאמים אישית
    $meta_query = array('relation' => 'AND');
    if ($rooms) $meta_query[] = array('key' => 'rooms', 'value' => $rooms);
    if ($floor) $meta_query[] = array('key' => 'floor', 'value' => $floor);
    if ($listing_type) $meta_query[] = array('key' => 'listing_type', 'value' => $listing_type);

    // תנאי חיפוש לפי טקסונומיות (עיר, אזור)
    $tax_query = array('relation' => 'AND');
    if ($city) {
        $tax_query[] = array(
            'taxonomy' => 'city',
            'field' => 'name',
            'terms' => $city,
        );
    }
    if ($area) {
        $tax_query[] = array(
            'taxonomy' => 'area',
            'field' => 'name',
            'terms' => $area,
        );
    }

    // ביצוע השאילתה
    $query = new WP_Query(array(
        'post_type' => 'apartment',
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
        'tax_query' => $tax_query,
    ));

    // הרכבת התוצאה
    $results = array();
    foreach ($query->posts as $post) {
        $results[] = array(
            'id' => $post->ID,
            'city' => ($terms = get_the_terms($post->ID, 'city')) ? $terms[0]->name : '',
            'gate' => ($terms = get_the_terms($post->ID, 'area')) ? $terms[0]->name : '',
            'elevator' => in_array('מעלית', get_field('whats_inside', $post->ID) ?: []),
            'phone' => get_user_meta($post->post_author, 'phone', true),
            'create_at' => get_the_date('Y-m-d', $post->ID),
            'listing_type' => get_field('listing_type', $post->ID), // הוספת סוג העסקה
        );
    }

    return new WP_REST_Response($results, 200);
}



// 4. יצירת חיפוש חדש - הוספת אזור לשדה saved_areas של המשתמש

function yedaphone_handle_search_request( WP_REST_Request $request ) {
    $caller_id      = $request->get_param('caller_id');
    $city           = $request->get_param('city');
    $area           = $request->get_param('area'); // יכול להיות "" אם נבחר "כל העיר"
    $frequency      = $request->get_param('frequency');
    $listing_type = $request->get_param('listing_type');

    if ( ! $caller_id || ! $city || ! $listing_type ) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
    }

    // חיפוש המשתמש לפי טלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $caller_id,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'User not found'), 404);
    }

    $user_id = $users[0];

    // יצירת פוסט חדש מסוג 'search'
    $search_post = array(
        'post_type'    => 'search',
        'post_title'   => 'חיפוש של משתמש #' . $user_id . ' (' . date('Y-m-d H:i:s') . ')',
        'post_status'  => 'publish',
        'post_author'  => $user_id,
    );

    $post_id = wp_insert_post($search_post);

    if (is_wp_error($post_id)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Failed to save search'), 500);
    }

    // שמירת המטא-מידע של החיפוש
    update_post_meta($post_id, 'city', $city);
    update_post_meta($post_id, 'frequency', $frequency);
    update_post_meta($post_id, 'listing_type', $listing_type);
    update_post_meta($post_id, 'user_phone', $caller_id);

    if (!empty($area)) {
        update_post_meta($post_id, 'area', $area);
    } else {
        update_post_meta($post_id, 'area', ''); // או לא לשמור כלל
    }

    return new WP_REST_Response(array(
        'status'     => true,
        'search_id'  => $post_id
    ), 200);
}



// 5. עדכון חיפוש קיים
// function yedaphone_handle_update_search_request( WP_REST_Request $request ) {
//     $search_id = $request->get_param('search_id');
//     $city = $request->get_param('city');
//     $area = $request->get_param('area');
//     $frequency = $request->get_param('frequency');

//     if (! $search_id || ! get_post($search_id)) {
//         return new WP_REST_Response(array('status' => false), 404);
//     }

//     if ($city) update_post_meta($search_id, 'city', $city);
//     if ($area) update_post_meta($search_id, 'area', $area);
//     if ($frequency) update_post_meta($search_id, 'frequency', $frequency);

//     return new WP_REST_Response(array('status' => true), 200);
// }
// 5. עדכון תדירות קבלת עדכונים
// function yedaphone_handle_update_search_request( WP_REST_Request $request ) {
//     $caller_id = $request->get_param('caller_id');
//     $frequency = $request->get_param('frequency');

//     if ( ! $caller_id || ! $frequency ) {
//         return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
//     }

//     $user_query = new WP_User_Query(array(
//         'meta_key' => 'phone',
//         'meta_value' => $caller_id,
//         'meta_compare' => '=',
//         'number' => 1,
//         'fields' => 'ID',
//     ));

//     $users = $user_query->get_results();
//     if ( empty( $users ) ) {
//         return new WP_REST_Response(array('status' => false, 'message' => 'User not found'), 404);
//     }

//     $user_id = $users[0];
//     update_user_meta($user_id, 'notification_frequency', $frequency); // שם שדה ACF

//     return new WP_REST_Response(array('status' => true), 200);
// }


//5
function yedaphone_handle_update_search_request(WP_REST_Request $request) {
    // קבלת הפרמטרים מהבקשה JSON
    $params = $request->get_json_params();

    // בדיקות תקינות - לוודא שכל הפרמטרים קיימים
    if (
        empty($params['search_id']) ||
        empty($params['city']) ||
        empty($params['area']) ||
        empty($params['frequency']) ||
        empty($params['caller_id']) ||
        empty($params['listing_type']) 
    ) {
        return new WP_REST_Response(
            array('status' => false, 'message' => 'Missing required parameters'),
            400
        );
    }

    $search_id = intval($params['search_id']);
    $city = sanitize_text_field($params['city']);
    $area = sanitize_text_field($params['area']);
    $frequency = sanitize_text_field($params['frequency']);
    $caller_id = sanitize_text_field($params['caller_id']);
    $listing_type = sanitize_text_field($params['listing_type']); // Get type_apartment

    global $wpdb;

    // נניח שיש טבלה בשם wp_searches עם שדות city, area, frequency, caller_id ו-id
    $table_name = $wpdb->prefix . 'searches';

     // Update post meta fields for the 'search' custom post type
     update_post_meta($search_id, 'city', $city);
     update_post_meta($search_id, 'area', $area);
     update_post_meta($search_id, 'frequency', $frequency);
     update_post_meta($search_id, 'listing_type', $listing_type); 
        if ($updated === false) {
        // שגיאה בעדכון
        return new WP_REST_Response(array('status' => false, 'message' => 'Database update failed'), 500);
    }
     return new WP_REST_Response(array('status' => true, 'message' => 'Search updated successfully'), 200);
 }
 



// 6. מחיקת חיפוש
function yedaphone_handle_delete_search_request( WP_REST_Request $request ) {
    $phone     = $request->get_param('phone_number');
    $search_id = intval($request->get_param('search_id'));

    if (!$phone || !$search_id) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Missing phone_number or search_id'
        ], 400);
    }

    // מציאת המשתמש לפי הטלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'User not found'
        ], 404);
    }

    $user_id = $users[0];

    // בדיקה שהחיפוש קיים ומשויך למשתמש
    $search_post = get_post($search_id);
    if (!$search_post || $search_post->post_type !== 'search') {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Search not found'
        ], 404);
    }

    if (intval($search_post->post_author) !== intval($user_id)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Search does not belong to the user'
        ], 403); // forbidden
    }

    // מחיקת הפוסט
    wp_delete_post($search_id, true);

    return new WP_REST_Response([
        'status' => true,
        'message' => 'Search deleted successfully'
    ], 200);
}




// 7. קבלת פרטים על דירה מסוימת
function yedaphone_handle_get_data_house_request( WP_REST_Request $request ) {
    $house_id = $request->get_param('house_id');

    if (! get_post($house_id)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Not found'), 404);
    }

    // שליפת שדות ACF
    $rooms    = get_field('rooms', $house_id);
    $floor    = get_field('floor', $house_id);
    $size     = get_field('size', $house_id);
    $price    = get_field('price', $house_id);
    $street   = get_field('apartment_street', $house_id);
    $extras   = get_field('whats_inside', $house_id);
    $images   = get_field('apartment_images', $house_id); // גלריית תמונות
    $record   = get_field('record', $house_id);
    $listing_type = get_field('listing_type', $house_id); // Added: 'שכירות' or 'מכירה'

    // טקסונומיות
    $city_terms = get_the_terms($house_id, 'city');
    $area_terms = get_the_terms($house_id, 'area');
    $city = ($city_terms && !is_wp_error($city_terms)) ? $city_terms[0]->name : '';
    $area = ($area_terms && !is_wp_error($area_terms)) ? $area_terms[0]->name : '';

    $text_parts = [];

    if ($city && $area) $text_parts[] = "דירה ב{$city} - {$area}";
    elseif ($city) $text_parts[] = "דירה ב{$city}";
    if ($street) $text_parts[] = "ברחוב {$street}";
    if ($floor) $text_parts[] = "בקומה {$floor}";
    if ($rooms) $text_parts[] = "עם {$rooms} חדרים";
    if ($size) $text_parts[] = "בשטח של {$size} מטרים רבועים";
    if ($price) $text_parts[] = "במחיר של ₪" . number_format($price, 0, '.', ',');

    if (is_array($extras) && count($extras)) {
        $text_parts[] = "כוללת " . implode(', ', $extras);
    }

    $full_text = implode(', ', $text_parts) . ".";

    $image_urls = [];
    if (is_array($images)) {
        foreach ($images as $img) {
            if (isset($img['url'])) {
                $image_urls[] = $img['url'];
            }
        }
    }

    return new WP_REST_Response(array(
        'status' => true,
        'text'   => $full_text,
        'record' => $record ?: null,
        'data'   => array(
            'rooms'   => $rooms,
            'floor'   => $floor,
            'size'    => $size,
            'price'   => $price,
            'street'  => $street,
            'extras'  => $extras,
            'images'  => $image_urls,
            'city'    => $city,
            'area'    => $area,
            'listing_type' => $listing_type,
        )
    ), 200);
}

// 8. קבלת רשימת ערים
function yedaphone_handle_get_cities_request( WP_REST_Request $request ) {
    $cities_terms = get_terms(array(
        'taxonomy'   => 'city',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC'
    ));

    $cities = array();

    foreach ($cities_terms as $city) {
        $cities[] = array(
            'id'   => $city->term_id,
            'name' => $city->name
        );
    }

    return new WP_REST_Response(array(
        'status' => true,
        'cities' => $cities
    ), 200);
}

// 9. חתימה קולית על תיווך
// function yedaphone_handle_record_signature_request( WP_REST_Request $request ) {
//     require_once ABSPATH . 'wp-admin/includes/file.php';
//     require_once ABSPATH . 'wp-admin/includes/media.php';
//     require_once ABSPATH . 'wp-admin/includes/image.php';

//     $house_id = $request->get_param('house_id');
//     $id_number = $request->get_param('id_number');

//     if (! $house_id || ! $id_number || empty($_FILES['record_file'])) {
//         return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters or file'), 400);
//     }

//     // העלאה למדיה
//     $attachment_id = media_handle_upload('record_file', 0);
//     if (is_wp_error($attachment_id)) {
//         return new WP_REST_Response(array('status' => false, 'message' => 'Upload failed'), 500);
//     }

//     update_post_meta($house_id, 'signature_record', $attachment_id);
//     update_post_meta($house_id, 'signer_id', $id_number);

//     return new WP_REST_Response(array('status' => 'success'), 200);
// }

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


// 10. פניה למתווך (כולל הקלטה ותמיכה ב-Base64)
function yedaphone_handle_calling_broker_request( WP_REST_Request $request ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $phone_number = $request->get_param('phone_number');
    $caller_id = $request->get_param('caller_id');
    $record_base64 = $request->get_param('record_base64'); // פרמטר חדש לקובץ Base64

    if (! $phone_number || ! $caller_id || (empty($_FILES['record_file']) && empty($record_base64)) ) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing data or file'), 400);
    }

    $attachment_id = 0;

    // טיפול בקובץ שהועלה ישירות
    if (!empty($_FILES['record_file'])) {
        $attachment_id = media_handle_upload('record_file', 0);
        if (is_wp_error($attachment_id)) {
            return new WP_REST_Response(array('status' => false, 'message' => 'File upload failed: ' . $attachment_id->get_error_message()), 500);
        }
    }
    // טיפול בקובץ Base64
    elseif (!empty($record_base64)) {
        // מנתח את ה-data URI (לדוגמה: "data:audio/mp3;base64,...")
        list($type, $data) = explode(';', $record_base64);
        list(, $data)      = explode(',', $data);
        $decoded_data = base64_decode($data);

        // מזהה את סוג הקובץ (לדוגמה, "audio/mp3")
        preg_match('/data:(.*?);/', $record_base64, $matches);
        $mime_type = $matches[1];

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

        // יצירת שם קובץ זמני
        $upload_dir = wp_upload_dir();
        $file_name = 'call_record_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir['path'] . '/' . $file_name;

        // שמירת הקובץ הזמני
        file_put_contents($file_path, $decoded_data);

        // הכנה להעלאה למדיה של וורדפרס
        $file_array = array(
            'name'     => $file_name,
            'type'     => $mime_type,
            'tmp_name' => $file_path,
            'error'    => 0,
            'size'     => filesize($file_path),
        );

        // העלאה למדיה ליברירי
        $attachment_id = media_handle_sideload($file_array, 0);

        // מחיקת הקובץ הזמני
        unlink($file_path);

        if (is_wp_error($attachment_id)) {
            return new WP_REST_Response(array('status' => false, 'message' => 'Base64 file upload failed: ' . $attachment_id->get_error_message()), 500);
        }
    }

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
        return new WP_REST_Response(array('status' => false, 'message' => 'Failed to create broker call post: ' . $call_id->get_error_message()), 500);
    }

    return new WP_REST_Response(array('status' => 'success', 'id' => $call_id), 200);
}

// 11. שליחת הודעה למנהל
function yedaphone_handle_send_message_request( WP_REST_Request $request ) {
    $phone_number = $request->get_param('phone_number');
    $record_base64 = $request->get_param('record_base64'); // תמיכה גם ב-Base64

    if (!$phone_number || (empty($_FILES['record_file']) && empty($record_base64))) {
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
            return new WP_REST_Response(array('status' => false, 'message' => 'Upload failed: ' . $attachment_id->get_error_message()), 500);
        }
    }

    //Base64
    elseif (!empty($record_base64)) {
        // ניתוח פורמט Base64
        list($type, $data) = explode(';', $record_base64);
        list(, $data) = explode(',', $data);
        $decoded_data = base64_decode($data);

        // זיהוי MIME
        preg_match('/data:(.*?);/', $record_base64, $matches);
        $mime_type = $matches[1];

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

        // הכנה להעלאה ל־Media
        $file_array = array(
            'name'     => $file_name,
            'type'     => $mime_type,
            'tmp_name' => $file_path,
            'error'    => 0,
            'size'     => filesize($file_path),
        );

        $attachment_id = media_handle_sideload($file_array, 0);

        // מחיקת קובץ זמני
        unlink($file_path);

        if (is_wp_error($attachment_id)) {
            return new WP_REST_Response(array('status' => false, 'message' => 'Base64 file upload failed: ' . $attachment_id->get_error_message()), 500);
        }
    }

    //יצירת פוסט מסוג admin_message
    $message_id = wp_insert_post(array(
        'post_type'  => 'admin_message',
        'post_status' => 'publish',
        'meta_input' => array(
            'from_phone'     => $phone_number,
            'message_record' => $attachment_id,
        )
    ));

    if (is_wp_error($message_id)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Failed to create message post: ' . $message_id->get_error_message()), 500);
    }

    return new WP_REST_Response(array('status' => 'success', 'id' => $message_id), 200);
}

// 12. סימון דירה
// 12. סימון דירה - מתוקן!
function yedaphone_handle_add_selected_house_request( WP_REST_Request $request ) {
    $phone = $request->get_param('phone_number');
    $house_id = $request->get_param('house_id');

    // 🔍 לוגינג לעקוב אחרי הבקשות
    error_log("🏠 Yedaphone: Trying to save apartment $house_id for phone $phone");

    if (! $phone || ! $house_id) {
        error_log("❌ Yedaphone: Missing parameters - phone: $phone, house_id: $house_id");
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing parameters'), 400);
    }

    // ✅ בדיקה שה-house_id הוא באמת דירה
    $post = get_post($house_id);
    if (!$post || $post->post_type !== 'apartment') {
        error_log("❌ Yedaphone: Invalid apartment ID $house_id - post type: " . ($post ? $post->post_type : 'not found'));
        return new WP_REST_Response(array('status' => false, 'message' => 'Invalid apartment ID'), 400);
    }

    // חיפוש משתמש לפי הטלפון
    $user_query = new WP_User_Query(array(
        'meta_key' => 'phone',
        'meta_value' => $phone,
        'number' => 1,
        'fields' => 'ID',
    ));

    $users = $user_query->get_results();
    if (empty($users)) {
        error_log("❌ Yedaphone: User not found for phone $phone");
        return new WP_REST_Response(array('status' => false, 'message' => 'User not found'), 404);
    }

    $user_id = $users[0];
    error_log("✅ Yedaphone: Found user ID $user_id for phone $phone");

    // 🎯 שינוי קריטי! משתמשים ב-ACF כמו במערכת WordPress
    $saved = get_field('saved_apartments', 'user_' . $user_id);
    if (!is_array($saved)) {
        $saved = [];
    }

    // בדיקה שהדירה לא כבר שמורה
    if (!in_array($house_id, $saved)) {
        $saved[] = $house_id;
        // 🎯 שינוי קריטי! משתמשים ב-ACF
        update_field('saved_apartments', $saved, 'user_' . $user_id);
        error_log("✅ Yedaphone: Successfully saved apartment $house_id for user $user_id");
        return new WP_REST_Response(array('status' => 'success', 'message' => 'Apartment saved'), 200);
    } else {
        error_log("ℹ️ Yedaphone: Apartment $house_id already saved for user $user_id");
        return new WP_REST_Response(array('status' => 'success', 'message' => 'Apartment already saved'), 200);
    }
}


// 13. שליפת הדירות שסומנו
function yedaphone_handle_get_selected_house_request( WP_REST_Request $request ) {
    $phone_number = $request->get_param('phone_number');

    if (!$phone_number) {
        return new WP_REST_Response(array('status' => false, 'message' => 'Missing phone_number'), 400);
    }

    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone_number,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));
    $users = $user_query->get_results();

    if (empty($users)) {
        return new WP_REST_Response(array('status' => false, 'message' => 'User not found'), 404);
    }

    $user_id = $users[0];
    $saved_apartments = get_user_meta($user_id, 'saved_apartments', true);

    if (!is_array($saved_apartments) || empty($saved_apartments)) {
        return new WP_REST_Response(array('status' => true, 'houses' => []), 200); // No selected houses
    }

    $house_data = [];
    foreach ($saved_apartments as $house_id) {
        $post = get_post($house_id);
        if ($post && $post->post_type === 'apartment') {
            $house_data[] = array(
                'id'        => $post->ID,
                'city'      => ($terms = get_the_terms($post->ID, 'city')) ? $terms[0]->name : '',
                'area'      => ($terms = get_the_terms($post->ID, 'area')) ? $terms[0]->name : '',
                'rooms'     => get_field('rooms', $post->ID),
                'floor'     => get_field('floor', $post->ID),
                'price'     => get_field('price', $post->ID),
                'listing_type' => get_field('listing_type', $post->ID), // Include deal_type
            );
        }
    }

    return new WP_REST_Response(array('status' => true, 'houses' => $house_data), 200);
}

// 14. קבלת רשימת אזורים לפי עיר מסוימת (כאשר associated_city הוא שדה טקסונומיה)
function yedaphone_handle_get_areas_by_city_request( WP_REST_Request $request ) {
    $city_id = intval($request->get_param('city_id'));
    if (!$city_id) {
        return new WP_REST_Response(array(
            'status' => false,
            'message' => 'Missing or invalid city_id'
        ), 400);
    }

    // מקבלים את כל מונחי הטקסונומיה 'area' (אזורים)
    $all_areas = get_terms(array(
        'taxonomy' => 'area',
        'hide_empty' => false,
    ));

    if (is_wp_error($all_areas)) {
        return new WP_REST_Response(array(
            'status' => false,
            'message' => 'Error fetching areas'
        ), 500);
    }

    $filtered_areas = [];

    foreach ($all_areas as $area) {
        // מקבלים את השדה associated_city (טקסונומיה) של האזור
        $associated_city = get_field('associated_city', 'area_' . $area->term_id);

        $associated_city_id = null;
        if (is_array($associated_city) && isset($associated_city['term_id'])) {
            $associated_city_id = $associated_city['term_id'];
        } elseif (is_object($associated_city) && isset($associated_city->term_id)) {
            $associated_city_id = $associated_city->term_id;
        } elseif (is_numeric($associated_city)) {
            $associated_city_id = intval($associated_city);
        }

        if ($associated_city_id === $city_id) {
            $filtered_areas[] = [
                'id' => $area->term_id,
                'name' => $area->name,
            ];
        }
    }

    return new WP_REST_Response(array(
        'status' => true,
        'city_id' => $city_id,
        'areas' => $filtered_areas,
    ), 200);
}


// 15.  קבלת חיפושים שמורים של משתמש לפי caller_id (טלפון)
function yedaphone_get_search_settings( WP_REST_Request $request ) {
    $caller_id = $request->get_param('caller_id');

    if (!$caller_id) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Missing caller_id parameter.'
        ], 400);
    }

    $user_query = new WP_User_Query([
        'meta_key'     => 'phone',
        'meta_value'   => $caller_id,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ]);
    $users = $user_query->get_results();

    if (empty($users)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'User not found for the given phone number.'
        ], 404);
    }

    $user_id = $users[0];

    $searches = get_posts([
        'post_type'   => 'search',
        'post_status' => 'publish',
        'author'      => $user_id,
        'numberposts' => -1,
    ]);

    $formatted_results = [];

    foreach ($searches as $search_post) {
        $formatted_results[] = [
            'search_id'     => $search_post->ID,
            'city'          => get_post_meta($search_post->ID, 'city', true),
            'area'          => get_post_meta($search_post->ID, 'area', true),
            'type_apartment'=> get_post_meta($search_post->ID, 'type_apartment', true),
            'frequency'     => get_post_meta($search_post->ID, 'frequency', true),
            'created_at'    => $search_post->post_date,
        ];
    }

    return new WP_REST_Response([
        'status' => true,
        'data'   => $formatted_results
    ], 200);
}


//16. שיחת אימות (OTP) להקראת קוד קולי, ללקוח שנרשם בטלפון
function yedaphone_generate_otp_code(WP_REST_Request $request) {
    $phone = $request->get_param('phone');

    // חיפוש יוזר לפי מספר טלפון
    $user_query = new WP_User_Query(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone,
        'meta_compare' => '=',
        'number'       => 1,
        'fields'       => 'ID',
    ));
    $users = $user_query->get_results();

    if (empty($users)) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Customer does not exist'
        ], 404);
    }

    $user_id = $users[0];

    // יצירת קוד OTP רנדומלי (6 ספרות)
    $otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $timestamp = current_time('mysql'); // חותמת זמן בפורמט MySQL (לפי זמן האתר)

    // שמירת ה־OTP והזמן למטה־של המשתמש
    update_user_meta($user_id, 'otp_code', $otp_code);
    update_user_meta($user_id, 'otp_created_at', $timestamp);

    return new WP_REST_Response([
        'status'         => true,
        'otp_code'       => $otp_code,
        'otp_created_at' => $timestamp
    ], 200);
}
   // 17. סינון דירה לפי פרמטר של שכירות או מכירה
   function yedaphone_get_apartments_by_type(WP_REST_Request $request) {
    $type_label = trim($request->get_param('listing_type'));

    // בדיקה שהתבקש ערך חוקי
    if (!in_array($type_label, ['rent', 'sale'])) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'Invalid value. Only "rent" or "sale" are allowed.'
        ], 400);
    }

    // שאילתת הדירות לפי המטא
    $query = new WP_Query(array(
        'post_type' => 'apartment',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'listing_type',
                'value' => $type_label,
                'compare' => '='
            )
        )
    ));

    $results = [];

    foreach ($query->posts as $post) {
        $results[] = array(
            'id' => $post->ID,
            'title' => get_the_title($post->ID),
            'listing_type' => get_post_meta($post->ID, 'listing_type', true),
            'image' => get_the_post_thumbnail_url($post->ID, 'medium'),
            'link' => get_permalink($post->ID),
        );
    }

    return new WP_REST_Response([
        'status' => true,
        'listing_type' => $type_label,
        'count' => count($results),
        'data' => $results
    ], 200);
}


    
    function yedaphone_handle_update_client(WP_REST_Request $request) {
        $phone     = $request->get_param('phone');
        $frequency = $request->get_param('frequency');
    
        // בדיקה שהשדות קיימים
        if (!$phone || !$frequency) {
            return new WP_REST_Response(array(
                'status' => false,
                'message' => 'Missing phone or frequency'
            ), 400);
        }
    
        // בדיקת תדירות חוקית – כולל 'no'
        $allowed = array('daily', 'weekly', 'monthly','immidiate', 'no');
        if (!in_array($frequency, $allowed)) {
            return new WP_REST_Response(array(
                'status' => false,
                'message' => 'Invalid frequency value'
            ), 400);
        }
    
        // חיפוש המשתמש לפי מספר טלפון
        $user_query = new WP_User_Query(array(
            'meta_key'     => 'phone',
            'meta_value'   => $phone,
            'meta_compare' => '=',
            'number'       => 1,
            'fields'       => 'ID',
        ));
    
        $users = $user_query->get_results();
        if (empty($users)) {
            return new WP_REST_Response(array(
                'status' => false,
                'message' => 'User not found'
            ), 404);
        }
    
        $user_id = $users[0];
    
        // עדכון שדה התדירות (בהנחה שזה שדה ACF)
        update_field('notification_frequency', $frequency, 'user_' . $user_id);
    
        return new WP_REST_Response(array(
            'status' => true,
            'message' => 'Client frequency updated',
        ), 200);
    }
    

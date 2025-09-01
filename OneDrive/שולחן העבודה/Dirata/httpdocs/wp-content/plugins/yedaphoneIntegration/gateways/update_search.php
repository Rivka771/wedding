<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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

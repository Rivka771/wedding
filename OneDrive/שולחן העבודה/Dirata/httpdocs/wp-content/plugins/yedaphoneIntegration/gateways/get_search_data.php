<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// 3. קבלת דירות לפי חיפוש
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

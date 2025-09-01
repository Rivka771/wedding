<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

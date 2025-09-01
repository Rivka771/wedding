<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

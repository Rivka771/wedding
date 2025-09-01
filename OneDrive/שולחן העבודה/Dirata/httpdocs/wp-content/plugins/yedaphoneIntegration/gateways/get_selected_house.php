<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

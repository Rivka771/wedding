<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

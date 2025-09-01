<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

<?php
/**
 * Functions and definitions for Dirata theme
 */
 
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
// include get_template_directory() . '/popup-edit-apartment.php';

function dirata_theme_setup() {
    // Enable post thumbnails
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'dirata_theme_setup');
require_once get_template_directory() . '/includes/rest-api.php';

/**
 * Redirect guest users from profile page to login page
 */
add_action( 'template_redirect', function() {
    // אם המשתמש לא מחובר וביקש את עמוד הפרופיל
    if ( ! is_user_logged_in() && is_page('profile-page') ) {
        wp_redirect( '/login/' ); // מפנה לעמוד ההתחברות
        exit;
    }
});

/**
 * Enqueue scripts and styles
 */
function dirata_enqueue_scripts() {
    // Enqueue CSS files
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '4.4.1');
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css', array(), '5.15.4');
    wp_enqueue_style('jquery-ui', get_template_directory_uri() . '/assets/css/jquery-ui.css', array(), '5.15.4');
    wp_enqueue_style('nice-select', get_template_directory_uri() . '/assets/css/nice-select.css', array(), '1.0.0');
    wp_enqueue_style('default-skin', get_template_directory_uri() . '/assets/css/default-skin.css', array(), '1.0.0');
    wp_enqueue_style('photoswipe-css', get_template_directory_uri() . '/assets/css/photoswipe.css', array(), '1.0.0');
    wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/css/slick.css', array(), '1.0.0');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/style.css', array(), '1.0.0');
    wp_enqueue_style('responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), '1.0.0');
    wp_enqueue_script('plugins', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('nice-select', get_template_directory_uri() . '/assets/js/jquery.nice-select.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('apartments-filter', get_template_directory_uri() . '/assets/js/apartments-filter.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('utils-js', get_template_directory_uri() . '/assets/js/utils.js', array('jquery'), '1.0.0', true);
    // wp_enqueue_script('photoswipe-main', get_template_directory_uri() . '/assets/js/photoswipe_main.js', array('jquery'), '1.0.0', true);
    // wp_enqueue_script('photoswipe-ui-default', get_template_directory_uri() . '/assets/js/photoswipe-ui-default.js', array('jquery'), '1.0.0', true);
    // wp_enqueue_script('photoswipe', get_template_directory_uri() . '/assets/js/photoswipe.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('slick-min', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('jquery-ui-js', get_template_directory_uri() . '/assets/js/jquery-ui.js', array('jquery'), '1.0.0', true);
    
    error_log('debug');

    // Localized variables
    $dirata_vars = array(
        'ajaxurl'   => admin_url('admin-ajax.php'),
        'themeUrl'  => get_template_directory_uri(),
        'currentUserId' => get_current_user_id(),
    );

    if (!is_array($dirata_vars)) {
        error_log('⚠️ dirata_vars is not an array!');
    }
    
    // Attach to multiple scripts
    wp_localize_script('main-js', 'dirataVars', $dirata_vars);
    wp_localize_script('apartments-filter', 'dirataVars', $dirata_vars);
    wp_localize_script('utils-js', 'dirataVars', $dirata_vars);
    
}
add_action('wp_enqueue_scripts', 'dirata_enqueue_scripts');
function enqueue_photoswipe_local() {
    wp_enqueue_style('photoswipe-css', get_template_directory_uri() . '/assets/css/photoswipe.css', [], null);
    wp_enqueue_script('photoswipe-core', get_template_directory_uri() . '/assets/js/photoswipe.umd.min.js', [], null, true);
    wp_enqueue_script('photoswipe-lightbox', get_template_directory_uri() . '/assets/js/photoswipe-lightbox.umd.min.js', ['photoswipe-core'], null, true);
    wp_enqueue_script('photoswipe-init', get_template_directory_uri() . '/assets/js/photoswipe-init.js', ['photoswipe-lightbox'], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_photoswipe_local');





/**
 * Register Custom Role Type: Broker (\"מתווך\")
 */
function dirata_add_broker_role_once() {
    if (get_option('dirata_mediator_role_created') !== 'yes') {

        $role_name = 'broker';
        $display_name = 'מתווך';
        $capabilities = array(
            'read' => true,  // יכולת לקרוא
            'edit_posts' => true,  // יכולת לערוך פוסטים
            'upload_files' => true,  // יכולת להעלות קבצים
            // ניתן להוסיף יותר יכולות לפי הצורך
        );

        // הוספת התפקיד
        add_role( $role_name, $display_name, $capabilities );
        update_option('dirata_mediator_role_created', 'yes');
    }
}
add_action('init', 'dirata_add_broker_role_once');
// הסתרת סרגל הכלים של וורדפרס
add_action('after_setup_theme', function () {
    if (current_user_can('broker') && !is_admin()) {
        show_admin_bar(false);
    }
});

/**
 * Register Custom Role Type: phone_registered_only (\"נרשם טלפונית\")
 */
// function dirata_add_phone_registered_only_role_once() {
//     if (get_option('dirata_mediator_role_created') !== 'yes') {

//         $role_name = 'phone_registered_only';
//         $display_name = 'נרשם טלפונית';
//         $capabilities = array(
//             'read' => true,  // יכולת לקרוא
//             'edit_posts' => false,  // יכולת לערוך פוסטים
//             'upload_files' => true,  // יכולת להעלות קבצים
//             // ניתן להוסיף יותר יכולות לפי הצורך
//         );

//         // הוספת התפקיד
//         add_role( $role_name, $display_name, $capabilities );
//         update_option('dirata_mediator_role_created', 'yes');
//     }
// }
// add_action('init', 'dirata_add_phone_registered_only_role_once');
add_action('init', function () {
    $role_name = 'phone_registered_only';
    $display_name = 'נרשם טלפונית';
    $capabilities = array(
        'read' => true,
        'edit_posts' => false,
        'upload_files' => true,
    );

    if (!get_role($role_name)) {
        add_role($role_name, $display_name, $capabilities);
    }
});

// הסתרת סרגל הכלים של וורדפרס
add_action('after_setup_theme', function () {
    if (current_user_can('phone_registered_only') && !is_admin()) {
        show_admin_bar(false);
    }
});


/**
 * Register lead: lead (\"ליד\")
 */
function dirata_register_lead_post_type() {
    $args = array(
        'label' => 'לידים',
        'singular_name' => 'lead',
        'public' => false, // לא פומבי = לא בעמודים רגילים
        'show_ui' => true, // כן נגיש בלוח הבקרה
        'show_in_menu' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'custom-fields'),
        'show_in_rest' => false, // מוסתר מה-REST API (Gutenberg, API וכו')
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'rewrite' => false, // אין URL ציבורי
    );

    register_post_type('lead', $args);
}
add_action('init', 'dirata_register_lead_post_type');

function dirata_remove_lead_from_yoast_sitemap($excluded_post_types) {
    $excluded_post_types[] = 'lead';
    return $excluded_post_types;
}
add_filter('wpseo_sitemap_exclude_post_type', 'dirata_remove_lead_from_yoast_sitemap');



/**
 * Register Custom Post Type: Apartment (\"דירה\")
 */
function dirata_register_apartment_post_type() {
    $labels = array(
        'name'                  => __('Apartments', 'dirata'),
        'singular_name'         => __('Apartment', 'dirata'),
        'menu_name'             => __('Apartments', 'dirata'),
        'name_admin_bar'        => __('Apartment', 'dirata'),
        'add_new'               => __('Add New', 'dirata'),
        'add_new_item'          => __('Add New Apartment', 'dirata'),
        'edit_item'             => __('Edit Apartment', 'dirata'),
        'new_item'              => __('New Apartment', 'dirata'),
        'view_item'             => __('View Apartment', 'dirata'),
        'search_items'          => __('Search Apartments', 'dirata'),
        'not_found'             => __('No Apartments Found', 'dirata'),
        'not_found_in_trash'    => __('No Apartments Found in Trash', 'dirata'),
        'all_items'             => __('All Apartments', 'dirata'),
        'archives'              => __('Apartment Archives', 'dirata'),
        'insert_into_item'      => __('Insert into Apartment', 'dirata'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'apartments'),
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-building',
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author'),
        'show_in_rest'          => true,
    );

    register_post_type('apartment', $args);
}
add_action('init', 'dirata_register_apartment_post_type');
function dirata_register_menus() {
    register_nav_menus(array(
        'main_menu'   => __('Main Menu', 'dirata'),
        'mobile_menu' => __('Mobile Menu', 'dirata'),
    ));
}
add_action('init', 'dirata_register_menus');
function dirata_register_taxonomies() {
    // City Taxonomy
    $labels = array(
        'name'              => __('Cities', 'dirata'),
        'singular_name'     => __('City', 'dirata'),
        'search_items'      => __('Search Cities', 'dirata'),
        'all_items'         => __('All Cities', 'dirata'),
        'parent_item'       => __('Parent City', 'dirata'),
        'parent_item_colon' => __('Parent City:', 'dirata'),
        'edit_item'         => __('Edit City', 'dirata'),
        'update_item'       => __('Update City', 'dirata'),
        'add_new_item'      => __('Add New City', 'dirata'),
        'new_item_name'     => __('New City Name', 'dirata'),
        'menu_name'         => __('Cities', 'dirata'),
    );

    register_taxonomy('city', 'apartment', array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'city'),
    ));
}
add_action('init', 'dirata_register_taxonomies');
function dirata_register_street_taxonomy() {
    // Street Taxonomy
    $labels = array(
        'name'              => __('Streets', 'dirata'),
        'singular_name'     => __('Street', 'dirata'),
        'search_items'      => __('Search Streets', 'dirata'),
        'all_items'         => __('All Streets', 'dirata'),
        'parent_item'       => __('Parent Street', 'dirata'),
        'parent_item_colon' => __('Parent Street:', 'dirata'),
        'edit_item'         => __('Edit Street', 'dirata'),
        'update_item'       => __('Update Street', 'dirata'),
        'add_new_item'      => __('Add New Street', 'dirata'),
        'new_item_name'     => __('New Street Name', 'dirata'),
        'menu_name'         => __('Streets', 'dirata'),
    );

    register_taxonomy('street', 'apartment', array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'street'),
    ));
}
add_action('init', 'dirata_register_street_taxonomy');
function dirata_add_city_meta_field() {
    $cities = get_terms(array('taxonomy' => 'city', 'hide_empty' => false));
    if (!empty($cities) && !is_wp_error($cities)) {
        ?>
        <div class="form-field term-group">
            <label for="city-selector"><?php _e('Associated City', 'dirata'); ?></label>
            <select id="city-selector" name="associated_city">
                <option value=""><?php _e('Select a City', 'dirata'); ?></option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->term_id); ?>"><?php echo esc_html($city->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}
add_action('street_add_form_fields', 'dirata_add_city_meta_field');
add_action('street_edit_form_fields', 'dirata_edit_city_meta_field');

function dirata_edit_city_meta_field($term) {
    $associated_city = get_term_meta($term->term_id, 'associated_city', true);
    $cities = get_terms(array('taxonomy' => 'city', 'hide_empty' => false));
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="city-selector"><?php _e('Associated City', 'dirata'); ?></label></th>
        <td>
            <select id="city-selector" name="associated_city">
                <option value=""><?php _e('Select a City', 'dirata'); ?></option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->term_id); ?>" <?php selected($associated_city, $city->term_id); ?>><?php echo esc_html($city->name); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php
}
function dirata_save_street_meta($term_id) {
    if (isset($_POST['associated_city']) && '' !== $_POST['associated_city']) {
        update_term_meta($term_id, 'associated_city', absint($_POST['associated_city']));
    } else {
        delete_term_meta($term_id, 'associated_city');
    }
}
add_action('created_street', 'dirata_save_street_meta');
add_action('edited_street', 'dirata_save_street_meta');

function dirata_register_area_taxonomy() {
    $labels = array(
        'name'              => __('Areas', 'dirata'),
        'singular_name'     => __('Area', 'dirata'),
        'search_items'      => __('Search Areas', 'dirata'),
        'all_items'         => __('All Areas', 'dirata'),
        'parent_item'       => __('Parent Area', 'dirata'),
        'parent_item_colon' => __('Parent Area:', 'dirata'),
        'edit_item'         => __('Edit Area', 'dirata'),
        'update_item'       => __('Update Area', 'dirata'),
        'add_new_item'      => __('Add New Area', 'dirata'),
        'new_item_name'     => __('New Area Name', 'dirata'),
        'menu_name'         => __('Areas', 'dirata'),
    );

    register_taxonomy('area', 'apartment', array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'area'),
    ));
}
add_action('init', 'dirata_register_area_taxonomy');

function dirata_add_area_meta_field() {
    $cities = get_terms(array('taxonomy' => 'city', 'hide_empty' => false));
    if (!empty($cities) && !is_wp_error($cities)) {
        ?>
        <div class="form-field term-group">
            <label for="area-associated-city"><?php _e('Associated City', 'dirata'); ?></label>
            <select id="area-associated-city" name="associated_city">
                <option value=""><?php _e('Select a City', 'dirata'); ?></option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->term_id); ?>"><?php echo esc_html($city->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}
add_action('area_add_form_fields', 'dirata_add_area_meta_field');
add_action('area_edit_form_fields', 'dirata_edit_area_meta_field');

function dirata_edit_area_meta_field($term) {
    $associated_city = get_term_meta($term->term_id, 'associated_city', true);
    $cities = get_terms(array('taxonomy' => 'city', 'hide_empty' => false));
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="area-associated-city"><?php _e('Associated City', 'dirata'); ?></label></th>
        <td>
            <select id="area-associated-city" name="associated_city">
                <option value=""><?php _e('Select a City', 'dirata'); ?></option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->term_id); ?>" <?php selected($associated_city, $city->term_id); ?>>
                        <?php echo esc_html($city->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php
}
function dirata_save_area_meta($term_id) {
    if (isset($_POST['associated_city']) && '' !== $_POST['associated_city']) {
        update_term_meta($term_id, 'associated_city', absint($_POST['associated_city']));
    } else {
        delete_term_meta($term_id, 'associated_city');
    }
}
add_action('created_area', 'dirata_save_area_meta');
add_action('edited_area', 'dirata_save_area_meta');


function enqueue_mapbox_scripts() {
    // Enqueue Mapbox CSS
    wp_enqueue_style('mapbox-css', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css');

    // Enqueue Mapbox JS
    wp_enqueue_script('mapbox-js', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js', [], null, true);

    wp_enqueue_script('mapbox-gl-rtl-text', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.3/mapbox-gl-rtl-text.js', ['mapbox-js'], null, true);

    wp_enqueue_script('mapbox-gl-language', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-language/v1.0.0/mapbox-gl-language.js', ['mapbox-js'], null, true);

    // Add a custom script for map initialization
    $mapbox_init_version = filemtime(get_template_directory() . '/assets/js/mapbox-init.js');
    wp_enqueue_script('custom-mapbox', get_template_directory_uri() . '/assets/js/mapbox-init.js', ['mapbox-js', 'mapbox-gl-language', 'mapbox-gl-rtl-text'], $mapbox_init_version, true);

    // Localize script to pass Mapbox access token
    wp_localize_script('custom-mapbox', 'mapboxSettings', [
        'accessToken' => 'pk.eyJ1Ijoic2hhbDN2IiwiYSI6ImNtNW10OXluNzA0Y2wybHM3Zm0xaGo3bncifQ.jZ4d6Zqe-z-afJt6g7beCQ', // Replace with your Mapbox token
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_mapbox_scripts');

/**
 * List apartments 
 */
function dirata_list_apartments($args = array(), $refresh_map = false) {
    $default_args = array(
        'post_type'      => 'apartment',
        'posts_per_page' => 10,
    );

    $query_args = wp_parse_args($args, $default_args);
    $apartment_query = new WP_Query($query_args);

    ob_start();

    if ($apartment_query->have_posts()) :
        while ($apartment_query->have_posts()) : $apartment_query->the_post();
            // Include the new template part for a single apartment listing.
            set_query_var('col_width', 6);
            get_template_part('template-parts/apartment-item');
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>' . __('No apartments found.', 'dirata') . '</p>';
    endif;

    $html_output = ob_get_clean();
    return $html_output;
}


/**
 * Filter apartments 
 */
function dirata_get_streets_by_city() {
    if (!isset($_GET['city']) || empty($_GET['city'])) {
        wp_send_json([]); // No city selected, return empty array
    }

    $city_id = intval($_GET['city']);
    $streets = get_terms([
        'taxonomy'   => 'street',
        'hide_empty' => false,
    ]);

    $street_options = [];

    if (!empty($streets) && !is_wp_error($streets)) {
        foreach ($streets as $street) {
            $associated_city = get_term_meta($street->term_id, 'associated_city', true);
            if ($associated_city == $city_id) {
                $street_options[] = [
                    'id'   => $street->term_id,
                    'name' => $street->name
                ];
            }
        }
    }

    wp_send_json($street_options);
}
add_action('wp_ajax_get_streets', 'dirata_get_streets_by_city');
add_action('wp_ajax_nopriv_get_streets', 'dirata_get_streets_by_city');

function dirata_ajax_filter_apartments() {
    $args = array(
        'post_type'      => 'apartment',
        'posts_per_page' => 10,
        'meta_query'     => array('relation' => 'AND'),
        'tax_query'      => array(),
    );

    // Filtering by City
    if (!empty($_POST['city']) && intval($_POST['city']) > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'city',
            'field'    => 'term_id',
            'terms'    => intval($_POST['city']),
        );
    }

    // Filtering by Area
    if (!empty($_POST['area']) && intval($_POST['area']) > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'area',
            'field'    => 'term_id',
            'terms'    => intval($_POST['area']),
        );
    }

    // Filtering by listing_type (rent or sale)
    if (!empty($_POST['listing_type'])) {
        $args['meta_query'][] = array(
            'key'     => 'listing_type',
            'value'   => sanitize_text_field($_POST['listing_type']),
            'compare' => '=',
        );
    }

    // Filtering by Price
    if (!empty($_POST['price_min']) || !empty($_POST['price_max'])) {
        $args['meta_query'][] = array(
            'key'     => 'price',
            'value'   => array(intval($_POST['price_min']), intval($_POST['price_max'])),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    // Filtering by Number of Rooms
    if (!empty($_POST['rooms'])) {
        $room_filters = array('relation' => 'OR');
        foreach ($_POST['rooms'] as $room) {
            $room_filters[] = array(
                'key'     => 'rooms',
                'value'   => floatval($room),
                'compare' => '=',
                'type'    => 'NUMERIC',
            );
        }
        $args['meta_query'][] = $room_filters;
    }

    // Filtering by Floor (min and/or max)
    if (!empty($_POST['floor_min']) || !empty($_POST['floor_max'])) {
        $floor_min = !empty($_POST['floor_min']) ? intval($_POST['floor_min']) : -2;
        $floor_max = !empty($_POST['floor_max']) ? intval($_POST['floor_max']) : 2000;
        $args['meta_query'][] = array(
            'key'     => 'floor',
            'value'   => array($floor_min, $floor_max),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }

    // Filtering by Size (מ"ר)
    if (!empty($_POST['size_min']) || !empty($_POST['size_max'])) {
        // Remove the " מ״ר" suffix (if present) before converting to an integer.
        $size_min = !empty($_POST['size_min']) ? intval(str_replace(" מ״ר", "", $_POST['size_min'])) : 20;
        $size_max = !empty($_POST['size_max']) ? intval(str_replace(" מ״ר", "", $_POST['size_max'])) : 9999;
        $args['meta_query'][] = array(
            'key'     => 'size',
            'value'   => array($size_min, $size_max),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }

    // Ensure Query Works if No Filters Are Selected
    if (empty($args['tax_query'])) {
        unset($args['tax_query']);
    }
    if (empty($args['meta_query']) || $args['meta_query'] === array('relation' => 'AND')) {
        unset($args['meta_query']);
    }

    echo dirata_list_apartments($args, true);

    wp_die();
}
add_action('wp_ajax_dirata_ajax_filter_apartments', 'dirata_ajax_filter_apartments');
add_action('wp_ajax_nopriv_dirata_ajax_filter_apartments', 'dirata_ajax_filter_apartments');

function get_term_id_by_name_callback() {
    // Get and sanitize parameters.
    $taxonomy  = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
    $term_name = isset($_POST['term_name']) ? sanitize_text_field($_POST['term_name']) : '';
    
    if ( empty($taxonomy) || empty($term_name) ) {
        wp_send_json_success( array( 'term_id' => '' ) );
    }
    
    // Look up the term by its name.
    $term = get_term_by('name', $term_name, $taxonomy);
    if ( $term && ! is_wp_error($term) ) {
        wp_send_json_success( array( 'term_id' => $term->term_id ) );
    } else {
        wp_send_json_success( array( 'term_id' => '' ) );
    }
    wp_die();
}
add_action('wp_ajax_get_term_id_by_name', 'get_term_id_by_name_callback');
add_action('wp_ajax_nopriv_get_term_id_by_name', 'get_term_id_by_name_callback');

/**
 * Map
 */
function dirata_ajax_filter_apartments_by_bounds() {
    if (!isset($_POST['apartment_ids']) || empty($_POST['apartment_ids'])) {
        echo '<p>' . __('No apartments found.', 'dirata') . '</p>';
        wp_die();
    }

    $apartment_ids = array_map('intval', $_POST['apartment_ids']);

    // Build your query args.
    $args = array(
        'post_type'      => 'apartment',
        'posts_per_page' => 10,
        'post__in'       => $apartment_ids,
    );

    // If a city filter is provided via AJAX, add it to the query.
    if (isset($_POST['city']) && intval($_POST['city']) > 0) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'city',
                'field'    => 'term_id',
                'terms'    => intval($_POST['city']),
            )
        );
    }

    echo dirata_list_apartments($args);
    wp_die();
}
add_action('wp_ajax_dirata_ajax_filter_apartments_by_bounds', 'dirata_ajax_filter_apartments_by_bounds');
add_action('wp_ajax_nopriv_dirata_ajax_filter_apartments_by_bounds', 'dirata_ajax_filter_apartments_by_bounds');

function dirata_get_apartments_for_map($args = []) {
    $default_args = array(
        'post_type'      => 'apartment',
        'posts_per_page' => -1, // Get all apartments
    );

    $query_args = wp_parse_args($args, $default_args);
    $query = new WP_Query($query_args);

    $apartments = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $longitude = get_post_meta(get_the_ID(), 'longitude', true);
            $latitude = get_post_meta(get_the_ID(), 'latitude', true);
            $price = get_post_meta(get_the_ID(), 'price', true);
            $title = get_the_title();
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

            if (!empty($longitude) && !empty($latitude)) {
                $apartments[] = array(
                    'id' => get_the_ID(),
                    'title' => $title,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'price' => $price,
                    'thumbnail' => $thumbnail ? $thumbnail : get_template_directory_uri() . '/assets/img/default-apartment.svg'
                );
            }
        }
        wp_reset_postdata();
    }

    return $apartments;
}
function dirata_ajax_get_apartments_for_map() {
    $args = array();
    $apartments = dirata_get_apartments_for_map($args); // Call our reusable function
    wp_send_json($apartments);
}
add_action('wp_ajax_dirata_get_apartments_for_map', 'dirata_ajax_get_apartments_for_map');
add_action('wp_ajax_nopriv_dirata_get_apartments_for_map', 'dirata_ajax_get_apartments_for_map');

function dirata_ajax_get_filtered_apartments_for_map() {
    $args = array(
        'post_type'      => 'apartment',
        'posts_per_page' => -1, // Get all matching apartments
        'meta_query'     => array('relation' => 'AND'),
        'tax_query'      => array(),
    );

    // Filtering by City
    if (!empty($_POST['city']) && intval($_POST['city']) > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'city',
            'field'    => 'term_id',
            'terms'    => intval($_POST['city']),
        );
    }

    // Filtering by Area
    if (!empty($_POST['area']) && intval($_POST['area']) > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'area',
            'field'    => 'term_id',
            'terms'    => intval($_POST['area']),
        );
    }

    // Filtering by Listing Type (rent or sale)
    if (!empty($_POST['listing_type'])) {
        $args['meta_query'][] = array(
            'key'     => 'listing_type',
            'value'   => sanitize_text_field($_POST['listing_type']),
            'compare' => '=',
        );
    }

    // Filtering by Price
    if (!empty($_POST['price_min']) || !empty($_POST['price_max'])) {
        $args['meta_query'][] = array(
            'key'     => 'price',
            'value'   => array( intval($_POST['price_min']), intval($_POST['price_max']) ),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    // Filtering by Rooms
    if (!empty($_POST['rooms']) && is_array($_POST['rooms'])) {
        $room_filters = array('relation' => 'OR');
        foreach ($_POST['rooms'] as $room) {
            $room_filters[] = array(
                'key'     => 'rooms',
                'value'   => floatval($room),
                'compare' => '=',
                'type'    => 'NUMERIC',
            );
        }
        $args['meta_query'][] = $room_filters;
    }

    // Filtering by Floor (min and/or max)
    if (!empty($_POST['floor_min']) || !empty($_POST['floor_max'])) {
        $floor_min = !empty($_POST['floor_min']) ? intval($_POST['floor_min']) : -2;
        $floor_max = !empty($_POST['floor_max']) ? intval($_POST['floor_max']) : 2000;
        $args['meta_query'][] = array(
            'key'     => 'floor',
            'value'   => array($floor_min, $floor_max),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }

    // Filtering by Size (מ"ר)
    if (!empty($_POST['size_min']) || !empty($_POST['size_max'])) {
        // Remove potential " מ״ר" suffix before converting to integer.
        $size_min = !empty($_POST['size_min']) ? intval(str_replace(" מ״ר", "", $_POST['size_min'])) : 20;
        $size_max = !empty($_POST['size_max']) ? intval(str_replace(" מ״ר", "", $_POST['size_max'])) : 9999;
        $args['meta_query'][] = array(
            'key'     => 'size',
            'value'   => array($size_min, $size_max),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }

    // If no filters are set, remove empty query parts.
    if (empty($args['tax_query'])) {
        unset($args['tax_query']);
    }
    if (empty($args['meta_query']) || $args['meta_query'] === array('relation' => 'AND')) {
        unset($args['meta_query']);
    }

    $apartments = dirata_get_apartments_for_map($args);
    wp_send_json($apartments);
    wp_die();
}
add_action('wp_ajax_dirata_ajax_get_filtered_apartments_for_map', 'dirata_ajax_get_filtered_apartments_for_map');
add_action('wp_ajax_nopriv_dirata_ajax_get_filtered_apartments_for_map', 'dirata_ajax_get_filtered_apartments_for_map');

// Personal Area Page
function delete_user_area_callback() {
    if (!isset($_POST['user_id']) || !isset($_POST['area_id'])) {
        wp_send_json_error(['message' => 'Invalid request.']);
        return;
    }

    $user_id = intval($_POST['user_id']);
    $area_id = intval($_POST['area_id']);

    $saved_areas = get_field('saved_areas', 'user_' . $user_id);

    if ($saved_areas && is_array($saved_areas)) {
        $saved_areas = array_map(function($area) {
            return is_object($area) ? $area->term_id : intval($area);
        }, $saved_areas);
    }

    if ($saved_areas && is_array($saved_areas)) {
        $new_areas = array_filter($saved_areas, function($id) use ($area_id) {
            return $id != $area_id;
        });

        update_field('saved_areas', array_values($new_areas), 'user_' . $user_id);
    }

    wp_send_json_success(['message' => 'Area removed successfully']);
}
add_action('wp_ajax_delete_user_area', 'delete_user_area_callback');

function get_areas_by_city_callback() {
    if (!isset($_GET['city'])) {
      wp_send_json_error(['message' => 'No city provided']);
    }
    $city_id = intval($_GET['city']);
    $areas = get_terms(array(
      'taxonomy'   => 'area',
      'hide_empty' => false,
    ));
    $result = [];
    if (!empty($areas) && !is_wp_error($areas)) {
      foreach ($areas as $area) {
        $assoc = get_term_meta($area->term_id, 'associated_city', true);
        if ($assoc == $city_id) {
          $result[] = array(
            'id'   => $area->term_id,
            'name' => $area->name,
          );
        }
      }
    }
    wp_send_json_success($result);
}
add_action('wp_ajax_get_areas_by_city', 'get_areas_by_city_callback');
add_action('wp_ajax_nopriv_get_areas_by_city', 'get_areas_by_city_callback');

function add_user_area_callback() {
    if (!isset($_POST['user_id']) || !isset($_POST['area_id'])) {
      wp_send_json_error(['message' => 'Invalid request']);
      return;
    }
    $user_id = intval($_POST['user_id']);
    $area_id = intval($_POST['area_id']);
  
    $saved_areas = get_field('saved_areas', 'user_' . $user_id);
    if ($saved_areas && is_array($saved_areas)) {
      $saved_areas = array_map(function($area) {
        return is_object($area) ? $area->term_id : intval($area);
      }, $saved_areas);
    } else {
      $saved_areas = [];
    }
  
    if (!in_array($area_id, $saved_areas)) {
      $saved_areas[] = $area_id;
      update_field('saved_areas', $saved_areas, 'user_' . $user_id);
    }
  
    wp_send_json_success(['message' => 'Area added successfully']);
}
add_action('wp_ajax_add_user_area', 'add_user_area_callback');

function get_user_saved_areas_callback() {
    if (!isset($_POST['user_id'])) {
      wp_send_json_error(['message' => 'Invalid request.']);
      return;
    }
    $user_id = intval($_POST['user_id']);
    $saved_areas = get_field('saved_areas', 'user_' . $user_id);
    if ($saved_areas && is_array($saved_areas)) {
      $saved_areas = array_map(function($area) {
        return is_object($area) ? $area->term_id : intval($area);
      }, $saved_areas);
    }
    ob_start();
    if ($saved_areas) {
      foreach ($saved_areas as $area_id) {
        $area = get_term($area_id, 'area');
        if (!$area || is_wp_error($area)) continue;
        $associated_city_id = get_term_meta($area_id, 'associated_city', true);
        $associated_city = get_term_by('id', $associated_city_id, 'city');
        echo '<div class="update_saved_area">' .
               '<div class="saved_area_name">' .
               ($associated_city ? esc_html($associated_city->name) . ' | ' : '') .
               esc_html($area->name) .
             '</div>' .
             '<div class="saved_area_btns">' .
               '<button class="edit-area-btn" data-area-id="' . esc_attr($area_id) . '"><img src="' . get_template_directory_uri() . '/assets/img/saved_areas_edit_icon.png" alt="עריכה"> עריכה</button>' .
               '<button class="delete-area-btn" data-area-id="' . esc_attr($area_id) . '"><img src="' . get_template_directory_uri() . '/assets/img/saved_areas_delete_icon.png" alt="מחיקה"> מחיקה</button>' .
             '</div>' .
             '</div>';
      }
    } else {
      echo '<p>לא נבחרו אזורים.</p>';
    }
    $html = ob_get_clean();
    wp_send_json_success(['html' => $html]);
}
add_action('wp_ajax_get_user_saved_areas', 'get_user_saved_areas_callback');
add_action('wp_ajax_nopriv_get_user_saved_areas', 'get_user_saved_areas_callback');

function update_notification_preferences_callback() {
    $user_id = intval($_POST['user_id']);
    $methods = json_decode(stripslashes($_POST['methods']), true);
    $frequencies = json_decode(stripslashes($_POST['frequency']), true);

    if (!is_array($methods)) $methods = [];
    if (!is_array($frequencies)) $frequencies = [];

    update_field('notification_method', $methods, 'user_' . $user_id);
    update_field('notification_frequency', $frequencies, 'user_' . $user_id);

    wp_send_json_success(['message' => 'Notification preferences updated.']);
}
add_action('wp_ajax_update_notification_preferences', 'update_notification_preferences_callback');

function update_user_personal_details_callback() {
    $user_id = intval($_POST['user_id']);

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);

    // Update WordPress user fields
    wp_update_user([
        'ID' => $user_id,
        'user_email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
    ]);

    update_field('phone', $phone, 'user_' . $user_id);

    wp_send_json_success(['message' => 'Personal details updated']);
}
add_action('wp_ajax_update_user_personal_details', 'update_user_personal_details_callback');

add_action('wp_ajax_save_apartment_to_user', 'save_apartment_to_user_callback');
function save_apartment_to_user_callback() {
    $user_id = get_current_user_id();
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$user_id) {
        wp_send_json_error('עליך להתחבר כדי לשמור דירה.');
    }
    if (!$post_id) {
        wp_send_json_error('דירה לא תקינה.');
    }    

    // Get current saved apartments
    $saved_apartments = get_field('saved_apartments', 'user_' . $user_id);

    if (!is_array($saved_apartments)) {
        $saved_apartments = [];
    }

    if (!in_array($post_id, $saved_apartments)) {
        $saved_apartments[] = $post_id;
        update_field('saved_apartments', $saved_apartments, 'user_' . $user_id);
        wp_send_json_success('saved');
    } else {
        wp_send_json_error('already_saved');
    }
}


// User logic
// Redirect failed login attempts with a query string like ?login=failed
function dirata_custom_login_failed($username) {
    $referrer = wp_get_referer();

    if (!empty($referrer) && !str_contains($referrer, 'wp-login.php')) {
        wp_redirect(add_query_arg('login', 'failed', $referrer));
        exit;
    }
}
add_action('wp_login_failed', 'dirata_custom_login_failed');

// Redirect if login fields are empty
function dirata_custom_login_empty($user, $username, $password) {
    if (empty($username) || empty($password)) {
        $referrer = wp_get_referer();
        if (!str_contains($referrer, 'wp-login.php')) {
            wp_redirect(add_query_arg('login', 'empty', $referrer));
            exit;
        }
    }
    return $user;
}
add_filter('authenticate', 'dirata_custom_login_empty', 30, 3);


// מכאן קוד של אפרת דיין - טויטו 0556614238


// add_action('admin_post_nopriv_create_apartment_post', 'handle_create_apartment_post');
// add_action('admin_post_create_apartment_post', 'handle_create_apartment_post');
add_action('wp_ajax_create_apartment_post', 'handle_create_apartment_post');
add_action('wp_ajax_nopriv_create_apartment_post', 'handle_create_apartment_post');

function handle_create_apartment_post() {
    if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
        wp_die('Not an AJAX request');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Invalid request');
    }
    $user_id = get_current_user_id();

    // קלטים מהטופס
    $city       = sanitize_text_field($_POST['city']);
    $term = get_term( $city );
    if ( ! is_wp_error( $term ) ) {
        $city_name = $term->name;
    }
    $area       = sanitize_text_field($_POST['area']);
    $street      = sanitize_text_field($_POST['street']);
    $house_num      = sanitize_text_field($_POST['house_num']);
    $floor       = sanitize_text_field($_POST['floor']);
    $rooms       = sanitize_text_field($_POST['num_of_rooms']);
    $size        = sanitize_text_field($_POST['apartment_size_in_meters']);
    $price       = sanitize_text_field($_POST['price']);
    $entrance_date = sanitize_text_field($_POST['entrance_date']);
    $entrance_date_formatted = date("d/m/Y", strtotime($entrance_date));
    $about_the_apartment = sanitize_textarea_field($_POST['about_the_apartment']);
    $options     = sanitize_text_field($_POST['other_options']);
    $main_img    = sanitize_text_field($_POST['main_img']);
    $img_gallery = sanitize_text_field($_POST['img_gallery']);
    $is_vat = $_POST['is_vat'];
    $brokerage_fees = $_POST['brokerage_fees'];
    $images = "כאן יישמרו התמונות";
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // גרסה מקוצרת - בבדיקה
    $whats_inside = isset($_POST['whats_inside']) ? $_POST['whats_inside'] : [];
    
    
    $this_user_posts_till_now = get_field('num_of_posts', 'user_' . $user_id, true);
    $this_user_allowed_posts = get_field('max_posts', 'user_' . $user_id, true);
    $this_user_posts_till_now++;

    // יצירת הפוסט של דירה כפורסם!
    $post_id = wp_insert_post([
        'post_title'   => $street . ', ' . $city_name,
        'post_content' => $about_the_apartment,
        'post_type'    => 'apartment', 
        'post_status'  => 'publish',
        'author' => $user_id,
    ]);

    if (is_wp_error($post_id)) {
        wp_die('Error creating post');
    }

    
    // $this_user_allowed_posts--;
    // שמירת השדות למטה ACF
    // הגדרה שזו דירה למכירה
    update_field('listing_type', 'sale', $post_id);
    update_field('price', $price, $post_id);
    update_field('rooms', $rooms, $post_id);
    update_field('floor', $floor, $post_id);
    update_field('size', $size, $post_id);
    update_field('על_הדירה', $about_the_apartment, $post_id);
    update_field('whats_inside', $whats_inside, $post_id);
    update_field('options', $options, $post_id); // אם השדה הזה קיים
    // update_field('apartment_city', $city_name, $post_id);
    wp_set_object_terms($post_id, (int)$city, 'city');
    // update_field('apartment_neighborhood', $area, $post_id);
    wp_set_object_terms($post_id, (int)$area, 'area');
    update_field('apartment_house_num', $house_num, $post_id);
    update_field('apartment_street', $street, $post_id);   
    update_field('building_options', $options , $post_id);
    update_field('entry_date', $entrance_date_formatted, $post_id);
    update_field('brokerage_fees', $brokerage_fees, $post_id);
    update_field('is_vat', $is_vat, $post_id);
    update_field('latitude', $latitude , $post_id);
    update_field('longitude', $longitude , $post_id);
    
    // update_field('main_img', $main_img, $post_id);
    // עדכון גלריה
    // $gallery_array = array_map('trim', explode(',', $img_gallery)); 
    // update_field('img_gallery', $gallery_array, $post_id);
    update_user_meta($user_id, 'num_of_posts', $this_user_posts_till_now);
    // update_user_meta($user_id, 'max_posts', $this_user_allowed_posts);

    // בעתיד תוסיפי גם: type, stage, sale_type, listing_type, וכו' לפי מה שתוסיפי לטופס
    


    $main_id = upload_base64_image($main_img, $post_id);
    if ($main_id) {
        update_field('main_img', $main_id, $post_id); // שדה מסוג "תמונה"
    }

    // $gallery_array = array_map('trim', explode(',', $img_gallery));
    $gallery_array = json_decode(stripslashes($img_gallery), true);

    $gallery_ids = [];

    foreach ($gallery_array as $img) {
        $id = upload_base64_image($img, $post_id);
        if ($id) {
            $gallery_ids[] = $id;
        }
    }

    update_field('img_gallery', $gallery_ids, $post_id); // שדה מסוג "גלריה"


    // הפניה לדף הדירה נוספה בהצלחה
    // wp_redirect(home_url('/published-successfully/'));
    wp_send_json_success([
        'post_id' => $post_id,
        'post_url' => get_permalink($post_id),
        'my_apartments_url' => home_url('/profile-page/'),
        'message' => 'הדירה נוספה בהצלחה!'
    ]);
    
    exit;
}


// פונקציה להעלאת תמונות - המרה מbase64
function upload_base64_image($base64_string, $post_id) {
    // נפריד בין התוכן לקידוד
    if (preg_match('/^data:image\/(\w+);base64,/', $base64_string, $type)) {
        $data = substr($base64_string, strpos($base64_string, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        $data = base64_decode($data);

        if ($data === false) {
            return null;
        }
    } else {
        return null;
    }

    $upload_dir = wp_upload_dir();
    $filename = 'apartment_image_'.uniqid() . '.' . $type;
    $file_path = $upload_dir['path'] . '/' . $filename;

    // שמירת הקובץ
    file_put_contents($file_path, $data);

    // יצירת קובץ מדיה
    $file_type = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $file_type['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

// הפניית מתווך לאזור שלו
add_action('template_redirect', function() {
    if (is_page('your-profile')) { // תואם לעמוד "הפרופיל האישי" שלך
        $user = wp_get_current_user();
        if (in_array('broker', (array) $user->roles)) {
            wp_redirect(home_url('/profile-page/'));
            exit;
        }
    }
});
// פונקצייה בקשר לעימוד הלידים באזור האישי של מתווך
add_action('wp_ajax_load_leads', 'load_leads_callback');
add_action('wp_ajax_nopriv_load_leads', 'load_leads_callback');

function load_leads_callback() {
    $per_page = 40;
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $offset = ($page - 1) * $per_page;
    $current_user_id = get_current_user_id();

    $args_leads = array(
        'post_type'      => 'lead',
        'posts_per_page' => $per_page,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'offset'         => $offset,
        'meta_query'     => array(
            array(
                'key'     => 'this_leads_owner',
                'value'   => $current_user_id,
                'compare' => '='
            ),
        ),
    );

    $leads = get_posts($args_leads);

    ob_start(); // נוסיף לכידת פלט

    if ($leads) {
        foreach ($leads as $lead) {
            setup_postdata($lead);
            $this_lead_id = $lead->ID;
            $lead_phone = get_post_meta($this_lead_id, 'leader_phone', true) ?: 'לא צוין טלפון';
            // $lead_city = get_post_meta($this_lead_id, 'leader_city', true) ?: 'לא צוינה עיר';
            // $lead_neighborhood = get_post_meta($this_lead_id, 'leader_neighberhood', true) ?: 'לא צוינה שכונה';
            // $lead_house_num = get_post_meta($this_lead_id, 'leader_house_num', true) ?: 'לא צויין מספר בית';
            $related_apartment_id = get_post_meta($this_lead_id, 'this_lead_apartment', true) ?: 0;
            $related_apartment_title = $related_apartment_id ? get_the_title($related_apartment_id) : 'לא צוינה דירה';
            $events_timeline = get_field('events_timeline', $this_lead_id) ?: [];

            $current_apartment_city = get_field('apartment_city', $related_apartment_id) ?: 'לא צוינה';
            $current_apartment_neighborhood = get_field('apartment_neighborhood', $related_apartment_id) ?: 'לא צוינה שכונה';
            $current_apartment_house_num = get_field('apartment_house_num', $related_apartment_id) ?: 'לא צויין מספר בית';

            ?>
                <div class="card <?php echo $activeToggle; ?> mt_13" data-apartment-id="<?php echo esc_attr($related_apartment_id); ?>">
                <div class="card-header" id="heading-<?php echo $this_lead_id; ?>">
                    <h2 data-toggle="collapse" data-target="#collapse-<?php echo $this_lead_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $this_lead_id; ?>">
                        <div class="custom_justify d-flex align-items-center justify-content-between flex-wrap">
                            <p><?php echo get_the_title($this_lead_id); ?></p>
                            <p><?php echo $lead_phone; ?></p>
                            <p><?php echo $current_apartment_city; ?></p>
                            <p><?php echo $current_apartment_neighborhood; ?></p>
                            <p><?php echo $current_apartment_house_num; ?></p>
                            <p class="active"><?php echo $related_apartment_title; ?></p>
                            
                        </div>
                    </h2>
                    <img class="arrow_icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon026.svg" alt="icon026">
                </div>

                <div id="collapse-<?php echo $this_lead_id; ?>" class="collapse" aria-labelledby="heading-<?php echo $this_lead_id; ?>">
                    <div class="card-body">
                        <?php foreach($events_timeline as $event) { 
                            $this_event_title = $event['event_title'] ?? 'לא צוין כותרת';
                            $this_event_time = $event['event_time'] ?? 'לא צוין זמן';
                            $this_event_details = $event['event_details'] ?? 'לא צוין פרטים';
                        ?>
                        <div class="default_item mt_10">
                            <div class="my_lead_card_sub">
                                <p class="font400"><?php echo $this_event_title; ?></p>
                                <span class="mt_8"><?php echo $this_event_time; ?></span>
                            </div>
                            <div class="my_lead_bottom_para">
                                <p><?php echo $this_event_details; ?> </p>
                            </div>
                        </div>
                        <?php } ?>
                        <a class="mini_btn mt_13 add_update" href="#">הוסף עדכון נוסף ←</a>                       
                        <div class="add-update-section">
                             <form data-lead-id="<?php echo $this_lead_id; ?>">
                                <div class="row custom_row3 mt_30">
                                    <div class="col-sm-4 custom_col3">
                                        <div>
                                            <label for="update_date">תאריך העדכון:</label>
                                            <input class="input_info mt_13" id="update_date" name="update_date" type="datetime-local" placeholder="" value="">
                                        </div> 
                                    </div>                                                               
                                    <div class="col-sm-8 custom_col3">
                                        <div>
                                            <label for="update_title">כותרת העדכון:</label>
                                            <input class="input_info mt_13" id="update_title" name="update_title" type="text" placeholder="" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row custom_row3 mt_30">
                                    <div class="col-sm-12 custom_col3">
                                        <div>
                                            <label for="update_content">תוכן העדכון:</label>
                                            <input class="input_info mt_13" id="update_content" name="update_content" type="text" placeholder="" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row custom_row3 mt_30">
                                    <div class="col-sm-12 custom_col3">
                                        <div>
                                            <label for="update_file">קובץ מצורף:</label>
                                            <input class="input_info mt_13" id="update_file" name="update_file" type="file" placeholder="" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mt_30">
                                    <div class="update-msg-box"></div>
                                    <input type="button" class="custom_btn" id="save_update" value="שמירת עדכון" onclick="saveUpdate(this)"/>
                                </div>                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p>אין לידים להצגה.</p>';
    }

    $html = ob_get_clean(); // לוכדים את הפלט

    // מחשבים כמה לידים יש סך הכל
    $total_leads = new WP_Query(array(
        'post_type' => 'lead',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key'     => 'this_leads_owner',
                'value'   => $current_user_id,
                'compare' => '='
            ),
        ),
    ));
    $total_leads_count = $total_leads->found_posts;
    wp_reset_postdata();

    wp_send_json(array(
        'html' => $html,
        'total' => $total_leads_count,
        'page' => $page,
        'per_page' => $per_page,
    ));
}

// קליטת ליד חדש מעמוד דירה
// add_action('admin_post_nopriv_save_apartment_lead', 'save_apartment_lead');
// add_action('admin_post_save_apartment_lead', 'save_apartment_lead');

// function save_apartment_lead() {
//     if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['apartment_id'])) {
//         wp_die('Missing required fields.');
//     }

//     $apartment_id = intval($_POST['apartment_id']);
//     $apartment_author_id = get_post_field('post_author', $apartment_id);
//     $current_user_name = sanitize_text_field($_POST['name']);
//     $current_apartment_city = get_field('city', $apartment_id) ?: 'לא צוינה עיר לדירה זו';
//     $current_apartment_neighborhood = get_field('area', $apartment_id) ?: 'לא צוינה שכונה לדירה זו';
//     $current_apartment_house_num = get_field('house_num', $apartment_id) ?: 'לא צויין מספר בית לדירה זו';
//     // צור פוסט חדש מסוג 'lead'
//     $lead_id = wp_insert_post([
//         'post_type'   => 'lead',
//         'post_title'  => $current_user_name,
//         'post_status' => 'publish',
//     ]);

//     if ($lead_id && !is_wp_error($lead_id)) {
//         // update_field('name', sanitize_text_field($_POST['name']), $lead_id);
//         update_field('leader_phone', sanitize_text_field($_POST['phone']), $lead_id);
//         update_field('this_leads_owner', $apartment_author_id, $lead_id); 
//         update_field('this_lead_apartment', $apartment_id, $lead_id);
//         // מעדכן למתווך שיש לידים שלא צפה בהם - עד שיראה את הפופאפ
//         update_field('is_non_watched_lead', true, 'user_'. $apartment_author_id); 
//     }

//     wp_redirect(get_permalink($apartment_id) . '?success=1');
//     exit;
// }

// מכאן פונקציה חדשה לשמירת ליד
// תמיכה ב־AJAX עבור משתמשים מחוברים ולא מחוברים
add_action('wp_ajax_nopriv_save_apartment_lead', 'save_apartment_lead_ajax');
add_action('wp_ajax_save_apartment_lead', 'save_apartment_lead_ajax');

function save_apartment_lead_ajax() {
    if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['apartment_id'])) {
        wp_send_json_error('נא למלא את כל השדות');
    }

    $apartment_id = intval($_POST['apartment_id']);
    $apartment_author_id = get_post_field('post_author', $apartment_id);
    $current_user_name = sanitize_text_field($_POST['name']);
    $current_apartment_city = get_field('city', $apartment_id) ?: 'לא צוינה עיר לדירה זו';
    $current_apartment_neighborhood = get_field('area', $apartment_id) ?: 'לא צוינה שכונה לדירה זו';
    $current_apartment_house_num = get_field('house_num', $apartment_id) ?: 'לא צויין מספר בית לדירה זו';

    $lead_id = wp_insert_post([
        'post_type'   => 'lead',
        'post_title'  => $current_user_name,
        'post_status' => 'publish',
    ]);

    if ($lead_id && !is_wp_error($lead_id)) {
        update_field('leader_phone', sanitize_text_field($_POST['phone']), $lead_id);
        update_field('this_leads_owner', $apartment_author_id, $lead_id); 
        update_field('this_lead_apartment', $apartment_id, $lead_id);
        update_field('is_non_watched_lead', true, 'user_' . $apartment_author_id);

        wp_send_json_success('הטופס נשלח בהצלחה! המשרד יצור אתכם קשר בהקדם.');
    } else {
        wp_send_json_error('אירעה שגיאה בשמירת הליד');
    }
}



add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'leads-filter',
        get_template_directory_uri() . '/assets/js/leads-filter.js',
        array('jquery'),
        '1.0.0',
        true
    );

    // משתנה גלובלי שמאפשר להשתמש ב־ajaxurl בתוך leads-filter.js
    wp_localize_script('leads-filter', 'dirataVars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'currentUserId' => get_current_user_id(),
    ));
    
});



// הקוד שמכניס את הפרטים הנכונים לפופאפ עריכת דירה
add_action('wp_ajax_get_apartment_data', function () {
    $post_id = intval($_POST['apartment_id']);
    $main_img = get_field('main_img', $post_id); // זה כבר מערך
    $gallery_imgs = get_field('img_gallery', $post_id); // זה מחזיר מערך של IDs
    $gallery_urls = [];
    
    if (is_array($gallery_imgs)) {
        foreach ($gallery_imgs as $img_id) {
            $url = wp_get_attachment_url($img_id);
            if ($url) {
                $gallery_urls[] = $url;
            }
        }
    }
        $main_img_url = isset($main_img['url']) ? $main_img['url'] : '';

    if (get_post_type($post_id) !== 'apartment') {
        wp_send_json_error('Invalid post type');
    }

    $data = [
        'apartment_city'         => get_field('apartment_city', $post_id),
        'apartment_neighborhood'         => get_field('apartment_neighborhood', $post_id),
        'apartment_street'       => get_field('apartment_street', $post_id),
        'apartment_house_num' => get_field('apartment_house_num', $post_id),
        'floor'        => get_field('floor', $post_id),
        'rooms'        => get_field('rooms', $post_id),
        'size'         => get_field('size', $post_id),
        'price'        => get_field('price', $post_id),
        'entry_date'   => get_field('entry_date', $post_id),
        'description'  => get_field('על_הדירה', $post_id),
        'options'      => get_field('building_options', $post_id),
        'main_img_url' => $main_img_url,
        'gallery_imgs' => $gallery_urls,

    ];

    wp_send_json_success($data);
});

// פונקציה לעדכון ושמירת נתונים של דירה
// מכינה את הנתונים של הדירה של המשתמש לשמירה בפוסט של הדירה
add_action('wp_ajax_save_apartment_data', function () {
    $post_id = intval($_POST['post_id']);
    if(!$post_id || $post_id === 0 || $post_id === ''){
        wp_send_json_error('i didnt get any post id');
    }
    if(get_post_type($post_id) !== 'apartment'){
        wp_send_json_error('Invalid post type');
    }

    update_field('apartment_city', sanitize_text_field($_POST['city']), $post_id);
    update_field('apartment_neighborhood', sanitize_text_field($_POST['area']), $post_id);
    update_field('apartment_street', sanitize_text_field($_POST['street']), $post_id);
    update_field('apartment_house_num', sanitize_text_field($_POST['house_number']), $post_id);
    update_field('floor', sanitize_text_field($_POST['floor']), $post_id);
    update_field('rooms', sanitize_text_field($_POST['rooms']), $post_id);
    update_field('size', sanitize_text_field($_POST['size']), $post_id);
    update_field('price', sanitize_text_field($_POST['price']), $post_id);
    update_field('entry_date', sanitize_text_field($_POST['entry_date']), $post_id);
    update_field('על_הדירה', sanitize_textarea_field($_POST['description']), $post_id);
    update_field('building_options', sanitize_text_field($_POST['options']), $post_id);
    // עדכון כותרת הפוסט לפי עיר, שכונה רחוב ומספר בית חדשים
    $post_title = $_POST['street'] . ' ' . $_POST['house_number'] . ', ' . $_POST['city'];

    wp_update_post([
        'ID' => $post_id,
        'post_title' => sanitize_text_field($post_title),
    ]);


    wp_send_json_success();
});

// הגדרת טמפלט למאמר בודד
add_filter('single_template', function ($template) {
    if (is_singular('post')) {
        $new_template = locate_template('single-article.php');
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    return $template;
});

// מעביר את המידע על כמות פוסטים שכבר העלה כדי להשוות לכמה מותר להעלות ולהציג הודעה
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('main-js', 'dirataLimits', [
        'max_posts' => get_field('max_posts', 'user_' . get_current_user_id()),
        'current_posts' => get_field('num_of_posts', 'user_' . get_current_user_id()),
    ]);
});



add_action('wp_ajax_load_edit_apartment_popup', 'load_edit_apartment_popup');
function load_edit_apartment_popup() {
    $apartment_id = intval($_POST['apartment_id'] ?? 0);

    if (!$apartment_id) {
        wp_send_json_error('Missing apartment ID');
    }

    // טוען את תוכן הפופאפ מהקובץ שלך
    ob_start();
    include get_template_directory() . '/popup-edit-apartment.php';
    $html = ob_get_clean();

    echo $html;
    wp_die(); // חובה לסיים AJAX
}



// סימון שהמתווך ראה את ההודעה שיש לידים חדשים
add_action('wp_ajax_mark_user_saw_lead', 'mark_user_saw_lead_callback');
function mark_user_saw_lead_callback() {
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => 'משתמש לא מחובר']);
    }

    update_user_meta($user_id, 'is_non_watched_lead', false);
    wp_send_json_success(['message' => 'עודכן בהצלחה']);
}

// קליטת טופס עריכת פרטי מתווך באזור אישי של מתווך
add_action('admin_post_save_broker_profile', 'save_broker_profile');
add_action('admin_post_nopriv_save_broker_profile', 'save_broker_profile'); // רק אם צריך גם לא מחוברים

function save_broker_profile() {
    if (!is_user_logged_in()) {
        wp_die('יש להתחבר כדי לערוך את הפרטים.');
    }

    $user_id = intval($_POST['user_id']);

    // עדכון אימייל (user_email) – אם נשלח
    if (isset($_POST['c']) && is_email($_POST['c'])) {
        wp_update_user([
            'ID' => $user_id,
            'user_email' => sanitize_email($_POST['c']),
        ]);
    }

    // עדכון שם מלא – פיצול לשם פרטי + שם משפחה
    if (isset($_POST['a'])) {
        $full_name = trim(sanitize_text_field($_POST['a']));
        $name_parts = explode(' ', $full_name, 2); // מחלק לשם פרטי ומשפחה
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
    }

    // שם המשרד
    if (isset($_POST['b'])) {
        update_user_meta($user_id, 'office_name', sanitize_text_field($_POST['b']));
    }

    // טלפון
    if (isset($_POST['d'])) {
        update_user_meta($user_id, 'phone', sanitize_text_field($_POST['d']));
    }

    // ווצאפ
    if (isset($_POST['e'])) {
        update_user_meta($user_id, 'whatsapp', sanitize_text_field($_POST['e']));
    }

    // עיר עיקרית
    if (isset($_POST['g'])) {
        update_user_meta($user_id, 'main_city_of_activity', intval($_POST['g']));
    }

    // לוגו משרד (קובץ)
    if (!empty($_FILES['ImageMedias']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded = media_handle_upload('ImageMedias', 0);

        if (!is_wp_error($uploaded)) {
            update_user_meta($user_id, 'office_logo', $uploaded);
        }
    }
    // אם התבקש למחוק את הלוגו
    // if (isset($_POST['remove_office_logo']) && $_POST['remove_office_logo'] === '1') {
    //     delete_user_meta($user_id, 'office_logo');
    // }

    // הפניה חזרה עם פרמטר הודעה
    wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
    exit;
}

// קליטת הטופס של יצירת קשר לשדרוג מנוי מתווך
add_action('wp_ajax_send_upgrade_contact_form', 'handle_upgrade_contact_form');
add_action('wp_ajax_nopriv_send_upgrade_contact_form', 'handle_upgrade_contact_form');

function handle_upgrade_contact_form() {
    $name = sanitize_text_field($_POST['name']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);

    // שלח מייל
    // $to = get_option('admin_email');
    $to = "efrat2115500@gmail.com";
    $subject = 'פנייה לשדרוג מנוי מתווך';
    $body = "שם: $name\nטלפון: $phone\n\nהודעה:\n$message";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    wp_mail($to, $subject, $body, $headers);

    echo 'success';
    wp_die();
}

//קליטת עדכון על ליד
add_action('wp_ajax_save_update_to_lead', 'save_update_to_lead_functions');
add_action('wp_ajax_nopriv_save_update_to_lead', 'save_update_to_lead_functions');
function save_update_to_lead_functions(){
    $lead_id = intval($_POST['lead_id']);
    $update_date = sanitize_text_field($_POST['update_date']);
    $update_title = sanitize_text_field($_POST['update_title']);
    $update_content = sanitize_text_field($_POST['update_content']);

    if (!$lead_id || empty($update_title)) {
        wp_send_json_error(['message' => 'Missing required fields']);
    }

    $lead_post = get_post($lead_id);
    if(!$lead_post){
        wp_send_json_error(['message' => 'Lead not found']);
    }

    //handle file upload
    $attachment_id = '';
    if(!empty($_FILES['update_file']['name'])){
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $file = $_FILES['update_file'];
        $upload = wp_handle_upload($file, ['test_form' => false]);

        if(!isset($upload['error']) && isset($upload['file'])) {
            $filetype = wp_check_filetype($upload['file']);
            $attachment = [
                'post_mime_type' => $filetype['type'],
                'post_title'     => sanitize_file_name($file['name']),
                'post_content'   => '',
                'post_status'    => 'inherit'
            ];
            $attachment_id = wp_insert_attachment($attachment, $upload['file']);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            wp_generate_attachment_metadata($attachment_id, $upload['file']);
        } else {
            wp_send_json_error(['message' => 'File upload failed: ' . $upload['error']]);
        }
    }

    if(have_rows('events_timeline', $lead_id)){
        $row_count = count(get_field('events_timeline', $lead_id));
    } else {
        $row_count = 0;
    }

    $new_row = [
        'event_time'      => $update_date,
        'event_title'     => $update_title,
        'event_details'   => $update_content,
        'event_attachment'=> $attachment_id ? $attachment_id : ''
    ];

    $add = add_row('events_timeline', $new_row, $lead_id);

    if ($add) {
        wp_send_json_success(['message' => 'Event added successfully']);
    } else {
        wp_send_json_error(['message' => 'Failed to add event']);
    }

}

// יצירת משתמש מסוג לקוח רגיל
add_action('admin_post_nopriv_register_custom_user', 'register_custom_user');
add_action('admin_post_register_custom_user', 'register_custom_user');

function register_custom_user() {
    if (!isset($_POST['action']) || $_POST['action'] !== 'register_custom_user') {
        return;
    }
    $current_saved_areas = [];
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $phone = sanitize_text_field($_POST['phone']);
    $name = sanitize_text_field($_POST['client_name']);
    $city = sanitize_text_field($_POST['city']);
    $area = sanitize_text_field($_POST['area']);
    $sale_or_rent = sanitize_text_field($_POST['sale_or_rent']);
    $notification_method = isset($_POST['notification_method']) ? $_POST['notification_method'] : [];
    $notification_frequency = isset($_POST['notification_frequency']) ? $_POST['notification_frequency'] : [];
    $is_mailing = $_POST['is_mailing'];
    // בדיקת סיסמא תואמת
    // if ($password !== $confirm_password) {
    //     alert('הסיסמאות אינן תואמות');
    // }

    $name_parts = explode(' ', trim($name));
    $first_name = isset($name_parts[0]) ? $name_parts[0] : '';
    $last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : '';
    // יצירת משתמש חדש
    $user_id = wp_insert_user([
        'user_login'    => 'rc_'. $phone,
        'user_pass'     => $password,
        'user_email'    => $email,
        'role'          => 'subscriber',
        'display_name'  => $name,
        'first_name' => $first_name,
        'last_name'  => $last_name
    ]);



    // if (is_wp_error($user_id)) {
    //     wp_die($user_id->get_error_message());
    // }
    update_field('phone', $phone, 'user_' . $user_id);
    update_field('notification_method', $notification_method, 'user_' . $user_id);
    update_field('notification_frequency', $notification_frequency, 'user_' . $user_id);
    update_field('notification_for_sale_or_rent', $sale_or_rent, 'user_' . $user_id);
    update_field('is_mailing', $is_mailing, 'user_' . $user_id);

    $current_saved_areas[] = $area;
    update_field('saved_areas', $current_saved_areas, 'user_' . $user_id);

    wp_redirect(home_url('/profile-page'));
    exit;
}


// פונקציה שממירה פורמט מחיר למנוע תקלות
if ( ! function_exists('safe_price') ) {
  function safe_price($price) {
    return is_numeric($price) ? number_format_i18n($price) : 'לא צויין מחיר';
  }
}


// //add cron to send daily email
// add_filter('cron_schedules', 'daily_mail_cron_schedules');
// function daily_mail_cron_schedules($schedules){
//     $schedules['every_5_minutes'] = array(
//         'interval' => 300, // 5 minutes
//         'display'  => __('Every 5 Minutes')
//     );
//     return $schedules;
// }

// add_action('wp', 'setup_daily_user_notification_cron');
// function setup_daily_user_notification_cron(){
//     if (!wp_next_scheduled('set_mail_user_notification_type')) {
//         wp_schedule_event(time(), 'every_5_minutes', 'set_mail_user_notification_type');
//         error_log('✅ Cron event scheduled.');
//     }
// }

// add_action('set_mail_user_notification_type', 'handle_user_notifications');
// function handle_user_notifications(){
//     error_log('🔔 Cron job started: checking user notification types');
// }

// add_action('init', function () {
//     $timestamp = wp_next_scheduled('set_mail_user_notification_type');
//     if ($timestamp) {
//         error_log('📆 Cron is scheduled for: ' . date('Y-m-d H:i:s', $timestamp));
//     } else {
//         error_log('🚫 Cron is NOT scheduled.');
//     }
// });

/**
 * קרון לשליחת מיילים מתוזמנים למשתמשים לפי ההעדפות שלהם.
 */

// הוספת תדירויות
add_filter('cron_schedules', 'custom_mail_cron_schedules');
function custom_mail_cron_schedules($schedules) {
    $schedules['every_5_minutes'] = [
        'interval' => 300,
        'display'  => __('Every 5 Minutes')
    ];
    return $schedules;
}
add_filter('wp_mail_from_name', function($name) {
    return 'דירתא.';
});

// תזמון הקרון
add_action('wp', 'setup_custom_user_notification_cron');
function setup_custom_user_notification_cron() {
    if (!wp_next_scheduled('process_user_notifications')) {
        wp_schedule_event(time(), 'every_5_minutes', 'process_user_notifications');
    }
}

    // הפעלת המשימה
add_action('process_user_notifications', 'handle_custom_user_notifications');

function handle_custom_user_notifications() {
    error_log('🚀 Cron job started to check user notifications');

    $users = get_users([
        'meta_query' => [
            [
                'key'     => 'notification_method',
                'value'   => 'email',
                'compare' => 'LIKE'
            ]
        ]
    ]);

    if (empty($users)) {
        error_log('🔕 No users to notify');
        return;
    }

    foreach ($users as $user) {
        $frequency = (array) get_user_meta($user->ID, 'notification_frequency', true);
        $methods = (array) get_user_meta($user->ID, 'notification_method', true);
        $logo_url = "https://dirata.co.il/wp-content/uploads/2025/06/Group-584.png";

        if (!in_array('email', $methods)) {
            continue;
        }

        foreach (['daily', 'every_week'] as $cron_type) {
            if (!in_array($cron_type, $frequency)) {
                continue;
            }

            $last_sent = get_user_meta($user->ID, "last_notification_sent", true);
            $current_user = wp_get_current_user();
            $first_name = $current_user->first_name;
            $now = current_time('timestamp');
            $should_send = false;

            if ($cron_type === 'daily') {
                $should_send = (!$last_sent || date_i18n('Y-m-d', $last_sent) !== date_i18n('Y-m-d', $now));
            } elseif ($cron_type === 'every_week') {
                $should_send = (!$last_sent || strtotime('last sunday', $last_sent) !== strtotime('last sunday', $now));
            }

            if (!$should_send) {
                continue;
            }

            $saved_areas = get_user_meta($user->ID, 'saved_areas', true);
            if (empty($saved_areas) || !is_array($saved_areas)) {
                continue;
            }

            $after_days = ($cron_type === 'daily') ? '1 day ago' : '7 days ago';

            $args = [
                'post_type'      => 'apartment',
                'posts_per_page' => 20,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
                'date_query'     => [
                    [
                        'after' => $after_days,
                        'inclusive' => true,
                    ]
                ],
                'tax_query' => [
                    [
                        'taxonomy' => 'area',
                        'field'    => 'term_id',
                        'terms'    => $saved_areas
                    ]
                ]
            ];

            $query = new WP_Query($args);

            if (!$query->have_posts()) {
                continue;
            }

            $items_html ="";
            $num_of_apartments =0;
            // $message = "היי {$user->display_name},\n\nשמחים לעדכן שעלו דירות חדשות שמתאימות לך:\n\n";

            while ($query->have_posts()) {
                $query->the_post();
            
                // שדות מיוחדים
                $main_img = get_post_meta(get_the_ID(), 'main_img', true);
                $sale_type = get_post_meta(get_the_ID(), 'sale_type', true);
                $price = get_post_meta(get_the_ID(), 'price', true);
            
                // טקסונומיות
                $city_terms = wp_get_post_terms(get_the_ID(), 'city');
                $area_terms = wp_get_post_terms(get_the_ID(), 'area');
            
                $city = (!is_wp_error($city_terms) && !empty($city_terms)) ? $city_terms[0]->name : '';
                $area = (!is_wp_error($area_terms) && !empty($area_terms)) ? $area_terms[0]->name : '';
            
                // טקסטים
                $sale_label = ($sale_type === 'rent') ? 'דירה להשכרה' : 'דירה למכירה';
                $price_label = safe_price($price) . ' ₪';
            
                $apartment_url = get_permalink();
                $profile_url = site_url('/profile-page');
                $header_img = "https://dirata.co.il/wp-content/uploads/2025/06/Group-639.png";
                $num_of_apartments++;
                $items_html .= "
                <tr>
                  <td align='center' style='padding: 20px;'>
                    <img src='{$main_img}' alt='' style='width: 100%; max-width: 560px; border-radius: 10px; display: block;'>
                  </td>
                </tr>
                <tr>
                  <td align='center' style='padding: 10px; font-size: 20px; color: #333;'>{$sale_label}</td>
                </tr>
                <tr>
                  <td align='center' style='padding: 5px; font-size: 24px; font-weight: bold; color: #333;'>" . get_the_title() . "</td>
                </tr>
                <tr>
                  <td align='center' style='padding: 5px; font-size: 18px; color: #555;'>{$price_label}</td>
                </tr>
                <tr>
                  <td align='center' style='padding: 10px 20px; font-size: 16px; color: #003366;'>
                    <table width='100%' cellpadding='0' cellspacing='0'>
                      <tr>
                        <td align='right'>עיר:<br>{$city}</td>
                        <td align='left'>אזור:<br>{$area}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align='center' style='padding: 20px; width: 100%;'>
                    <a href='{$apartment_url}' style='display: inline-block; padding: 15px 30px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 50px;'>
                      מעבר לפרטים נוספים על הדירה
                    </a>
                  </td>
                </tr>
                <tr><td><hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'></td></tr>
                ";
                // $message = "
                // <html dir='rtl'>
                // <head>
                //   <meta charset='UTF-8'>
                // </head>
                // <body style='margin:0; padding:0; background: #f9f9f9;'>
                //   <table align='center' width='600' cellpadding='0' cellspacing='0' style='background: #ffffff; border-radius: 8px; overflow: hidden; font-family: Tahoma, sans-serif; border: #76C092 solid 1px;'>
                //     <!-- Header -->
                //     <tr>
                //       <td align='center'>
                //         <img src='{$header_img}' alt='דירתא' style='width: 100%; max-width: 600px; display: block;'>
                //       </td>
                //     </tr>
                
                //     <!-- Main Image -->
                //     <tr>
                //       <td align='center' style='padding: 20px;'>
                //         <img src='{$main_img}' alt='' style='width: 100%; max-width: 560px; border-radius: 10px; display: block;'>
                //       </td>
                //     </tr>
                
                //     <!-- Sale Label -->
                //     <tr>
                //       <td align='center' style='padding: 10px; font-size: 20px; color: #333;'>
                //         {$sale_label}
                //       </td>
                //     </tr>
                
                //     <!-- Title -->
                //     <tr>
                //       <td align='center' style='padding: 5px; font-size: 24px; font-weight: bold; color: #333;'>
                //         " . get_the_title() . "
                //       </td>
                //     </tr>
                
                //     <!-- Price -->
                //     <tr>
                //       <td align='center' style='padding: 5px; font-size: 18px; color: #555;'>
                //         {$price_label}
                //       </td>
                //     </tr>
                
                //     <!-- City and Area -->
                //     <tr>
                //       <td align='center' style='padding: 10px 20px; font-size: 16px; color: #003366;'>
                //         <table width='100%' cellpadding='0' cellspacing='0'>
                //           <tr>
                //             <td align='right'>
                //               עיר:<br>{$city}
                //             </td>
                //             <td align='left'>
                //               אזור:<br>{$area}
                //             </td>
                //           </tr>
                //         </table>
                //       </td>
                //     </tr>
                
                //     <!-- Button to Apartment -->
                //     <tr>
                //       <td align='center' style='padding: 20px;'>
                //         <a href='{$apartment_url}' style='display: inline-block; padding: 15px 30px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px;'>
                //           מעבר לפרטים נוספים על הדירה
                //         </a>
                //       </td>
                //     </tr>
                
                //     <!-- Button to Profile -->
                //     <tr>
                //       <td align='center' style='padding: 0 20px 20px 20px;'>
                //         <a href='{$profile_url}' style='display: inline-block; padding: 15px 30px; background: #003366; color: #fff; text-decoration: none; border-radius: 5px;'>
                //           מעבר לאזור האישי
                //         </a>
                //       </td>
                //     </tr>
                
                //     <!-- Footer -->
                //     <tr>
                //       <td align='center' style='padding: 30px; font-size: 12px; color: #555; border-top: 1px solid #ddd;'>
                //         מערכת טלפונית דירתא &middot; 073-3452829
                //       </td>
                //     </tr>
                //   </table>
                // </body>
                // </html>
                // ";
            }

            $message = "
            <html dir='rtl'>
            <head>
            <meta charset='UTF-8'>
            </head>
            <body style='margin:0; padding:0; background: #f9f9f9;'>
            <table align='center' width='600' cellpadding='0' cellspacing='0' style='background: #ffffff; border-radius: 8px; overflow: hidden; font-family: Verdana, sans-serif;'>
                <!-- Header -->
                <tr>
                <td align='center'>
                    <img src='{$header_img}' alt='דירתא' style='width: 100%; max-width: 600px; display: block;'>
                </td>
                </tr>

                {$items_html}

                <!-- כפתור לאזור האישי -->
                <tr>
                <td align='center' style='padding: 20px; width: 100%;'>
                    <a href='{$profile_url}' style='display: inline-block; padding: 15px 30px; background: #003366; color: #fff; text-decoration: none; border-radius: 50px;'>
                    מעבר לאזור האישי
                    </a>
                </td>
                </tr>

                <!-- Footer -->
                <tr>
                <td align='center' style='padding: 30px; font-size: 12px; color: #555; border-top: 1px solid #ddd;'>
                    מערכת טלפונית דירתא &middot; 073-3452829
                </td>
                </tr>
            </table>
            </body>
            </html>
            ";
            
            wp_reset_postdata();
            $is_or_are = ($num_of_apartments > 1) ? 'דירות' : 'דירה';
            $is_or_are_2 = ($num_of_apartments > 1) ? 'שמתאימות' : 'שמתאימה';

            // שליחת המייל כ-HTML
            wp_mail(
                $user->user_email,
                'שלום '.$first_name .
                ', מצאנו '.$is_or_are.' '.$is_or_are_2.' לחיפוש שלך',
                $message,
                [
                    'Content-Type: text/html; charset=UTF-8'
                ]
            );
            

            

            update_user_meta($user->ID, "last_notification_sent", $now);

            error_log("📧 [{$cron_type}] Email sent to {$user->user_email}");
        }
    }

    error_log('✅ Cron job finished.');
}

// ממיר את נראות המספרים בערך של שניות לנראות של תאריך ושעה מסודרים
add_filter('acf/load_value/name=last_notification_sent', function($value, $post_id, $field) {
    if (!$value) return '';
    return date_i18n('Y-m-d H:i:s', $value);
}, 10, 3);
// נועל את השדה של עדכון אחרון שנשלח ללקוח כדי למנוע תקלות בכניסה לניהול
add_filter('acf/prepare_field/name=last_notification_sent', function($field) {
    // הפוך לשדה לקריאה בלבד
    $field['readonly'] = true;
    // וגם מנע עריכה מוחלטת
    $field['disabled'] = true;
    return $field;
});


// הצגת כפתור הרץ קרון עכשיו בפאנל הניהול, בשביל לראות פיתוחים
add_action('admin_menu', function() {
    add_menu_page(
        'הרצת קרון מיידית',
        'בדיקת קרון דירות',
        'manage_options',
        'dirata-run-cron',
        'dirata_run_cron_page',
        'dashicons-schedule',
        90
    );
});

function dirata_run_cron_page() {
    // אם לחצו על הכפתור - תריץ מיידית
    if (isset($_POST['run_cron_now'])) {
        do_action('process_user_notifications');
        echo '<div style="padding:10px; background:#46b450; color:#fff;">✅ הקרון רץ בהצלחה!</div>';
    }
    ?>
    <div class="wrap">
        <h1>בדיקת קרון דירות בזמן אמת</h1>
        <form method="post">
            <?php submit_button('הפעל קרון עכשיו 🔄', 'primary', 'run_cron_now'); ?>
        </form>
    </div>
    <?php
}
// יצירת עמודים בלוח הבקרה
if (function_exists('acf_add_options_page')) {

    // עמוד ראשי
    acf_add_options_page(array(
        'page_title' => 'ניהול נגיש לאתר',
        'menu_title'  => 'ניהול נגיש לאתר',
        'menu_slug'   => 'site_content_management',
        'capability'  => 'manage_options',
        'redirect'    => false,
    ));

    // תת-עמוד: ניהול באנרים
    acf_add_options_sub_page(array(
        'page_title'  => 'ניהול באנרים',
        'menu_title'  => 'ניהול באנרים',
        'parent_slug' => 'site_content_management',
        'menu_slug'   => 'banner_settings',
    ));

    // תת-עמוד: ניהול מאמרים
    acf_add_options_sub_page(array(
        'page_title'  => 'ניהול מאמרים באזור רץ',
        'menu_title'  => 'ניהול מאמרים באזור רץ',
        'parent_slug' => 'site_content_management',
        'menu_slug'   => 'articles_settings',
    ));
}

// הוספת שדות ACF לשני העמודים
add_action('acf/init', function () {

    // שדות לבאנרים
    // שדות לבאנרים – תצוגה עם סטים: דסקטופ, לפטופ, מובייל
acf_add_local_field_group(array(
    'key' => 'group_banner_settings',
    'title' => 'הגדרות באנרים',
    'fields' => array(
        array(
            'key' => 'field_banners_repeater',
            'label' => 'באנרים שיוצגו',
            'name' => 'banners',
            'type' => 'repeater',
            'instructions' => 'הוסף לפחות שני באנרים, כל אחד עם 3 גדלים (דסקטופ, לפטופ, מובייל)',
            'min' => 2,
            'layout' => 'row',
            'button_label' => 'הוסף באנר חדש',
            'sub_fields' => array(
                array(
                    'key' => 'field_banner_desktop',
                    'label' => 'באנר לדסקטופ',
                    'name' => 'desktop',
                    'type' => 'image',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_banner_laptop',
                    'label' => 'באנר ללפטופ',
                    'name' => 'laptop',
                    'type' => 'image',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_banner_mobile',
                    'label' => 'באנר למובייל',
                    'name' => 'mobile',
                    'type' => 'image',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'banner_settings',
            ),
        ),
    ),
));


    // שדות למאמרים
    acf_add_local_field_group(array(
        'key' => 'group_articles_settings',
        'title' => 'הגדרות מאמרים באזור רץ',
        'fields' => array(
            array(
                'key' => 'field_selected_articles',
                'label' => 'אילו מאמרים יוצגו?',
                'name' => 'selected_articles',
                'type' => 'post_object',
                'instructions' => 'בחר את המאמרים שיוצגו באזור רץ',
                'required' => 0,
                'post_type' => array('post'),
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'articles_settings',
                ),
            ),
        ),
    ));
});





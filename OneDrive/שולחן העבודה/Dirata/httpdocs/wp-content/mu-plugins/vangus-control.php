<?php
/**
 * Plugin Name: Clear Cache Button
 * Description: Adds a "ניקוי קאש" button to the admin bar that clears all cache via AJAX.
 * Author: Your Name
 */

// Remove Site Health submenu from Tools menu
function remove_site_health_menu() {
    remove_submenu_page('tools.php', 'site-health.php');
}
add_action('admin_menu', 'remove_site_health_menu');

// Remove Site Health widget from dashboard
add_action('wp_dashboard_setup', 'remove_site_health_dashboard_widget');
function remove_site_health_dashboard_widget() {
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
}


// Allow SVG file uploads
add_filter('upload_mimes', 'add_file_types_to_uploads');
function add_file_types_to_uploads($file_types) {
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes);
    return $file_types;
}

function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); // Activity
        remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' ); // WordPress News
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // At a Glance
remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action( 'admin_init', 'remove_dashboard_meta' );

// Change the heartbeat interval
function custom_heartbeat_settings($settings) {
    $settings['interval'] = 200; // Adjust the heartbeat interval in seconds
    return $settings;
}
add_filter('heartbeat_settings', 'custom_heartbeat_settings');

// Hook to add the button to the admin bar
add_action('admin_bar_menu', 'add_clear_cache_button', 100);
function add_clear_cache_button($wp_admin_bar) {
    // Add the button to the admin bar
    $args = array(
        'id'    => 'clear_cache_button',
        'title' => '<span class="ab-icon"></span><strong>ניקוי מטמון</strong>',  // Text for the button
        'href'  => '#', // Empty href to make the button clickable
        'meta'  => array('class' => 'clear-cache-button'), // Meta class for styling and interaction
    );
    $wp_admin_bar->add_node($args);
}

// Enqueue JavaScript and inline CSS
add_action('admin_enqueue_scripts', 'enqueue_clear_cache_assets');
function enqueue_clear_cache_assets() {
    wp_enqueue_script('clear-cache-js', plugin_dir_url(__FILE__) . 'clear-cache.js', array('jquery'), null, true);

    // Localize script to pass AJAX URL and nonce
    wp_localize_script('clear-cache-js', 'clear_cache_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('clear_cache_nonce'),
    ));

    // Inline CSS for the spinner and button styling
    $custom_css = "
        #wp-admin-bar-clear_cache_button a {
            background-color: #0070ff !important;
            color: white !important;
            font-weight: bold !important;
            padding: 0 10px !important; /* Adjusted padding to 0 vertically and 10px horizontally */
            border-radius: 3px !important;
            line-height: 32px !important; /* Ensure the button aligns correctly with other admin bar items */
            height: 32px !important; /* Match the default height of admin bar buttons */
            display: flex;
            align-items: center; /* Vertically center the text and spinner */
        }
        
        #wp-admin-bar-clear_cache_button a:hover {
            background-color: #005bb5 !important;
        }

        .spinner-border {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 8px; /* Add some space between the spinner and text */
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
        }

        @keyframes spinner-border {
            100% {
                transform: rotate(360deg);
            }
        }
    ";
    wp_add_inline_style('admin-bar', $custom_css);
}


// AJAX action to clear cache
add_action('wp_ajax_clear_cache', 'clear_cache_ajax');
function clear_cache_ajax() {
    // Check nonce for security
    check_ajax_referer('clear_cache_nonce', 'nonce');

    // Call the cache clearing functions
    clear_wordpress_cache();
    clear_server_cache();

    // Send success response to AJAX
    wp_send_json_success();
}

// Function to clear WordPress cache by deleting cache files
function clear_wordpress_cache() {
    $cache_folder = WP_CONTENT_DIR . '/cache/';
    if (is_dir($cache_folder)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($cache_folder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $file = $fileinfo->getRealPath();
            if ($fileinfo->isDir()) {
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }
}

// Function to clear server cache and Elementor cache if applicable
function clear_server_cache() {
    // Clear PHP OPcache
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }

    // Clear Elementor cache if Elementor is installed
    if (defined('ELEMENTOR_PATH')) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }
}

<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, 'create_virtual_phone_log_table' );
function create_virtual_phone_log_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'virtual_phone_log';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        action_type varchar(55) DEFAULT '' NOT NULL,
        user_id mediumint(9) NOT NULL,
        phone_number varchar(55) DEFAULT '' NOT NULL,
        notes text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function log_virtual_phone_action( $action_type, $user_id, $phone_number, $notes = '' ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'virtual_phone_log';
    
    $wpdb->insert( 
        $table_name, 
        array( 
            'time' => current_time( 'mysql' ), 
            'action_type' => $action_type, 
            'user_id' => $user_id,
            'phone_number' => $phone_number,
            'notes' => $notes
        ) 
    );
}

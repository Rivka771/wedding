<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'user_register', 'assign_virtual_number_on_registration', 10, 1 );
function assign_virtual_number_on_registration( $user_id ) {
    $pool = get_field('virtual_phone_pool', 'option');

    // Find an available number and assign it.
    foreach ($pool as &$row) {
        if ( empty($row['assigned_user_id']) ) {
            $row['assigned_user_id'] = $user_id;
            $row['assigned_date'] = current_time('mysql');
            update_field('virtual_phone_pool', $pool, 'option');
            log_virtual_phone_action('assigned', $user_id, $row['virtual_phone_number'], 'Assigned on user registration.');
            return;
        }
    }

    // If no numbers are available.
    wp_mail(get_option('admin_email'), 'Virtual Phone Number Pool Depleted', 'A new user was registered, but the virtual phone number pool is empty. Please add more numbers.');
    log_virtual_phone_action('out_of_numbers', $user_id, '', 'Attempted to assign a number on user registration, but none were available.');
}

add_action( 'delete_user', 'unassign_virtual_number_on_delete', 10, 1 );
function unassign_virtual_number_on_delete( $user_id ) {
    $pool = get_field('virtual_phone_pool', 'option');

    // Find the user's number and unassign it.
    foreach ($pool as &$row) {
        if ( $row['assigned_user_id'] == $user_id ) {
            $number_to_unassign = $row['virtual_phone_number'];
            $row['assigned_user_id'] = '';
            $row['assigned_date'] = '';
            update_field('virtual_phone_pool', $pool, 'option');
            log_virtual_phone_action('unassigned', $user_id, $number_to_unassign, 'Unassigned on user deletion.');
            return;
        }
    }
}

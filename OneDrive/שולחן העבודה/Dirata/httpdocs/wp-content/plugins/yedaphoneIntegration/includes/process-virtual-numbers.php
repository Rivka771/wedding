<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action('acf/save_post', 'sync_virtual_numbers', 20);
function sync_virtual_numbers( $post_id ) {
    // Ensure we are on the correct options page.
    if ( ! $post_id == 'options' || ! isset($_POST['acf']['field_virtual_phone_pool']) ) {
        return;
    }
    
    // Get the state of the pool right after ACF has saved it.
    $pool = get_field('virtual_phone_pool', 'option');
    if ( ! is_array($pool) ) {
        $pool = [];
    }
    
    // --- Step 1: Process and add new numbers from the textarea ---
    $new_numbers_raw = sanitize_textarea_field( $_POST['acf']['field_virtual_phone_pool_input'] ?? '' );
    if ( ! empty( $new_numbers_raw ) ) {
        $new_numbers = array_filter( array_map( 'trim', explode( "\n", $new_numbers_raw ) ) );
        $existing_numbers_in_pool = wp_list_pluck( $pool, 'virtual_phone_number' );
        $added_count = 0;
        
        foreach ( $new_numbers as $number ) {
            if ( ! in_array( $number, $existing_numbers_in_pool ) ) {
                $pool[] = [
                    'virtual_phone_number' => $number,
                    'assigned_user_id'     => '',
                    'assigned_date'        => '',
                ];
                $added_count++;
            }
        }
        
        // Save the updated pool and clear the textarea
        update_field('virtual_phone_pool', $pool, 'option');
        update_field('virtual_phone_pool_input', '', 'option');
        
        if ( $added_count > 0 ) {
            acf_add_admin_notice( "Successfully added {$added_count} new virtual numbers.", 'success' );
        }
    }
    
    // --- Step 2: Sync all assignments with user meta ---
    $current_assignments = []; // [user_id => virtual_number]
    foreach ($pool as $row) {
        if ( ! empty($row['assigned_user_id']) ) {
            $user_id = is_array($row['assigned_user_id']) ? $row['assigned_user_id']['ID'] : $row['assigned_user_id'];
            $current_assignments[$user_id] = $row['virtual_phone_number'];
        }
    }
    
    // Find all users who currently have an office phone set
    $users_with_office_phone = get_users([
        'meta_key' => 'office_phone',
        'fields'   => ['ID'],
    ]);
    
    // Check for and remove stale/incorrect assignments
    foreach ($users_with_office_phone as $user) {
        $user_id = $user->ID;
        $current_meta = get_user_meta($user_id, 'office_phone', true);
        $is_stale = !isset($current_assignments[$user_id]) || $current_assignments[$user_id] !== $current_meta;
        
        if ($is_stale) {
            delete_user_meta($user_id, 'office_phone');
            log_virtual_phone_action('synced_unassign', $user_id, $current_meta, 'User meta unassigned during options page sync.');
        }
    }
    
    // Add or update current assignments
    foreach ($current_assignments as $user_id => $number) {
        $old_meta = get_user_meta($user_id, 'office_phone', true);
        if ($old_meta !== $number) {
            update_user_meta($user_id, 'office_phone', $number);
            log_virtual_phone_action('synced_assign', $user_id, $number, 'User meta assigned/updated during options page sync.');
        }
    }
}

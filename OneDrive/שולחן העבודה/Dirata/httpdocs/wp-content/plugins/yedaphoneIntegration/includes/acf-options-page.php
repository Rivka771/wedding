<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' 	=> 'Virtual Phone Numbers',
        'menu_title'	=> 'Virtual Phone Numbers',
        'menu_slug' 	=> 'virtual-phone-numbers',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}

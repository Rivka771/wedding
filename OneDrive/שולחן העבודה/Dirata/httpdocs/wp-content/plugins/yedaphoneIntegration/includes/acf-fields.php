<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_virtual_phone_numbers',
	'title' => 'Virtual Phone Numbers Management',
	'fields' => array(
		array(
			'key' => 'field_virtual_phone_pool_input',
			'label' => 'Virtual Phone Pool Input',
			'name' => 'virtual_phone_pool_input',
			'type' => 'textarea',
			'instructions' => 'Enter new phone numbers, one per line. Existing numbers that are in use will not be affected.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_virtual_phone_pool',
			'label' => 'Virtual Phone Pool',
			'name' => 'virtual_phone_pool',
			'type' => 'repeater',
			'instructions' => 'This is the pool of available virtual phone numbers.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_virtual_phone_number',
					'label' => 'Virtual Phone Number',
					'name' => 'virtual_phone_number',
					'type' => 'text',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'readonly' => 1
				),
				array(
					'key' => 'field_assigned_user_id',
					'label' => 'Assigned User',
					'name' => 'assigned_user_id',
					'type' => 'user',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'role' => '',
					'allow_null' => 1,
					'multiple' => 0,
					'return_format' => 'id',
				),
				array(
					'key' => 'field_assigned_date',
					'label' => 'Assigned Date',
					'name' => 'assigned_date',
					'type' => 'date_time_picker',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'd/m/Y g:i a',
					'return_format' => 'Y-m-d H:i:s',
					'first_day' => 1,
					'readonly' => 1
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'virtual-phone-numbers',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;

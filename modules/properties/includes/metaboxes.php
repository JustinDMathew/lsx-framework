<?php

// helper functions
function lsx_populate_gallery_select(){

	$pages = get_posts( array( 'post_type' => 'page', 'posts_per_page' => -1 ) );

	$options = array();
	foreach( $pages as $page ){
		$options[ $page->ID ] = $page->post_title;

	}
	return $options;
}




$property_info_base = array(
	'post_type'			=> 	'property',
	'name'				=>	__('Property Information', 'lsx'),
	'section'			=>	'',
	'section_priority'	=>	10,
	'panel'				=>	__( 'General', 'lsx' ),
	'panel_priority'	=>	10,
	'context'			=>	'advanced',
	'priority'			=>	'default',
	'fields'			=>	array()
);

/*
 * register the general tab
 *
*/
$metabox 				= $property_info_base;
$metabox['id'] 			= 'general';
$metabox['panel'] 		= __( 'General', 'lsx' );
$metabox['fields'] 		= array(
	'logo'				=>	array( 'label' => 'Logo' ),
	'star_authority'	=>	array( 'label' => 'Star Authority' ),
	'rooms'				=>	array( 'label' => 'Rooms' ),
	'stars'				=>	array( 'label' => 'Stars' ),
	'check_in_time'		=>	array( 'label' => 'Check in time' ),
	'check_out_time'	=>	array( 'label' => 'Check out time' ),
	'child_policy'		=>	array( 'label' => 'Child Policy' ),
	'from_price'		=>	array( 'label' => 'From Price' ),
	'business_hours'	=>	array( 'label' => 'Business Hours', 'type' => 'hours' ),
	'gallery_page'		=>	array( 'label' => 'Gallery Page', 'type' => 'select', 'options' => lsx_populate_gallery_select() ),
);
// register it
lsx_register_metabox( $metabox );

/*
 * register the location tab
 *
*/
$metabox 				= $property_info_base;
$metabox['post_type']	= 	array('property','accommodation','offers','restaurant', 'activity');
$metabox['id'] 			= 'location';
$metabox['panel'] 		= __( 'Location', 'lsx' );
$metabox['fields'] 		= array(
	'geolocation'			=>	array( 'label' => 'Geolocation', 'type' => 'geo' ),
	'latitude'				=>	array( 'label' => 'Latitude' ),
	'longitude'				=>	array( 'label' => 'Longitude' ),
	'driving_latitude'		=>	array( 'label' => 'Driving Latitude' ),
	'driving_longitude'		=>	array( 'label' => 'Driving Longitude' ),
	'suburb'				=>	array( 'label' => 'Suburb' ),
	'city'					=>	array( 'label' => 'City' ),
	'province'				=>	array( 'label' => 'Province' ),
	'region'				=>	array( 'label' => 'Region' ),
	'area'					=>	array( 'label' => 'Area' ),
	'country'				=>	array( 'label' => 'Country' ),
	'closest_town'			=>	array( 'label' => 'Closest Town' ),
	'distance_to_closest_town'=>	array( 'label' => 'Distance to Closest Town' ),
	'directions'			=>	array( 'label' => 'Directions', 'type' => 'textarea' ),	
);
// register it
lsx_register_metabox( $metabox );
// add geo tab
add_action('lsx_settings_module_tabs', function(){
	echo '<a class="{{#is _current_tab value="#lsx-panel-gmaps"}}nav-tab-active {{/is}}lsx-nav-tab nav-tab" href="#lsx-panel-gmaps">' . __('Google Maps', 'lsx') . '</a>';
});
// add geo tab template
add_action('lsx_settings_module_templates', function(){
?>
	<div id="lsx-panel-gmaps" class="lsx-editor-panel" {{#is _current_tab value="#lsx-panel-gmaps"}}{{else}} style="display:none;" {{/is}}>		
		<h4><?php _e('Google Maps', 'lsx') ; ?> <small class="description"><?php _e('API Key', 'lsx') ; ?></small></h4>
		<div class="lsx-config-group">
			<label for="lsx-gmaps-key">
				<?php _e( 'Google Maps API Key', 'lsx' ); ?>
			</label>
			<input type="text" name="gmaps_api_key" value="{{gmaps_api_key}}" id="lsx-gmaps-key">
		</div>
	</div>
<?php	
});

/*
 * register the external tab
 *
*/
$metabox 				= $property_info_base;
$metabox['id'] 			= 'external_services';
$metabox['panel'] 		= __( 'External Services', 'lsx' );
$metabox['fields'] 		= array(
	'tripadvisor_code'	=>	array( 'label' => 'Tripadvisor Code', 'type' => 'textarea' ),
);
// register it
lsx_register_metabox( $metabox );


/*
 * register the Contact Details - general tab
 *
*/
$metabox 				= $property_info_base;
$metabox['id'] 			= 'contact_details_general';
$metabox['panel'] 		= __( 'Contact Details', 'lsx' );
$metabox['section'] 	= __( 'General', 'lsx' );
$metabox['fields'] 		= array(
	'front_desk'		=>	array( 'label' => 'Front Desk Telephone' ),
	'contact_person'	=>  array( 'label' => 'Contact Person' ),
	'website_address'	=>  array( 'label' => 'Website Address' ),	
	'contact_email'		=>  array( 'label' => 'Email' ),
	'telephone_number'	=>  array( 'label' => 'Telephone Number' ),
	'fax_number'		=>  array( 'label' => 'Fax Number' ),
	'cell_number'		=>  array( 'label' => 'Cell Phone Number' ),
	'contact_person'	=>  array( 'label' => 'Contact Person' ),
	'address'			=>	array( 'label' => 'Physical Address' ),
	'postal_address'	=>	array( 'label' => 'Postal Address' ),
);
// register it
lsx_register_metabox( $metabox );



/*
 * register the Contact Details - general tab
 *
*/
$metabox 				= $property_info_base;
$metabox['id'] 			= 'contact_details_booking_reservations';
$metabox['panel'] 		= __( 'Contact Details', 'lsx' );
$metabox['section'] 	= __( 'Booking / Reservations', 'lsx' );
$metabox['fields'] 		= array(
	'telephone_number'			=>	array( 'label' => 'Telephone Number' ),
	'bookings_email'			=>	array( 'label' => 'Bookings Email' ),
	'skype'						=>	array( 'label' => 'Skype' ),
	'online_reservation_url'	=>	array( 'label' => 'Online Reservation Url' ),
	'mobile_reservation_url'	=>	array( 'label' => 'Mobile Reservation Url' ),
);
// register it
lsx_register_metabox( $metabox );


/*
 * register the Contact Details - general tab
 *
*/
$metabox 				= $property_info_base;
$metabox['id'] 			= 'contact_details_marketing';
$metabox['panel'] 		= __( 'Contact Details', 'lsx' );
$metabox['section'] 	= __( 'Marketing Contact Details', 'lsx' );
$metabox['fields'] = array(
	'marketing_contact_person'		=>	array( 'label' => 'Contact Person' ),
	'marketing_email_address'		=>	array( 'label' => 'Email Address' ),
	'marketing_telephone_number'	=>	array( 'label' => 'Telephone Number' ),
);
// register it
lsx_register_metabox( $metabox );




/*
 * register the Social Profiles
 *
*/
$metabox 				= $property_info_base;
$metabox['id'] 			= 'social_profiles';
$metabox['panel'] 		= __( 'Social Profiles', 'lsx' );
$metabox['fields'] = array(
	'facebook'		=>	array( 'label' => 'Facebook' ),
	'twitter'		=>	array( 'label' => 'Twitter' ),
	'linkedin'	=>	array( 'label' => 'LinkedIn' ),
	'instagram'	=>	array( 'label' => 'Instagram' ),
	'youtube'	=>	array( 'label' => 'YouTube' ),
	'google'	=>	array( 'label' => 'Google +' ),
	'pinterest'	=>	array( 'label' => 'Pinterest' ),
	'tripadvisor_url'	=>	array( 'label' => 'Tripadvisor URL' ),
	'airbnb'	=>	array( 'label' => 'Air BnB Link' )
);
// register it
lsx_register_metabox( $metabox );







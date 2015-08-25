<?php
/**
 * Metaboxes for this plugin
 *
 * @package   Lsx_Tours
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 LightSpeed
 */

function lsx_tours_form_select(){
	
	$forms = get_option( '_caldera_forms' , false );
	
	if(false == $forms ) { return array('0' => 'Please enable Caldera Forms.'); }
	$options = array();
	foreach($forms as $form_id=>$form_maybe){
		$options[ $form_id ] = $form_maybe['name'];
	}
	return $options;
}

/**
 * A simple function that registers the metaboxes. Triggers imeditenly after its declared.
 *
 * @package 	lsx-tours
 * @subpackage	metaboxes
 * @category	setup
 */
function lsx_tours_register_metaboxes(){
	// this is a base array of a metabox to reuse . this makes is easier.
	$metabox_base = array(
		'post_type'			=> 	array( 'tour' ), // array of post types this should be in
		'name'				=>	__('Details', 'lsx-tours'), // the label/name of the metabox 
		'section'			=>	'', // section creates heading in the body. metaboxes pages can join existing metaboxes made by LST
		'section_priority'	=>	10, // proirity to includ ewhen adding to existing metaboxes
		'panel'				=>	__( 'General', 'lsx-tours' ), // tab panbel to add to
		'panel_priority'	=>	10, // tab panel position priority.
		'context'			=>	'advanced', // metabox type ( core , advanced, side )
		'priority'			=>	'default', // priority of the box in editor
		'fields'			=>	array() // fields this metabox had
	);
	
	/*
	 * register the general tab
	 *
	*/
	
	$currency_choices = array();
	$currencys = get_option( '_happybeds_currency' );
	if( !empty( $currencys ) ){
		foreach( $currencys['currency'] as $currency ){
			$currency_choices[$currency['code']] = $currency['code'];
		}		
	}else{
		$currency_choices = array();
	}	
	
	$metabox 				= $metabox_base;
	$metabox['id'] 			= 'general';
	$metabox['panel']		= __( 'General', 'lsx-tours' );
	$metabox['fields'] 		= array(
		'price'				=>	array( 
				'label' 	=> __( 'Price','lsx-tours') ,
				'type' 		=> 'text',
				'default' 	=> '0'
		),
		'currency'			=>	array( 'label' => __( 'Currency','lsx-tours') ),	
		'duration'			=>	array( 'label' => __( 'Duration','lsx-tours'), 'type' => 'text' , 'description' => __( 'The number of nights.','lsx-tours') ),
		'group_size'		=>	array( 'label' => __( 'Group Size','lsx-tours'), 'type' => 'text' , 'description' => __( 'Maximum amount of people on the tour.','lsx-tours') ),
		'pdf_brochure'		=>	array( 'label' => __( 'PDF Brochure','lsx-tours'), 'type' => 'image' ),
		'featured'			=>	array( 'label' => __( 'Featured','lsx-tours'), 'type' => 'checkbox' ),			
		'language'			=>	array( 
									'label' 	=> _x( 'Supported Languages','lsx-tours'),
									'type'		=> 'select',
									'options' 	=> array(
													'en-ZA'		=>	'English',
													'af-ZA'		=>	'Afrikaans',
													'de-DE'		=>	'German',
													'es-ES'		=>	'Spanish',
													'fr-FR'		=>	'French',
													'it-IT'		=>	'Italian',
													'nl-NL'		=>	'Dutch',
													'pt-PT'		=>	'Portugese',
													'nn-NO'		=>	'Norwegian',
													'sv-SE'		=>	'Swedish',
												)
									),
		'identifier'		=>	array( 'label' => __( 'Identifier','lsx-tours'),'capability'	=> 'wetu_administrator' ),
		'last_modified'		=>	array( 'label' => __( 'Last modified','lsx-tours'),'capability'	=> 'wetu_administrator' ),			
	);
	// register it
	lsx_register_metabox( $metabox );

	/*
	 * register the gallery tab
	*
	*/
	$gallery_metabox 				= $metabox_base;
	$gallery_metabox['id'] 			= 'gallery';
	$gallery_metabox['panel']		= __( 'Gallery', 'lsx-tours' );
	$gallery_metabox['repeatable']	= false;
	$gallery_metabox['fields'] 		= array(
			'image'		=>	array( 'label' => __( 'Image','lsx-tours') , 'type' => 'image' , 'repeatable' => true ),
	);
	// register it
	lsx_register_metabox( $gallery_metabox );	
	
	/*
	 * register the gallery tab
	*
	*/
	$location_metabox 				= $metabox_base;
	$location_metabox['id'] 			= 'location';
	$location_metabox['panel']		= __( 'Location', 'lsx-tours' );
	$location_metabox['repeatable']	= false;
	$location_metabox['fields'] 		= array(
			'longitude'		=>	array( 'label' => __( 'Longitude','lsx-tours') , 'description' => 'e.g -33.9302139' ),
			'latitude'		=>	array( 'label' => __( 'Latitude','lsx-tours'), 'description' => 'e.g 18.4533731' ),
			'zoom'			=>	array(
					'label' 	=> _x( 'Zoom Level','lsx-tours'),
					'type'		=> 'select',
					'default'	=> '18_0',
					'description' => __( '20 is closest to the ground, 1 is far into space.','lsx-tours'),
					'options' 	=> array(
							'20_0'		=>	'20',
							'19_0'		=>	'19',
							'18_0'		=>	'18',
							'17_0'		=>	'17',
							'16_0'		=>	'16',
							'15_0'		=>	'15',
							'14_0'		=>	'14',
							'13_0'		=>	'13',
							'12_0'		=>	'12',
							'11_0'		=>	'11',
							'10_0'		=>	'10',
							'9_0'		=>	'9',
							'8_0'		=>	'8',
							'7_0'		=>	'7',
							'6_0'		=>	'6',
							'5_0'		=>	'5',
							'4_0'		=>	'4',
							'3_0'		=>	'3',
							'2_0'		=>	'2',
							'1_0'		=>	'1',							
					)
			),	
			'map_type'	=>	array(
					'label' 	=> _x( 'Map Type','lsx-tours'),
					'type'		=> 'select',
					'default'	=> 'satalite',
					'options' 	=> array(
							'satellite'	=>	'Satellite',
							'roadmap'	=>	'Road Map',
					)
			),								
	);
	// register it
	lsx_register_metabox( $location_metabox );	
	
	/*
	 * register the videos tab
	*
	*/
	$metabox 				= $metabox_base;
	$metabox['id'] 			= 'videos';
	$metabox['panel']		= __( 'Videos', 'lsx-tours' );
	$metabox['repeatable']	= true;
	$metabox['fields'] 		= array(
			'title'			=>	array( 'label' => __( 'Title','lsx-tours') ),
			'url'			=>	array( 'label' => __( 'URL','lsx-tours') ),
			'description'	=>	array( 'label' => __( 'Description','lsx-tours'), 'type' => 'textarea' ),
			'thumbnail'		=>	array( 'label' => __( 'Thumbnail','lsx-tours') , 'type' => 'image' ),
	);
	// register it
	lsx_register_metabox( $metabox );	
	

	/*
	 * register the Itinerary tab
	*/
	/*$metabox 				= $metabox_base;
	$metabox['id'] 			= 'accommodation';
	$metabox['name'] 		= __( 'Accommodation', 'lsx-tours' );
	$metabox['section']		= __( 'Accommodation', 'lsx-tours' );
	$metabox['repeatable']	= true;
	$metabox['fields'] 		= array(
		'accommodation'		=>	array( 'label' => _x( 'Accommodation','lsx-tours'), 'type' => 'text'),								
	);
	// register it
	lsx_register_metabox( $metabox );*/
	
	/*
	 * register the Dates Rates tab
	*/
	$rates_rates_metabox 				= $metabox_base;
	$rates_rates_metabox['id'] 			= 'general_rates';
	$rates_rates_metabox['name']		= __( 'Rates', 'lsx-tours' );
	$rates_rates_metabox['section']		= __( 'Rates', 'lsx-tours' );
	$rates_rates_metabox['repeatable']	= false;
	$rates_rates_metabox['fields'] 		= array(
			'price_includes'	=>	array( 'label' => __( 'Price Includes','lsx-tours'),'type'=>'textarea'),
			'price_excludes'	=>	array( 'label' => __( 'Price Excludes','lsx-tours'),'type'=>'textarea'),
	);	
	lsx_register_metabox( $rates_rates_metabox );	
	
	/*
	 * register the General Rates tab
	*
	*/
	$general_rates_metabox 				= $metabox_base;
	$general_rates_metabox['id'] 			= 'date_rates';
	$general_rates_metabox['name']		= __( 'Rates', 'lsx-tours' );
	$general_rates_metabox['panel']		= __( 'Dates', 'lsx-tours' );
	$general_rates_metabox['repeatable']	= true;
	$general_rates_metabox['fields'] 		= array(
			'start_date'		=>	array( 'label' => __( 'Start Date','lsx-tours'),'type' => 'date'),
			'end_date'			=>	array( 'label' => __( 'End Date','lsx-tours'),'type' => 'date'),
			'price_sharing'		=>	array( 'label' => __( 'Price Sharing','lsx-tours'), 'description' => _x( 'Enter a number without a currency symbol.','lsx-tours')),
			'single_add'		=>	array( 'label' => __( 'Single Add','lsx-tours'), 'description' => _x( 'Enter a number without a currency symbol.','lsx-tours')),
			'park_fees'			=>	array( 'label' => __( 'Park Fees','lsx-tours'), 'description' => _x( 'Enter a number without a currency symbol.','lsx-tours')),
	);
	
	// register it
	lsx_register_metabox( $general_rates_metabox );	
	
	/*
	 * register the features tab
	*/
	$metabox 				= $metabox_base;
	$metabox['id'] 			= 'days';
	$metabox['name'] 		= __( 'Itinerary', 'lsx-tours' );
	$metabox['section']		= __( 'Days', 'lsx-tours' );
	$metabox['repeatable']	= true;
	$metabox['fields'] 		= array(
			'thumbnail'			=>	array( 'label' => _x( 'Thumbnail','lsx-tours'), 'type' => 'image'),
			'description'		=>	array( 'label' => _x( 'Description','lsx-tours'), 'type' => 'textarea'),
			'nights'			=>	array( 'label' => _x( 'Nights','lsx-tours'), 'type' => 'text' ),
			'room_basis'		=>	array( 'label' => _x( 'Room basis','lsx-tours'), 'type' => 'text'),
			'drinks_basis'		=>	array( 'label' => _x( 'Drinks basis','lsx-tours'), 'type' => 'text'),
			'included'			=>	array( 'label' => _x( 'Included','lsx-tours'), 'type' => 'text'),
			'excluded'			=>	array( 'label' => _x( 'Excluded','lsx-tours'), 'type' => 'text'),
			'consultant_notes'	=>	array( 'label' => _x( 'Consultant notes','lsx-tours'), 'type' => 'textarea'),
			'location_country'	=>	array( 'label' => _x( 'Country','lsx-tours'), 'type' => 'text'),
			'location_region'	=>	array( 'label' => _x( 'Region','lsx-tours'), 'type' => 'text'),
			'location_city'		=>	array( 'label' => _x( 'City','lsx-tours'), 'type' => 'text'),
			//'activities'		=>	array( 'label' => _x( 'Activities','lsx-tours'), 'type' => 'text','repeatable' => true),
			'leg_id'			=>	array( 'label' => _x( 'Leg ID','lsx-tours'), 'type' => 'text', 'description' => _x( 'this will be hidden.','lsx-tours'),'capability'	=> 'wetu_administrator'),
			'sequence'			=>	array( 'label' => _x( 'Sequence','lsx-tours'), 'type' => 'text', 'description' => _x( 'this will be hidden.','lsx-tours'),'capability'	=> 'wetu_administrator')			
	);
	// register it
	lsx_register_metabox( $metabox );	
	
}
lsx_tours_register_metaboxes();

/**
 * Generates a day title for the itinerary items.
 *
 * @package 	lsx-tours
 * @subpackage	metaboxes
 * @category	filter
 */
function lsx_tours_collapsable_title($title,$part){
	
	//print_r($part);
	if(isset($part['id']) && isset($part['post_type']) && in_array('tour',$part['post_type'])){
		switch($part['id']){
			case 'videos':
				$title = 'Video';
			break;
			
			case 'days':
				$title = 'Day';
				break;			
			
			default:
			break;
		}
	}
	
	return $title;
	
}
add_filter('lsx_metabox_collapsable_panel_title','lsx_tours_collapsable_title',2,10);
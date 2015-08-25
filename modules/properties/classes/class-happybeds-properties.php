<?php
/**
 * Properties.
 *
 * @package   Lsx_Properties
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 */

/**
 * Plugin class.
 * @package Lsx_Properties
 * @author   LightSpeed Team
 */
class Lsx_Properties {

	/**
	 * The slug for this plugin
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'happybeds-properties';

	/**
	 * Holds class isntance
	 *
	 * @since 0.0.1
	 *
	 * @var      object|Lsx_Properties
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	
	
	/**
	 * Our tabs for the single property page.
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	public $tabs = array();	

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function __construct() {

		if( !empty( $_GET['_show_buggerup'] ) ){
			set_error_handler( "errorHandler" );
			ini_set( 'display_errors', 1 );
			error_reporting( E_ALL );
		}
		
		$this->tabs = array(
				'videos',
				'galleries',
				'activities',
				'accommodation',
				'restaurants',
				'offers',
				'contact',
				'location',
				'directions'
		);		

		add_filter( 'lsx_create_defaults', array( $this, 'create_defaults' ) );

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// activate property post type
		add_action( 'init', array( $this, 'activate_post_types' ) );
		
		//Add Endpoints
		add_action( 'init', array( $this, 'add_endpoints' ) );
		
		//function to set the endpoints query vars
		add_action( 'pre_get_posts', array( $this, 'set_tab_query_var' ) );

		// add metaplate filter
		add_filter( 'metaplate_data', array( $this, 'build_metaplate_data' ), 11, 2 );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );

		// Add post classes
		add_filter( 'post_class' , array( $this, 'post_classes') , '3', '10' );

		//Template Redirect
		add_filter( 'template_include', array( $this, 'post_type_archive_template_include'), 99 );
		add_filter( 'template_include', array( $this, 'post_type_single_template_include'), 99 );
		add_filter( 'template_include', array( $this, 'page_rates'), 99 );
		
		add_action( 'template_redirect', array( $this, 'redirect_single_property') );

		
		// add shortcode
		add_shortcode( 'properties', array( $this, 'render_property') );

		// add_filter for content on property
		//add_filter( 'the_content', array( $this, 'render_property_metaplate' ) );

	}

	public function create_defaults( $defaults ){

		$defaults['property'] = array(
			'type'	=>	'post-type',
			'data'	=>	array(
				
			)
		);

		return $defaults;
	}

	public function render_property_metaplate( $content ){

		global $post;

		if( empty( $post ) || $post->post_type !== 'property' || !is_single() ){
			return $content;
		}

		return $content . caldera_metaplate_from_file( LSX_PROPERTIES_PATH . 'metaplates/metaplate-single-property.html', $post->ID );


	}

	/**
	 * Bind meta data objects
	 *
	 * @since 0.0.1
	 *
	 * @return    array room post objects
	 */
	public function build_metaplate_data( $data, $metaplate ){
		global $post;

		if( $post->post_type !== 'property' ){
			return $data;
		}
		
		self::remove_jetpack_sharing();

		// add content
		ob_start();
		the_content();
		$data['post_content'] = ob_get_clean();
		//add exceprt
		$data['excerpt'] = str_replace('[&hellip;]', '', get_the_excerpt() );

		// permalink
		$data['permalink'] = get_the_permalink();

		// add taxonomies in with a taxonomy. alias
		$taxonomies = get_object_taxonomies( $post );
		if( !empty( $taxonomies ) ){
			foreach ( $taxonomies as $taxonomy_name  ) {

				$taxonomy = get_taxonomy( $taxonomy_name );
				$data['taxonomy'][ $taxonomy_name ] = $data[ $taxonomy_name ] = wp_get_post_terms( $post->ID, $taxonomy_name, array("fields" => "all") );

			}
		}
		if( !empty( get_the_post_thumbnail() ) ){
			$data['post_thumbnail'] = lsx_get_thumbnail( 'thumbnail-wide' );	
		}
		
		$classes = get_post_class();
		$data['post_class'] = implode( ' ', $classes );

		// get rooms and base rate
		if( isset( $data['connected_room'] ) && $post->post_type === 'property' ){
			if( is_object( $data['connected_room'] ) ){
				$data['connected_room'] = array( $data['connected_room'] );
			}
			foreach( (array) $data['connected_room'] as $index=>$room ){
				$is_room = get_post( $room );
				if( $is_room->post_type == 'room' || $is_room->post_type == 'accommodation' ){

					$data['connected_room'][$index] = $is_room;
					if( !empty( $is_room->_from_price ) ){
						if( !isset( $data['accommodation_from']['price'] ) || $data['accommodation_from']['price'] > $is_room->_from_price ){
							$data['accommodation_from']['price'] = $is_room->_from_price;
							$data['accommodation_from']['price_view'] = hb_currency_view( $is_room->_from_price, null, false );
						}
					}
					if( !empty( $is_room->_rate_type ) ){
						$data['accommodation_from']['rate_type'] = $is_room->_rate_type;
					}
				}
			}
			$data['accommodation_count'] = (string) count( $data['connected_room'] );
		}

		if( !empty( $data['offers_connect'] ) ){
			$data['offers_count'] = (string) count( $data['offers_connect'] );
		}
		if( !empty( $data['activities_connect'] ) ){
			$data['activities_count'] = (string) count( $data['activities_connect'] );
		}		
		if( !empty( $data['restaurants_connect'] ) ){
			$data['restaurants_count'] = (string) count( $data['restaurants_connect'] );
		}
		$data['global']['site_url'] = site_url();
		// get currency stuff
		$currencys = get_option( '_happybeds_currency' );
		if( !empty( $currencys ) ){
			foreach( $currencys['currency'] as $currency ){
				$currency_line = array(
					'code'	=>	$currency['code']
				);
				if( $currency['_id'] == $currencys['default_currency'] ){
					$currency_line['default'] = true;
				}
				$data['global']['currency'][] = $currency_line;
			}
			$data['global']['base_currency'][] = $currency['code'];
		}
		


		if( !empty( $data['gallery_page'] ) ){
			$media = get_attached_media( 'image', $data['gallery_page'] );
			$data['gallery_link'] = get_permalink( $data['gallery_page'] );
		}else{
			$media = get_attached_media( 'image', $post->ID );
		}

		if( !empty( $media ) ){
			$data['media'] = $media;
			$images = array();
			foreach( $media as $image ){
				$images[] = $image->ID;
			}

			$columns = 3;
			if( count( $images) > 10 ){
				$columns = 4;
			}
			if( count( $images) > 20 ){
				$columns = 5;
			}
			if( count( $images) > 40 ){
				$columns = 6;
			}
			if( count( $images) > 100 ){
				$columns = 8;
			}

			$data['gallery'] = do_shortcode( '[gallery columns="' . $columns . '" link="file" ids="' . implode(',', $images ) . '"]' );
			
			if( count( $images) > 3 ){
				$images = array_chunk($images, 3);
				$data['gallery_summary'] = do_shortcode( '[gallery columns="' . $columns . '" link="file" ids="' . implode(',', $images[0] ) . '"]' );
			}

		}

		// room rates
		if( !empty( $data['connected_room'] ) ){

			foreach ( $data['connected_room'] as $room_key => &$room) {

				$room->permalink = get_permalink( $room );

				if( !empty( $room->rates ) && !empty( $room->rates['rate_group'] ) ){

					$rates = array();

					foreach( $room->rates['rate_group'] as $key => $value ){
						// get rate group
						$group_parts = explode('-', $value);
						$post_meta = get_post_meta( $group_parts[0], 'rate_groups', true );
						$price = $room->rates['price'][ $key ];
						if( function_exists( 'hb_currency_view' ) ){
							ob_start();
							hb_currency_view( $price );
							$price = ob_get_clean();
						}
						if( !empty( $post_meta['group_name'] ) ){
							$rate_group = array(
								'group_name'	=>	$post_meta['group_name'][ $group_parts[1] ],
								'start_date'	=>	date('j M Y', strtotime( $post_meta['start_date'][ $group_parts[1] ] ) ),
								'end_date'		=>	date('j M Y', strtotime( $post_meta['end_date'][ $group_parts[1] ] ) ),
								'price'			=>	$price,
								'rate_type'		=>	$room->_rate_type
							);

							$rates[] = $rate_group;
						}
						
					}
					$room->rates = $rates;
				}

				// misc rates
				if( !empty( $post->misc_rate ) ){
					$rates = array();
					foreach( $post->misc_rate['rate_name'] as $key => $value ){
						// get rate group
						$price = $post->misc_rate['rate_price'][ $key ];
						if( function_exists( 'hb_currency_view' ) ){
							ob_start();
							hb_currency_view( $price );
							$price = ob_get_clean();
						}

						$rate_group = array(
							'group_name'	=>	$value,
							'start_date'	=>	date('j M Y', strtotime( $post->misc_rate['rate_start_date'][ $key ] ) ),
							'end_date'		=>	date('j M Y', strtotime( $post->misc_rate['rate_end_date'][ $key ] ) ),
							'price'			=>	$price,
						);

						$rates[] = $rate_group;
						
					}
					$room->misc_rate = $rates;
				}

			}
		}


		ob_start();
		echo '<pre>';
		var_dump( $data );
		echo '</pre>';
		$data['_fields'] = ob_get_clean();

		//var_dump( $data );
		return $data;
	}

	/**
	 * A filter to add some classes to the article tag.
	 *
	 * @param	$classes
	 * @param	$class
	 * @param	$post_ID
	 * 
	 * @return	$classes
	 */	
	public function post_classes($classes, $class, $post_ID){
		global $wp_query;
		
		if('property' == get_post_type($post_ID)){
			
			$classes[] = sanitize_key( get_post_meta( $post_ID, 'country', true ) );

			//Post Count
			$post_count = $wp_query->post_count;
			

			if(in_array('related-property', $classes)){
				$post_count = get_theme_mod('happybeds_property_single_related_property_amount',3);
				$columns = $post_count;
			}

		}
		return $classes;
	} 
	
	/**
	 *
	 * Redirect wordpress to the archive template located in the plugin
	 * @param	$template
	 *
	 * @return	$template
	 */	
	public function post_type_archive_template_include( $template ) {
	
		if ( is_main_query() 
			&& is_post_type_archive('property') 
			&& '' == locate_template( array( 'archive-property.php' ) )
			&& file_exists( LSX_PROPERTIES_PATH.'templates/' . "archive-property.php" )) {
			
			$template = LSX_PROPERTIES_PATH.'templates/' . "archive-property.php";
		}
		return $template;
	}
	

	/**
	 * Redirect wordpress to the single template located in the plugin
	 *
	 * @param	$template
	 *
	 * @return	$template
	 */
	public function post_type_single_template_include( $template ) {
	
		if ( is_main_query()
		&& is_singular('property')
		&& '' == locate_template( array( 'single-property.php' ) )
		&& file_exists( LSX_PROPERTIES_PATH.'templates/' . "single-property.php" )) {
			$template = LSX_PROPERTIES_PATH.'templates/' . "single-property.php";
		}
		return $template;
	}	

	/**
	 * Redirect wordpress to the rates tempalte
	 *
	 * @param	$template
	 *
	 * @return	$template
	 */
	public function page_rates( $template ) {
	
		if ( is_main_query()
		&& is_page('rates')
		&& file_exists( LSX_PROPERTIES_PATH.'templates/' . "rates-template.php" )) {
			$template = LSX_PROPERTIES_PATH.'templates/' . "rates-template.php";
		}
		return $template;
	}	
	
	
	
	/**
	 * Redirect the single links to the homepage if the single is set to be disabled.
	 *
	 * @param	$template
	 *
	 * @return	$template
	 */	
	public function redirect_single_property() {
		if(true == get_theme_mod('happybeds_property_single_disable',false)){
			$queried_post_type = get_query_var('post_type');
			if ( is_singular('property') && 'property' ==  $queried_post_type ) {
				wp_redirect( home_url(), 301 );
				exit;
			}
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return    object|Lsx_Properties    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( LSX_PROPERTIES_PATH ) . '/languages');

	}
	
	/**
	 * Register the properties post types.
	 *
	 *
	 * @return    null
	 */
	public function activate_post_types() {
		
		// define the properties post type
		$args = array(
			'labels' 				=> array(
				'name' 				=> __('Properties', 'happybeds-properties'),
				'singular_name' 	=> __('Property', 'happybeds-properties'),
				'add_new' 			=> __('Add New', 'happybeds-properties'),
				'add_new_item' 		=> __('Add New Property', 'happybeds-properties'),
				'edit_item' 		=> __('Edit Property', 'happybeds-properties'),
				'all_items' 		=> __('All Properties', 'happybeds-properties'),
				'view_item' 		=> __('View Property', 'happybeds-properties'),
				'search_items' 		=> __('Search Properties', 'happybeds-properties'),
				'not_found' 		=> __('No properties defined', 'happybeds-properties'),
				'not_found_in_trash'=> __('No properties in trash', 'happybeds-properties'),
				'parent_item_colon' => '',
				'menu_name' 		=> __('Properties', 'happybeds-properties')
			),
			'public' 				=>	true,
			'publicly_queryable'	=>	true,
			'show_ui' 				=>	true,
			'show_in_menu' 			=>	true,
			'query_var' 			=>	true,
			'rewrite' 				=>	array( 'slug' => 'property' ),
			'exclude_from_search' 	=>	false,
			'capability_type' 		=>	'post',
			'has_archive' 			=>	'properties',
			'hierarchical' 			=>	false,
			'menu_position' 		=>	null,
			'menu_icon'				=>	"dashicons-building",
			'supports' 				=> array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'author'
			),
		);
		
		// register post type
		lsx_register_post_type('property', $args);

		$labels = array(
			'name' => _x( 'Spoken Languages', 'lsx' ),
			'singular_name' => _x( 'language', 'lsx' ),
			'menu_name' => __( 'Languages' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('spoken_languages',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));

		$labels = array(
			'name' => _x( 'Special Interests', 'lsx' ),
			'singular_name' => _x( 'Special Interests', 'lsx' ),
			'menu_name' => __( 'Special Interests' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('special_interests',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));


		$labels = array(
			'name' => _x( 'Property Facilities', 'lsx' ),
			'singular_name' => _x( 'Property Facility', 'lsx' ),
			'menu_name' => __( 'Property Facilities' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('property_facilities',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));


		$labels = array(
			'name' => _x( 'Room Facilities', 'lsx' ),
			'singular_name' => _x( 'Room Facility', 'lsx' ),
			'menu_name' => __( 'Room Facilities' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('room_facilities',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));


		$labels = array(
			'name' => _x( 'Available Services', 'lsx' ),
			'singular_name' => _x( 'Available Service', 'lsx' ),
			'menu_name' => __( 'Available Services' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('available_services',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));



		$labels = array(
			'name' => _x( 'On Site Activities', 'lsx' ),
			'singular_name' => _x( 'On Site Activity', 'lsx' ),
			'menu_name' => __( 'On Site Activities' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('activities_on_site',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));


		$labels = array(
			'name' => _x( 'Off Site Activities', 'lsx' ),
			'singular_name' => _x( 'Off Site Activity', 'lsx' ),
			'menu_name' => __( 'Off Site Activities' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('activities_off_site',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));


		$labels = array(
			'name' => _x( 'Friendly', 'lsx' ),
			'singular_name' => _x( 'Friendly', 'lsx' ),
			'menu_name' => __( 'Friendly' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('friendly',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
		));

		$labels = array(
			'name' => _x( 'Types', 'lsx' ),
			'singular_name' => _x( 'Type', 'lsx' ),
			'menu_name' => __( 'Types' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('property_type',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true
		));

		$labels = array(
			'name' => _x( 'Locations', 'lsx' ),
			'singular_name' => _x( 'Location', 'lsx' ),
			'menu_name' => __( 'Locations' ),
		);   

		// Now register the taxonomy
		lsx_register_taxonomy('locations',array('property'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true
		));


	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 0.0.1
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();

		if( is_object( $screen ) && !empty( $screen->post_type ) && $screen->post_type == 'property' ){

			// include scripts or styles for edit screen	
		
		}


	}

	/**
	 * Render the shortcode
	 *
	 * @since 1.0.0
	 */
	public function render_property($atts, $code, $shortcode) {
	
		global $room;
	
		$limit = -1;
		if( !empty( $atts['limit'] ) ){
			$limit = $atts['limit'];
		}
		if( empty( $atts['id'] ) ){
			$filter = true;
		}
	
		if( !empty( $filter ) ){
	
			$filters = array(
					'posts_per_page'       => $limit,
					'post_type'			=> 'property'
			);
	
			if( !empty( $atts['order'] ) ){
				$filters['orderby'] = $atts['order'];
			}
		}else{
			$filters = 'post_type=property&p=' . $atts['id'];
		}
	
		$out = null;
	
		$property_query = new WP_Query( $filters );
	
		while ( $property_query->have_posts() ) : $property_query->the_post();
	
		$prefix = sanitize_title( $property_query->post->post_name );
	
		if( file_exists( get_stylesheet_directory() . '/property-' . $prefix . '.php' ) ){
	
			ob_start();
			include get_stylesheet_directory() . '/property-' . $prefix . '.php';
			$template = ob_get_clean();
	
		}else{
	
			ob_start();
			get_template_part( 'content', 'property' );
			$template = ob_get_clean();
	
		}
		$out .= $template;
	
		endwhile;
	
		wp_reset_postdata();
	
		return do_shortcode( $out );
	
	}	
	/**
	 * Remove the sharing from below the content on single accommodation.
	 */
	public static function remove_jetpack_sharing() {
		remove_filter( 'the_excerpt', 'sharing_display',19 );
		if ( class_exists( 'Jetpack_Likes' ) ) {
			remove_filter( 'the_excerpt', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
		}
		remove_filter( 'the_content', 'sharing_display',19 );
		if ( class_exists( 'Jetpack_Likes' ) ) {
			remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
		}
	}
	
	/**
	 * Add the url endpoints
	 */	
	public function add_endpoints() {
	

		foreach($this->tabs as $tab){
			add_rewrite_endpoint( $tab, EP_PERMALINK );
		}
	}

	
	/**
	 * Add the url endpoints
	 */
	public function set_tab_query_var($query) {
		if($query->is_main_query() && $query->is_singular() && 'property' == $query->get('post_type')){
			$default = true;
			foreach($this->tabs as $tab){
				if(isset($query->query_vars[$tab])){
					$query->set($tab,1);
					$default = false;
				}
			}	

			if(true == $default){
				$query->set('default',1);
			}
		}
	}
}
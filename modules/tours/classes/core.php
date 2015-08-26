<?php
/**
 * Module Template.
 *
 * @package   Lsx_Tours
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package Lsx_Tours
 * @author  LightSpeed
 */
class Lsx_Tours extends Lsx {

	/**
	 * The slug for this plugin
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'tour';

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object|Module_Template
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	
	/**
	 * The Enquire form ID
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	public $enquire_form = false;	
	
	/**
	 * The Booking form ID
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	public $booking_form = false;	
	
	/**
	 * Holds the google api key.
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	protected $google_api_key = false;	

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );
		
		// activate property post type
		add_action( 'init', array( $this, 'register_post_types' ) );		
		
		//Settings
		add_action('lsx_settings_module_templates',array( $this, 'display_settings_fields' ));
		add_action('lsx_settings_module_tabs',array( $this, 'display_settings_tab' ));		

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );

		// Load front style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_stylescripts' ) );
		
		//Populate the data for the meta plate
		add_filter( 'metaplate_data', array( $this, 'build_metaplate_data' ), 11, 2 );

		//Template Redirect
		add_filter( 'template_include', array( $this, 'post_type_archive_template_include'), 99 );
		add_filter( 'template_include', array( $this, 'post_type_single_template_include'), 99 );
		
		
		$options = Lsx_Options::get_single( 'lsx' );
		//if( false !== $options && is_array($options['lsx-tours']) ){
				
			if(isset($options['lsx-tours']['enquire_form']) && '' != $options['lsx-tours']['enquire_form']){
				$this->enquire_form = $options['lsx-tours']['enquire_form'];
			}
			if(isset($options['lsx-tours']['booking_form']) && '' != $options['lsx-tours']['booking_form']){
				$this->booking_form = $options['lsx-tours']['booking_form'];
			}
			if(isset($options['lsx-tours']['google_api_key']) && '' != $options['lsx-tours']['google_api_key']){
				$this->google_api_key = $options['lsx-tours']['google_api_key'];
			}			
		//}
	}
	
	


	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object|Module_Template    A single instance of this class.
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
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_slug, false, basename( LSX_TOURS_PATH ) . '/languages');
	}

	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function enqueue_front_stylescripts() {
		
		// if this module has front scripts or styles

	}
	
	/**
	 * Register the landing pages post type.
	 *
	 *
	 * @return    null
	 */
	public function register_post_types() {
	
		$labels = array(
		    'name'               => _x( 'Tours', 'lsx-tours' ),
		    'singular_name'      => _x( 'Tour', 'lsx-tours' ),
		    'add_new'            => _x( 'Add New', 'lsx-tours' ),
		    'add_new_item'       => _x( 'Add New Tour', 'lsx-tours' ),
		    'edit_item'          => _x( 'Edit Tour', 'lsx-tours' ),
		    'new_item'           => _x( 'New Tour', 'lsx-tours' ),
		    'all_items'          => _x( 'All Tours', 'lsx-tours' ),
		    'view_item'          => _x( 'View Tour', 'lsx-tours' ),
		    'search_items'       => _x( 'Search Tours', 'lsx-tours' ),
		    'not_found'          => _x( 'No tours found', 'lsx-tours' ),
		    'not_found_in_trash' => _x( 'No tours found in Trash', 'lsx-tours' ),
		    'parent_item_colon'  => '',
		    'menu_name'          => _x( 'Tours', 'lsx-tours' )
		);

		$args = array(
            'menu_icon'          =>'dashicons-admin-site',
		    'labels'             => $labels,
		    'public'             => true,
		    'publicly_queryable' => true,
		    'show_ui'            => true,
		    'show_in_menu'       => true,
		    'query_var'          => true,
		    'rewrite'            => array( 'slug' => 'tour' ),
		    'capability_type'    => 'post',
		    'has_archive'        => 'tours',
		    'hierarchical'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
		);

		lsx_register_post_type( 'tour', $args );
		
		
		//Register the Taxonomies
		$labels = array(
				'name' => _x( 'Experience', 'lsx-tours' ),
				'singular_name' => _x( 'Experience', 'lsx-tours' ),
				'search_items' =>  __( 'Search Experiences' , 'lsx-tours' ),
				'all_items' => __( 'Experiences' , 'lsx-tours' ),
				'parent_item' => __( 'Parent Experience' , 'lsx-tours' ),
				'parent_item_colon' => __( 'Parent Experience:' , 'lsx-tours' ),
				'edit_item' => __( 'Edit Experience' , 'lsx-tours' ),
				'update_item' => __( 'Update Experience' , 'lsx-tours' ),
				'add_new_item' => __( 'Add New Experience' , 'lsx-tours' ),
				'new_item_name' => __( 'New Room Experience' , 'lsx-tours' ),
				'menu_name' => __( 'Experiences' , 'lsx-tours' ),
		);
		
		
		// Now register the taxonomy
		lsx_register_taxonomy('experience',array('tour'), array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'experience' ),
		));
		
		$labels = array(
				'name' => _x( 'Tour Types', 'lsx-tours' ),
				'singular_name' => _x( 'Tour Type', 'lsx-tours' ),
				'search_items' =>  __( 'Search Tour Types' , 'lsx-tours' ),
				'all_items' => __( 'Tour Types' , 'lsx-tours' ),
				'parent_item' => __( 'Parent Tour Type' , 'lsx-tours' ),
				'parent_item_colon' => __( 'Parent Tour Type:' , 'lsx-tours' ),
				'edit_item' => __( 'Edit Tour Type' , 'lsx-tours' ),
				'update_item' => __( 'Update Tour Type' , 'lsx-tours' ),
				'add_new_item' => __( 'Add New Tour Type' , 'lsx-tours' ),
				'new_item_name' => __( 'New Room Tour Type' , 'lsx-tours' ),
				'menu_name' => __( 'Tour Types' , 'lsx-tours' ),
		);
		
		// Now register the taxonomy
		lsx_register_taxonomy('tour-type',array('tour'), array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'public' => true,
			'exclude_from_search' => false,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'tour-type' ),
		));		
		
	}	
	
	
	/**
	 * Redirect wordpress to the archive template located in the plugin
	 *
	 * @param	$template
	 *
	 * @return	$template
	 */
	public function post_type_archive_template_include( $template ) {
			
		if ( is_main_query() && ( is_post_type_archive('tour') || is_tax( 'tour-type' ) || is_tax( 'location' ) ) && '' == locate_template( array( 'archive-tour.php' ) ) && file_exists( LSX_TOURS_PATH.'/templates/' . "archive-tour.php" )) {	
			$template = LSX_TOURS_PATH.'/templates/' . "archive-tour.php";
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
		&& is_singular('tour')
		&& !file_exists( get_stylesheet_directory() . '/single-tour.php')) {
			$template = LSX_TOURS_PATH.'/templates/' . "single-tour.php";
		}
		return $template;
	}	
	
	
	/**
	 * Bind meta data objects
	 *
	 * @since 0.0.1
	 *
	 * @return    array room post objects
	 */
	public function build_metaplate_data( $data, $metaplate ){	
		
		global $post,$content_width;
	
		if( $post->post_type !== 'tour' ){
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
	
		// COUNTRY
		if( !empty( get_the_post_thumbnail() ) ){
			$data['post_thumbnail'] = get_the_post_thumbnail( null, 'thumbnail-wide' );
		}

		if(!empty( $data['videos'] )){
			foreach($data['videos'] as &$video){
				$video['iframe'] = apply_filters('the_content',$video['url']);
			}
		}
		if(!empty( $data['days'] )){
			$day_count = 1;
			foreach($data['days'] as &$day){
				$day['title'] = 'Day '.$day_count;
				
				if(isset($day['nights'])){
					$day_count = $day_count + $day['nights'];
					
					//If its more than 1 day, we add a range to the title
					if('1' != $day['nights'] && '' != $day['nights']){
						$day['title'].= ' - '.($day_count-1);
					}
				}else{
					$day_count++;
				}
				
			}			
		}
		
		if(false != $this->enquire_form && class_exists('Caldera_Forms')){
			$data['enquire_form'] = do_shortcode('[caldera_form id="'.$this->enquire_form.'"]');
		}
		
		if(false != $this->booking_form && class_exists('Caldera_Forms')){
			$data['booking_form'] = do_shortcode('[caldera_form id="'.$this->booking_form.'"]');
		}
		
		if(false != $this->google_api_key && !empty( $data['longitude'] ) && !empty( $data['latitude'] )){
			
			$zoom = '18';
			if(!empty( $data['zoom'] )){
				$zoom = $data['zoom'];
			}
			$map_type = 'satalite';
			if(!empty( $data['map_type'] )){
				$map_type = $data['map_type'];
			}			
			
			$data['map'] = "<iframe 
							src=\"https://www.google.com/maps/embed/v1/view
							?key={$this->google_api_key}
							&center={$data['longitude']},{$data['latitude']}
							&maptype={$map_type}
							&zoom={$zoom}\"
							width=\"{$content_width}\"
							height=\"400\"></iframe>";
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
	 * Outputs the field for the setting tab
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function display_settings_fields(){
		/**
		 * Include the template-panel
		 */
		include LSX_TOURS_PATH . 'includes/settings.php';
	}

	/**
	 * Outputs the tab for the setting page
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function display_settings_tab(){
	
		?>
			<a class="{{#is _current_tab value="#lsx-tours"}}nav-tab-active {{/is}}lsx-nav-tab nav-tab" href="#lsx-tours">
				<?php _e('Tours', 'lsx-tours') ; ?>
			</a>
		<?php
	}	

}
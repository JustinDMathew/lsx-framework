<?php

/**
 * Template Tags to 
 *
 * @package   Lsx_Property
 * @license   GPL-2.0+
 */
/**
 * A function to fetch the metaplate data form the front page
 * 
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	sorter
 */
if(!function_exists('happybeds_front_data')){
	function happybeds_front_data(){
		$master = get_option( '_hb_primary_property' );

		wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), '1.4.1', true ); 
		wp_enqueue_script( 'front-slider', get_stylesheet_directory_uri() . '/js/offer-slider.js', array('slick'), '1.0', true ); 

		add_filter( 'excerpt_more', function( $text ){ return '&hellip; <a href="' . get_permalink( get_option( '_hb_primary_property' ) ) . '">Read More</a>'; } );

		$data = array();

		$data['name'] = get_bloginfo( 'name', 'display' );
		$data['description'] = get_bloginfo( 'description', 'display' );
		$data['wpurl'] = get_bloginfo( 'wpurl', 'display' );
		$data['url'] = get_bloginfo( 'url', 'display' );
		$data['admin_email'] = get_bloginfo( 'admin_email', 'display' );
		$data['version'] = get_bloginfo( 'version', 'display' );
		$data['html_type'] = get_bloginfo( 'html_type', 'display' );
		$data['stylesheet_url'] = get_bloginfo( 'stylesheet_url', 'display' );

		$data['property'] = get_post( $master );
		$data['property']->post_content = apply_filters( 'the_content', $data['property']->post_content );
		$data['property']->excerpt = get_the_excerpt();

		// front contact reservations
		if( empty( $data['property']->telephone_number ) && empty( $data['property']->bookings_email ) && empty( $data['property']->skype ) ){
			$data['no_contact'] = true;
		}

		// get videos
		if( !empty( $data['property']->videos ) ){
			$data['property']->videos = get_post_meta( $data['property']->ID, 'videos' );
			foreach( (array) $data['property']->videos as $key=>$video ){
				$video['tag'] = basename( $video['url'] );
				$data['video'][$key] = $video;
				if( count( $data['video'] ) >= 3 ){
					break;
				}				
			}
		}

		//get rooms
		if( !empty( $data['property']->connected_room ) ){
			$data['property']->connected_room = get_post_meta( $data['property']->ID, 'connected_room' );
			foreach( (array) $data['property']->connected_room as $key=>$post_item ){
				$room = get_post( $post_item );
				$media = wp_get_attachment_url( get_post_thumbnail_id($post_item), 'slider-thumb' );
				if( !empty( $media ) ){					
					$room->image = $media;
				}				
				$room->permalink = get_permalink( $room->ID );
				$data['room'][] = $room;
				if( count( $data['room'] ) >= 3 ){
					break;
				}
			}
		}

		// video accomodataion size
		if( empty( $data['property']->videos ) || empty( $data['property']->connected_room ) ){
			$data['video_accommodation'] = '8 col-md-offset-2';
		}elseif( !empty( $data['property']->videos ) && !empty( $data['property']->connected_room ) ){
			$data['video_accommodation'] = '6';
		}


		//get activities_connect
		if( !empty( $data['property']->activities_connect ) ){
			$data['property']->activities_connect = get_post_meta( $data['property']->ID, 'activities_connect' );
			foreach( (array) $data['property']->activities_connect as $key=>$post_item ){

				$activity = get_post( $post_item );
				$media = wp_get_attachment_url( get_post_thumbnail_id($post_item), 'slider-thumb' );
				if( !empty( $media ) ){					
					$activity->image = $media;
				}
				$activity->permalink = get_permalink( $activity->ID );
				$data['activity'][] = $activity;

				if( $key >= 2 ){
					break;
				}
			}
		}
		//get restaurants_connect
		if( !empty( $data['property']->restaurants_connect ) ){
			$data['property']->restaurants_connect = get_post_meta( $data['property']->ID, 'restaurants_connect' );
			foreach( (array) $data['property']->restaurants_connect as $key=>$post_item ){

				$restaurant = get_post( $post_item );
				
				$media = wp_get_attachment_url( get_post_thumbnail_id($post_item), 'slider-thumb' );
				if( !empty( $media ) ){					
					$restaurant->image = $media;
				}

				$restaurant->permalink = get_permalink( $restaurant->ID );
				$data['restaurant'][] = $restaurant;

				if( $key >= 2 ){
					break;
				}
			}
		}
		// video activities-restaurants size
		if( empty( $data['property']->activities_connect ) || empty( $data['property']->restaurants_connect ) ){
			$data['activities_restaurants'] = '8 col-md-offset-2';
		}elseif( !empty( $data['property']->activities_connect ) && !empty( $data['property']->restaurants_connect ) ){
			$data['activities_restaurants'] = '6';
		}

		//get connected_offer
		if( !empty( $data['property']->connected_offer ) ){
			$data['property']->connected_offer = get_post_meta( $data['property']->ID, 'connected_offer' );
			foreach( (array) $data['property']->connected_offer as $key=>$post_item ){

				$offer = get_post( $post_item );
				$offer->from = get_post_meta( $post_item, '_from', true );
				$offer->to = get_post_meta( $post_item, '_to', true );
				
				$media = wp_get_attachment_url( get_post_thumbnail_id($post_item), 'slider-thumb' );
				if( empty( $media ) ){					
					$offer->image = $media;
				}

				$offer->permalink = get_permalink( $offer->ID );
				$data['offer'][] = $offer;

				if( $key >= 2 ){
					break;
				}
			}
		}

		if( !empty( $data['property']->gallery_page ) ){
			$media = get_attached_media( 'image', $data['property']->gallery_page );
		}else{
			$media = get_attached_media( 'image', $data['property']->ID );
		}
		if( !empty( $media ) ){
			$images = array();
			foreach( $media as $image ){
				$images[] = wp_get_attachment_url( $image->ID , 'slider-thumb' );
			}
			$data['media'] = $images;
		}

		return $data;
	}
}
/**
 * A function to see what the current tab is.
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	conditional
 */
function happybeds_properties_is_tab($needle = false){
	
	global $wp_query;
	$current_tab = get_query_var($needle); 
	if(false == $current_tab || null == $current_tab || '' == $current_tab){
		return false;
	} else {
		return true;
	}
}

/**
 * A property type filter for the property template
 * 
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	sorter
 */
if(!function_exists('happybeds_property_sorter')){
	function happybeds_property_sorter(){
		global $wp_query;
		$countries = array();
		foreach( $wp_query->posts as $post ){
			if(!isset( $countries[ sanitize_key( $post->country ) ] ) ){
				$countries[ sanitize_key( $post->country ) ] = $post->country;
			}
		}
		?>
				<div class="property-filters">
					<div class="caldera_forms_form panel-filters">

						<?php
						/*
						 * Do not show if there is only 1 post
						*/ 
						$types = get_terms('property-type');
						if( $wp_query->post_count > 1 && is_array($types) && count( $types ) > 1 ){
						?>
						<div class="form-group">
							<div>
								<select name="type" class="form-control panel-filter-control">
									<option value="">Property Type</option>
									<?php
									foreach ($types as $type) {
										echo '<option value="' . esc_attr( $type->slug ) . '">' . $type->name . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<?php } ?>
						<?php if( $wp_query->post_count > 1 && count( $countries ) > 1 ){ ?>
						<div class="form-group">
							<div>
								<select name="country" class="form-control panel-filter-control">
									<option value="">Country</option>
									<?php foreach( $countries as $country_key=>$country ){ ?>
									<option value="<?php echo $country_key; ?>"><?php echo $country; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
						<?php $enquire_modal = classybeds_is_form_enabled('enquire');
							if(false != $enquire_modal){
							?>						
							<div class="form-group">
								<div>
									<button class="btn btn-default enquire-now" data-toggle="modal" data-target="#enquire-modal"><?php _e('Make an Enquiry','classybeds-lsx-child'); ?></button>
								</div>
							</div>
						<?php } ?>							
					</div>
				</div>
		<?php
	}
}

/**
 * Echos booking button, for use in a loop
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	url
 */
if(!function_exists('happybeds_property_button')){
	function happybeds_property_button($post_id = false){

		$is_single_disabled = get_theme_mod('happybeds_property_single_disable',false);
		if(false == $is_single_disabled){
			?>
				<a href="<?php echo get_permalink($post_id); ?>" class="property-read-more" role="button"><?php _e('Read More','happybeds-property'); ?></a>
			<?php 
		}else{
			happybeds_booking_button();
		}
	}
}

/**
 * Echos booking button, for use in a loop
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	url
 */
if(!function_exists('happybeds_booking_button')){
	function happybeds_booking_button($post_id = false){
		$url = happybeds_get_booking_url($post_id);
		if(false != $url){
			?>
				<a href="<?php echo $url; ?>" target="_blank" class="btn btn-primary btn-lg book-now" role="button"><?php _e('Book Now','happybeds-property'); ?></a>
			<?php 
		}
	}
}

/**
 * Echos enquire button, if the form is enabled
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	enquire
 */
if(!function_exists('happybeds_enquire_button')){
	function happybeds_enquire_button($post_id = false){
		$enquire_modal = classybeds_is_form_enabled('enquire');
		if(false != $enquire_modal){
			?>
				<a data-toggle="modal" data-target="#enquire-modal" class="btn btn-default btn-lg btn-block enquire-now"><?php _e('Make an Enquiry','happybeds-property'); ?></a>
			<?php 
		}
	}
}

/**
 * Grabs the correct template part for the property loop.
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	template
 * 
 * @param		$slug	String
 * @param		$name	String
 */
function happybeds_property_get_template_part($slug, $name = '', $shortcode = false){
	global $post;
	//Templates to look for in order
	$template = locate_template( array("{$slug}-{$name}.php") );
	
	$layout = get_theme_mod('happybeds_property_archive_layout','default');
	if('default' == $layout){
		$layout = '';
	}else{
		$layout = '-'.$layout;
	}
	
	if(true != $shortcode){
		$layout = '';
	}

	//Load the Default metaplate template in the theme
	if ( ! $template && $name && file_exists( LSX_PROPERTIES_PATH.'templates/' . "metaplate-{$slug}-{$name}{$layout}.html" ) && function_exists( 'caldera_metaplate_from_file' ) ) {
		$template = LSX_PROPERTIES_PATH.'templates/' . "metaplate-{$slug}-{$name}{$layout}.html";
		echo caldera_metaplate_from_file( $template, $post->ID );
		return;
	}
	//Load the Default template in the theme
	if ( ! $template && $name && file_exists( LSX_PROPERTIES_PATH.'templates/' . "{$slug}-{$name}{$layout}.php" ) ) {
		$template = LSX_PROPERTIES_PATH.'templates/' . "{$slug}-{$name}{$layout}.php";
	}
	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Suggested Property
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	related
 *
 * @param		$before			html
 * @param		$after			html
 */
function happybeds_related_property(){

	if(false == get_theme_mod('happybeds_property_single_display_related_property',true)){
		return;
	}
	

	$filters = array('post_type'	=>	'property');
	
	//Get the settings from the customizer options
	$limit = get_theme_mod('happybeds_property_single_related_property_amount','3');
	$filters['posts_per_page'] = $limit;
	
	
	//Exclude the current post
	$filters['post__not_in'] = array(get_the_ID());
	
	//if its set to related then we need to check by the type.
	$type = get_theme_mod('happybeds_property_single_related_property_type','random');
	if('property-type' == $type){
		$term = get_theme_mod('happybeds_property_single_related_property_type_term',false);
		$filters['orderby'] = 'rand';
		$current_post = get_queried_object_id();
		if(false != $current_post){
			$terms = wp_get_object_terms($current_post, 'property-type');

			//only allow relation by 1 property type term
			if(is_array($terms) != $term){
				foreach($terms as $term){
					$filters[$type] = $term->slug;
				}
			}	
		}
	}elseif('featured' == $type){
		$filters['meta_value'] = 1;
		$filters['meta_key'] = '_is_featured';
		$filters['orderby'] = 'rand';
	}else{
		$filters['orderby'] = $type;
	}
	
	$property_query = new WP_Query( $filters );

	if($property_query->have_posts()) :
	?>
	<div id="related-properties" class="col-md-12">
		<h3>Related Campsites</h3>

		<div class="lsx-property-wrapper filter-items-wrapper">
			<div id="property-infinite-scroll-wrapper" class="filter-items-container lsx-property masonry">
	<?php
		
		Lsx_Properties::remove_jetpack_sharing();
	
		while ( $property_query->have_posts() ) : $property_query->the_post();
			
			if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_PROPERTIES_PATH . 'templates/metaplate-related-property.html' ) ){
				echo caldera_metaplate_from_file( LSX_PROPERTIES_PATH . 'templates/metaplate-related-property.html', get_the_id() );
			}
		
		endwhile;
		
			echo '</div>';
		echo '</div>';
	echo '</div>';

		
		wp_reset_postdata();
	endif;	
}

/**
 * Outputs the Room Price
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	price
 */
if(!function_exists('happybeds_property_price')){
	function happybeds_property_price($post_id = false){

		if(false == $post_id){ $post_id = get_the_ID(); }
		$price = get_post_meta( $post_id, '_from_price', true ); 
		
		?>
		<div class="price-wrapper">
			<span class="price">
				<?php
					if(false != $price){
						_e('Price per night from: ','happybeds-property');
					} else {
						_e('Price available on request. ','happybeds-property');
					}
				?>
				<span class="price-inner">
					<?php if ( function_exists('hb_currency_view') && false != $price ) { hb_currency_view( $price ); } elseif(false != $price) { echo $price; } ?>
				</span>
			</span>
		</div>
		<?php 
	}
}

/**
 * Outputs the Room Price on Single Page
 *
 * @package 	happybeds-property
 * @subpackage	template-tags
 * @category	price
 */
if(!function_exists('happybeds_single_property_price')){
	function happybeds_single_property_price($post_id = false){

		if(false == $post_id){ $post_id = get_the_ID(); }
		$price = get_post_meta( $post_id, '_from_price', true );
		$rate_type = get_post_meta( $post_id, '_rate_type', true );

		if(false != $price){
		?>		
			<p><?php _e('from','happybeds-property'); ?></p>
			<span class="price">
				<span class="price-inner">
					<?php if ( function_exists('hb_currency_view') ) { hb_currency_view( $price ); } else { echo $price; } ?>
				</span>
			</span>
			<p><?php echo $rate_type; ?></p>
		<?php
		} else { ?>
			<p><?php _e('Price available on request.','happybeds-property'); ?></p>
		<?php }	
		 
	}
}




add_action( 'lsx_footer_before', 'lsx_add_single_properties_bottom' );
function lsx_add_single_properties_bottom() {
	if ( is_page_template( 'single-property.php' ) ) { ?>
		<div id="property-single-bottom">
			<script src="https://maps.googleapis.com/maps/api/js?"></script>
		    <script>
		        function initialize() {
		    	
		        var myLatlng = new google.maps.LatLng(-33.92945, 18.45345);

		        var mapOptions = {
			        center: myLatlng,
			        zoom: 15,
			        scrollwheel: false,
			        panControl: false,
				    zoomControl: false,
				    scaleControl: false,
					mapTypeControl: false,
					streetViewControl: false,
					overviewMapControl: false
		        }

		        var map = new google.maps.Map(document.getElementById('ssingle-property-map'),
		            mapOptions);

		        var marker = new google.maps.Marker({
				    position: myLatlng,
				    map: map
				});
		      }

		      google.maps.event.addDomListener(window, 'load', initialize);
		    </script>
			
		    <div id="single-property-map-wrapper">
				<div id="single-property-map" style="width:500px; height:500px;">OOOK</div>
			</div>


			<!-- Similar to the other masonry layouts markup, but note where I've put the excerpts (in the thumbnail anchor tag) -->
			<div id="related-properties" class="col-md-12">
				<h3>Related Properties</h3>

				<div class="lsx-property-wrapper filter-items-wrapper">
					<div id="property-infinite-scroll-wrapper" class="filter-items-container lsx-property masonry">
						<article id="post-3489" class="property">
							<div class="property-content-wrapper">
								<div class="property-thumbnail">
									<a href="http://mushara.feedmybeta.com/properties/mushara-outpost/">
										<span class="related-property-excerpt">This is where the related property excerpt is supposed to be...</span>
										<img class="attachment-responsive wp-post-image lsx-responsive-banner lsx-responsive" src="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Mushara-Outpost-slider-home-2-350x230.jpg" data-desktop="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Mushara-Outpost-slider-home-2-350x230.jpg" data-tablet="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Mushara-Outpost-slider-home-2-350x230.jpg" data-mobile="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Mushara-Outpost-slider-home-2-350x230.jpg" alt="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Mushara-Outpost-slider-home-2-350x230">		
									</a>
								</div>
								
								<div class="property-content">	
									<h3>
										<a href="http://mushara.feedmybeta.com/properties/mushara-outpost/" rel="bookmark">
											<span>Mushara Outpost</span>
										</a>
									</h3>		
									
								</div>
							</div>
						</article>
						
						<article id="post-3488" class="property">
							<div class="property-content-wrapper">
								<div class="property-thumbnail">
									<a href="http://mushara.feedmybeta.com/properties/mushara-bushcamp/">
										<span class="related-property-excerpt">This is where the related property excerpt is supposed to be...</span>
										<img class="attachment-responsive wp-post-image lsx-responsive-banner lsx-responsive" src="http://mushara.feedmybeta.com/wp-content/uploads/2015/04/Mushara-Bush-Camp-tents-exterior-body-350x230.jpg" data-desktop="http://mushara.feedmybeta.com/wp-content/uploads/2015/04/Mushara-Bush-Camp-tents-exterior-body-350x230.jpg" data-tablet="http://mushara.feedmybeta.com/wp-content/uploads/2015/04/Mushara-Bush-Camp-tents-exterior-body-350x230.jpg" data-mobile="http://mushara.feedmybeta.com/wp-content/uploads/2015/04/Mushara-Bush-Camp-tents-exterior-body-350x230.jpg" alt="http://mushara.feedmybeta.com/wp-content/uploads/2015/04/Mushara-Bush-Camp-tents-exterior-body-350x230">
									</a>

								</div>
								
								<div class="property-content">	
									<h3>
										<a href="http://mushara.feedmybeta.com/properties/mushara-bushcamp/" rel="bookmark">
											<span>Mushara Bushcamp</span>
										</a>
									</h3>	
								</div>
							</div>
						</article>
						
						<article id="post-3487" class="property">
							<div class="property-content-wrapper">
								<div class="property-thumbnail">
									<a href="http://mushara.feedmybeta.com/properties/mushara-villa/">
										<span class="related-property-excerpt">This is where the related property excerpt is supposed to be...</span>
										<img class="attachment-responsive wp-post-image lsx-responsive-banner lsx-responsive" src="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Villa-Mushara-Home-Slider-2-350x230.jpg" data-desktop="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Villa-Mushara-Home-Slider-2-350x230.jpg" data-tablet="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Villa-Mushara-Home-Slider-2-350x230.jpg" data-mobile="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Villa-Mushara-Home-Slider-2-350x230.jpg" alt="http://mushara.feedmybeta.com/wp-content/uploads/2015/03/Villa-Mushara-Home-Slider-2-350x230">				
									</a>
								</div>
								
								<div class="property-content">	
									<h3>
										<a href="http://mushara.feedmybeta.com/properties/mushara-villa/" rel="bookmark">
											<span>Mushara Villa</span>
										</a>
									</h3>	
								</div>
							</div>
						</article>
					</div>
				</div>
			</div>
		</div>
	<?php }
}

/**
 * Checks if a caldera form with your slug exists
 *
 * @package 	happybeds-property
 * @subpackage	setup
 * @category 	helper
 */
/*
if( !function_exists( 'lsx_is_form_enabled' ) ){
	function lsx_is_form_enabled($slug = false) {
		if(false == $slug){ return false; }

		$match = false;
		$forms = get_option( '_caldera_forms' , false );
		if(false !== $forms ) {
			foreach($forms as $form_id=>$form_maybe){
				if( trim(strtolower($slug)) == strtolower($form_maybe['name']) ){
					$match = $form_id;
					break;
				}
			}
		}
		if( false === $match ){
			$is_form = Caldera_Forms::get_form( strtolower( $slug ) );
			if( !empty( $is_form ) ){
				return strtolower( $slug );
			}
		}
		
		return $match;
	}
}

/**
 * Adds Enquire Form on Single Properties sticky booking widget
 *
 * @package 	lsx
 * @subpackage	hooks
 * @category	enquire bar
 */
/*
if( !function_exists( 'lsx_sticky_enquire_form' ) ){
	function lsx_sticky_enquire_form() { 
		
		$enquire_modal = lsx_is_form_enabled('enquire');
		if(false != $enquire_modal){
			global $post;

			if( $post->post_type == 'accommodation' ){
				if( !empty( $post->connected_room ) ){
					$property = get_post( $post->connected_room );
				}elseif( !empty( $post->property ) ){
					$property = get_post( $post->property );
				}
			}elseif( $post->post_type == 'property' ){
				$property = $post;
			}

		?>							
		<form class="form-inline" onsubmit="return false;">
			<div class="form-group has-feedback sync-start">
				<input type="text" data-provide="cfdatepicker" data-date-autoclose="true" class="form-control is-cfdatepicker" id="check_in" data-date-format="dd-mm-yyyy" name="check_in" data-sync="fld_9703038" placeholder="Check In" value="">
				<span class="form-control-feedback genericon genericon-month"></span>
			</div>
			<div class="form-group has-feedback sync-end">
				<input type="text" data-provide="cfdatepicker" data-date-autoclose="true" class="form-control is-cfdatepicker" id="check_out" data-date-format="dd-mm-yyyy" name="check_out" data-sync="fld_8979815" placeholder="Check Out" value="">
				<span class="form-control-feedback genericon genericon-month"></span>
			</div>
			<?php if( isset( $property ) && !empty( $property->online_reservation_url ) && !wp_is_mobile() ){ ?>
			<button href="<?php echo esc_url( $property->online_reservation_url ); ?>" target="_blank" class="btn btn-primary btn-lg book-now" role="button">Book Now</button>
			<?php } ?>
			<?php if( isset( $property ) && !empty( $property->direct_link_to_online_reservation_mobile_optimised ) && wp_is_mobile() ){ ?>
			<button href="<?php echo esc_url( $property->direct_link_to_online_reservation_mobile_optimised ); ?>" target="_blank" class="btn btn-primary btn-lg book-now" role="button">Book Now</button>
			<?php } ?>
			<button class="btn btn-default enquire-now" data-toggle="modal" data-target="#enquire-modal"><?php _e('Make an Enquiry','classybeds-lsx-child'); ?></button>
		</form>
	<?php }
	}
}*/
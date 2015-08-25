<?php
/**
 * The Template for displaying all single posts.
 *
 * @package lsx
 */

get_header(); ?>
	<?php if( function_exists( 'lsx_sticky_enquire_form' ) ){ ?>
	<div id="secondary" class="widget-area test col-md-4" role="complementary">
		<aside id="book" class="widget sticky-enquire">
			<div class="panel panel-default">
				<div class="panel-body metaplate">
					<?php lsx_sticky_enquire_form(); ?>
				</div>
			</div>
		</aside>
	</div><!-- #secondary -->
	<?php } ?>
	<div id="primary" class="content-area col-md-8">

		<?php //lsx_content_before(); ?>
		
		<main id="main" class="site-main" role="main">

		<?php //lsx_content_top(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			// try get location stuff
			$latitude = get_post_meta( get_the_id(), 'latitude', true );
			$longitude = get_post_meta( get_the_id(), 'longitude', true );

			?>
			<?php //lsx_entry_before(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php //lsx_entry_top(); ?>
				
				
				<?php // Default Page Content 
				
				if(happybeds_properties_is_tab('default')) { ?>
					<div class="entry-content">
					<?php

					// load content
					if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( get_template_directory() . 'templates/metaplate-single-property.html' ) ){
						
						echo caldera_metaplate_from_file( get_template_directory() . 'templates/metaplate-single-property.html', get_the_id() );

					}elseif( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_PROPERTIES_PATH . 'templates/metaplate-single-property.html' ) ){
						
						echo caldera_metaplate_from_file( LSX_PROPERTIES_PATH . 'templates/metaplate-single-property.html', get_the_id() );
					
					}else{

						the_content();
					}
	
					?>
					</div><!-- .entry-content -->
				<?php } ?>
				
				<?php // Activities Content 
				if(happybeds_properties_is_tab('activities')) { ?>
					<div class="activity-content">
						<h3><?php _e('Activities','happybeds-properties');?></h3>
					</div>
				<?php } ?>
				
				<?php // Contact Details 
				if(happybeds_properties_is_tab('directions') || happybeds_properties_is_tab('location') || happybeds_properties_is_tab('contact')) { ?>
					<div class="directions-content">
						<h3><?php _e('Directions','happybeds-properties');?></h3>
					</div>
				<?php } ?>	
				
				<?php // Galleries Content 
				if(happybeds_properties_is_tab('galleries')) { ?>
					<div class="galleries-content">
						<h3><?php _e('Galleries','happybeds-properties');?></h3>
					</div>
				<?php } ?>	
				
				<?php // Offers Content 
				if(happybeds_properties_is_tab('offers')) { ?>
					<div class="offers-content">
						<h3><?php _e('Offers','happybeds-properties');?></h3>
					</div>
				<?php } ?>
				
				<?php // Restaurants Content 
				if(happybeds_properties_is_tab('restaurants')) { ?>
					<div class="restaurants-content">
						<h3><?php _e('Restaurants','happybeds-properties');?></h3>
					</div>
				<?php } ?>	

				<?php // Accommodation Content 
				if(happybeds_properties_is_tab('accommodation')) { ?>
					<div class="restaurants-content">
						<h3><?php _e('Accommodation','happybeds-properties');?></h3>
					</div>
				<?php } ?>				
				
				<?php // Videos Content 
				if(happybeds_properties_is_tab('videos')) { ?>
					<div class="videos-content">
						<h3><?php _e('Videos','happybeds-properties');?></h3>
					</div>
				<?php } ?>	
				
				<?php edit_post_link( __( 'Edit', 'classybeds-lsx-child' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
				
				<?php //lsx_entry_bottom(); ?>

			</article><!-- #post-## -->

			<?php //lsx_entry_after(); ?>

		<?php endwhile; // end of the loop. ?>
		
		<?php //lsx_content_bottom(); ?>
		
		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() ) :
				comments_template();
			endif;
		?>		

		</main><!-- #main -->			

		<?php //lsx_content_after(); ?>
		
	</div><!-- #primary -->
	<?php if( !empty( $latitude ) && !empty( $longitude ) ){ ?>
	
	<script src="https://maps.googleapis.com/maps/api/js?"></script>
    <script>
        function initialize() {
    	
        var myLatlng = new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>);

        var mapOptions = {
	        center: myLatlng,
	        zoom: 10,
	        scrollwheel: false,
	        panControl: false,
		    zoomControl: false,
		    scaleControl: false,
			mapTypeControl: false,
			streetViewControl: false,
			overviewMapControl: false
        }

        var map = new google.maps.Map(document.getElementById('gm-single-property-map'),
            mapOptions);

        var marker = new google.maps.Marker({
		    position: myLatlng,
		    map: map
		});
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	<div id="gm-single-property-map" style="clear:both; height: 400px; width:auto; margin: 0 -50%;"></div>
	
	<?php } ?>

	<?php happybeds_related_property(); ?>

	<?php 
		if ( function_exists( 'sharing_display' ) ) {
		    sharing_display( '', true );
		}
		 
		if ( class_exists( 'Jetpack_Likes' ) ) {
		    $custom_likes = new Jetpack_Likes;
		    echo '<div class="col-md-12">';
		    echo $custom_likes->post_likes( '' );
		    echo '</div>';
		}
	?>

<?php get_footer(); ?>
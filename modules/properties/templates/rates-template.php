<?php
get_header(); ?>

	<?php lsx_page_header_embeds(); ?>

	<div id="primary" class="content-area col-sm-12">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main" role="main">

			<?php lsx_content_top(); ?>

			<header class="page-header property-archive-header">
					<h1 class="page-title">Rates</h1>
			</header><!-- .entry-header -->


			
			<?php
				if ( post_type_exists( 'property' ) && have_posts() ) :
			?>

				<?php while ( have_posts() ) : the_post(); ?>


					<?php
					
						$filters = array('post_type'	=>	'property');
						$filters['posts_per_page'] = -1;

						$property_query = new WP_Query( $filters );

						if($property_query->have_posts()) :
				
						while ( $property_query->have_posts() ) : $property_query->the_post();
							
							if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_PROPERTIES_PATH . 'templates/metaplate-property-rates.html' ) ){
								echo caldera_metaplate_from_file( LSX_PROPERTIES_PATH . 'templates/metaplate-property-rates.html', get_the_id() );
							}
						
						endwhile;
						wp_reset_postdata();

						endif;

					 ?>

				<?php endwhile; ?>

			<?php else : ?>

				<section class="no-results not-found">
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Nothing Found', 'classybeds-lsx-child' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<?php if ( current_user_can( 'publish_posts' ) ) : ?>

							<p><?php printf( __( 'Ready to publish your first property? <a href="%1$s">Get started here</a>.', 'classybeds-lsx-child' ), esc_url( admin_url( 'post-new.php?post_type=property' ) ) ); ?></p>

						<?php else : ?>

							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'classybeds-lsx-child' ); ?></p>
							<?php get_search_form(); ?>

						<?php endif; ?>
					</div><!-- .page-content -->
				</section><!-- .no-results -->

			<?php endif; ?>	

			<div class="clearfix"></div>

		</main><!-- #main -->

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->
	
<?php get_footer(); ?>
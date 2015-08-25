<?php
/**
 * The Template for displaying all single tours.
 *
 * @package lsx
 */

get_header(); ?>

	<div id="primary" class="content-area col-md-12">

		<?php lsx_content_before(); ?>
		
		<main id="main" class="site-main" role="main">

		<?php lsx_content_top(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>

			<?php lsx_entry_before(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php lsx_entry_top(); ?>
				
				<h1 class="title"><?php the_title(); ?></h1>

				<div class="entry-content">
				<?php
				// load content
				if( function_exists( 'caldera_metaplate_from_file' ) && file_exists( get_stylesheet_directory() . 'templates/metaplate-single-tour.html' ) ){
						
					echo caldera_metaplate_from_file( get_stylesheet_directory() . 'templates/metaplate-single-tour.html', get_the_id() );

				}elseif( function_exists( 'caldera_metaplate_from_file' ) && file_exists( LSX_TOURS_PATH . 'templates/metaplate-single-tour.html' ) ){
					echo caldera_metaplate_from_file( LSX_TOURS_PATH . 'templates/metaplate-single-tour.html', get_the_id() );
				}else{
					the_content();
				}
				?>
				</div><!-- .entry-content -->
				
				<?php lsx_entry_bottom(); ?>

			</article><!-- #post-## -->

			<?php lsx_entry_after(); ?>

		<?php endwhile; // end of the loop. ?>
		
		<?php lsx_content_bottom(); ?>
		
		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() ) :
				comments_template();
			endif;
		?>		

		</main><!-- #main -->			

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->
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
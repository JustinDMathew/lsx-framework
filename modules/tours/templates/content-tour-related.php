<?php
/**
 * The template used for displaying accommodation posts on the accommodation archive
 *
 * @package happybeds-accommodation
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('related-accommodation'); ?>  >
	<div class="accommodation-content-wrapper">	
		<?php if ( '' != get_the_post_thumbnail() ) : ?>
			<div class="accommodation-thumbnail">
				<a href="<?php the_permalink(); ?>">
				<?php 
					if(function_exists('lsx_thumbnail')){
						lsx_thumbnail( 'thumbnail-wide' );
					}else{
						the_post_thumbnail();
					}
				?>
				</a>
			</div>
		<?php endif; ?>
		
		<div class="accommodation-content">
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>


			<?php happybeds_accommodation_price(); ?>

			<?php the_excerpt(); ?>

			<?php happybeds_accommodation_button(); ?>
		</div>
	</div>
</article>
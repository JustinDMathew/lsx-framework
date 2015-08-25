<?php
/**
 * The template used for displaying accommodation posts on the accommodation archive
 *
 * @package lsx-tours
 * @subpackage templates
 * @category content
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>  >
	<div class="tour-content-wrapper">	
		<?php if ( '' != get_the_post_thumbnail() ) : ?>
			<div class="tour-thumbnail">
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
		
		<div class="tour-content">
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
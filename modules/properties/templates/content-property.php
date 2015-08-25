<?php
/**
 * The template used for displaying property posts on the property archive
 *
 * @package lsx
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="property-content-wrapper">
		<?php if ( '' != get_the_post_thumbnail() ) : ?>
			<div class="property-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php lsx_thumbnail( 'thumbnail-wide' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="property-content">
			<div class="property-top">
				<?php the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><span>', '</span></a></h3>' ); ?>
				
				<div>
					<span class="property-location">Property Location</span>
				</div>
			</div>

			<div class="price-wrapper">
				<span class="price">
					Price per night from: 				<span class="price-inner">
						<span class="hb_currency_tag" data-base="NAD" data-value="2 300.00" data-convert="NAD"><span class="hb_symbol">N$</span>2 300.00</span>				</span>
				</span>
			</div>

			<p class="property-excerpt">This is where the property excerpt should be</p>

			<div class="property-bottom">
				<span class="property-country"><span class="genericon genericon-location"></span> Country</span>
				<a href="#" class="property-read-more" role="button">Read More</a>
			</div>
		</div>
	</div>
</article>
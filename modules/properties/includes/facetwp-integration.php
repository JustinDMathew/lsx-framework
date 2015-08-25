<?php

/**
 * FacetWP Integration
 *
 * @package   Lsx_Property
 * @license   GPL-2.0+
 */


function lsx_price_sort_options( $options, $params ) {
	$options['pricee_asc'] = array(
        'label' => __( 'Price (Highest)', 'lsx' ),
        'query_args' => array(
            'orderby' => 'from_price',
            'order' => 'DESC',
        )
    );

    $options['price_desc'] = array(
        'label' => __( 'Price (Lowest)', 'lsx' ),
        'query_args' => array(
        	'orderby' => 'from_price',
            'order' => 'ASC',
        )
    );

    return $options;
}

add_filter( 'facetwp_sort_options', 'lsx_price_sort_options', 10, 2 );
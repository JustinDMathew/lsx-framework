<?php
/**
 * LST helper functions
 *
 * @package   Lsx
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 */

/**
 * Register a metabox with LST
 *
 * @since 0.0.1
 *
 */
function lsx_register_metabox( $metabox ){

	$metabox = (array) $metabox;

	if( empty( $metabox['name'] ) ){
		trigger_error( sprintf( __( 'A name is required for your metabox.', 'lsx' ) ) );
	}
	if( empty( $metabox['post_type'] ) ){
		trigger_error( sprintf( __( 'A post_type is required for your metabox.', 'lsx' ) ) );
	}

	$defaults = array(
		'post_type'			=> 	'', // string|array
		'name'				=>	'',
		'section'			=>	'',
		'section_priority'	=>	10,
		'panel'				=>	__( 'General', 'lsx' ),
		'panel_priority'	=>	10,
		'context'			=>	'advanced',
		'priority'			=>	'default',
		'fields'			=>	array()
	);
	$metaboxes = Lsx_Metabox::get_instance();
	$metaboxes->register_metabox( array_merge( $defaults, $metabox ) );
	
}

/**
 * Register a Post Type with LSX
 *
 * @since 0.0.1
 *
 */
function lsx_register_post_type( $type, $args ){
	$post_types = Lsx_post_types::get_instance();
	$post_types->register_post_type( $type, $args );	
}

/**
 * Register a taxonomy with LSX
 *
 * @since 0.0.1
 *
 */
function lsx_register_taxonomy( $taxonomy, $post_type, $args = null ){
	$taxonomies = Lsx_post_types::get_instance();
	$taxonomies->register_taxonomy( $taxonomy, $post_type, $args );	
}
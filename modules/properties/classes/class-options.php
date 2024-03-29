<?php
/**
 * Properties Options.
 *
 * @package   Lsx_Properties
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link
 * @copyright 2015  LightSpeed Team
 */

/**
 * Plugin class.
 * @package Lsx_Properties
 * @author   LightSpeed Team
 */
class Lsx_Properties_Options {


	/**
	 * Get an option from this plugin.
	 *
	 * @since 0.0.1
	 *
	 * @param string $option The name of a specific option to get.
	 * @param mixed $default Optional. Default to return if no value found. Default is false.
	 *
	 * @return string|null|array Returns the option or null if it doesn't exist
	 */
	public static function get ( $option, $default = false ) {
		$option = self::get_options( $option );
		if ( is_array( $option ) && empty( $option ) ) {
			return null;

		}

		if ( is_null( $option ) ) {
			return $default;

		}

		return $option;

	}

	/**
	 * Get all option from this plugin.
	 *
	 * @since 0.0.1
	 *
	 * @return null|array Returns the options or null if none are set
	 */
	public static function get_all (  ) {
		return self::get_options( null );

	}

	/**
	 * Get an option or all option from this plugin
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 *
	 * @param null|string $option Optional. If null, the default, all options for this plugin are returned. Provide the name of a specific option to get just that one.
	 *
	 * @return array|null|string
	 */
	private static function get_options( $option = null ) {
		$options = get_option( "_happybeds_properties", array() );
		if ( empty( $options ) ) {
			return $options;

		}

		if ( ! is_null( $option ) ) {
			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}
			else {
				return null;

			}

		}

		return $options;

	}

}

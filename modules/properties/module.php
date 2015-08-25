<?php
/**
 * @package   Lsx_Properties
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 *
 * @wordpress-plugin
 * Module Name: Properties
 * Description: Properties Post Type
 * Version:     0.0.1
 * Author:      LightSpeed Team
 * Author URI:  https://www.lsdev.biz/
 * Text Domain: happybeds-properties
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_PROPERTIES_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_PROPERTIES_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_PROPERTIES_VER',  '0.0.1' );



// load internals
require_once( LSX_PROPERTIES_PATH . 'classes/class-happybeds-properties.php' );
require_once( LSX_PROPERTIES_PATH . 'classes/class-options.php' );
require_once( LSX_PROPERTIES_PATH . 'classes/class-widget.php' );

require_once( LSX_PROPERTIES_PATH . 'includes/template-tags.php' );
require_once( LSX_PROPERTIES_PATH . 'includes/facetwp-integration.php' );
require_once( LSX_PROPERTIES_PATH . 'includes/metaboxes.php' );

// Load instance
Lsx_Properties::get_instance();


function hbprop_rewrite_flush() {
	Lsx_Properties::get_instance();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'hbprop_rewrite_flush' );
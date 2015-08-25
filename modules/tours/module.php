<?php
/**
 * @package   Lsx_Tours
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 LightSpeedDevelopment
 *
 * @wordpress-plugin
 * Module Name: Tours
 * Description: Tours module
 * Version:     1.0.0
 * Author:      LightSpeed Team
 * Author URI:  https://www.lsdev.biz/
 * Text Domain: lsx-tours
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_TOURS_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_TOURS_CORE',  __FILE__ );
define('LSX_TOURS_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_TOURS_VER',  '1.0.0' );


// load internals
require_once( LSX_TOURS_PATH . 'classes/core.php' );
include_once LSX_TOURS_PATH . 'includes/metaboxes.php';
 
// Load instance
$lsx_tours = Lsx_Tours::get_instance();
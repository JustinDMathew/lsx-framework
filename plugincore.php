<?php
/**
 * @package   Lsx
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 *
 * @wordpress-plugin
 * Plugin Name: LSX
 * Plugin URI:  http://CalderaWP.com
 * Description: LSX Modules manager
 * Version:     0.2
 * Author:      LightSpeed Team
 * Author URI:  https://www.lsdev.biz/
 * Text Domain: lsx
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_VER',  '0.2' );



// load internals
require_once( LSX_PATH . 'classes/class-lsx.php' );
require_once( LSX_PATH . 'classes/class-options.php' );
require_once( LSX_PATH . 'classes/class-settings.php' );
require_once( LSX_PATH . 'classes/class-metabox.php' );
require_once( LSX_PATH . 'classes/class-types.php' );
require_once( LSX_PATH . 'includes/functions.php' );


// Load instance
add_action( 'plugins_loaded', array( 'Lsx', 'get_instance' ) );

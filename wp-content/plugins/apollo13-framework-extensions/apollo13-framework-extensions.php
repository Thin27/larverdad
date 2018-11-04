<?php
/**
 * Plugin Name:			Apollo13 Framework Extensions
 * Plugin URI:			https://apollo13themes.com/rife/free
 * Description:			Adds custom post types, shortcodes and some non theme features that we use in themes build on our Apollo13 Framework
 * Author:				Apollo13Themes
 * Author URI:			https://apollo13themes.com/
 * License:             GPLv2 or later
 * Requires at least:	4.7
 * Tested up to:		4.9
 * Version:				1.5.0
 *
 *
 * Text Domain: a13_framework_cpt
 *
 */

//no double instances
if(defined('A13FE_BASE_DIR')){
	return;
}

define('A13FE_BASE_DIR', dirname(__FILE__).'/');

//"If" will be removed in future updates
if( !defined('A13FRAMEWORK_FILES') ){
	//useful constants
	$upload_dir = wp_upload_dir();
	define('A13FRAMEWORK_FILES', trailingslashit( $upload_dir['baseurl'] ) . 'apollo13_framework_files');
	define('A13FRAMEWORK_FILES_DIR', trailingslashit( $upload_dir['basedir'] ) . 'apollo13_framework_files');

	define('A13FRAMEWORK_IMPORT_SERVER', 'https://api.apollo13.eu/file_sender');
	define( 'A13FRAMEWORK_IMPORTER_TMP_DIR', trailingslashit( $upload_dir['basedir'] ) . 'apollo13_tmp' );

	//generated css file directory
	define('A13FRAMEWORK_GENERATED_CSS', A13FRAMEWORK_FILES . '/css');
	define('A13FRAMEWORK_GENERATED_CSS_DIR', A13FRAMEWORK_FILES_DIR . '/css');
}

//add helpers
require_once A13FE_BASE_DIR.'functions.php';

//load text domain to translate notices
add_action( 'plugins_loaded', 'a13fe_load_plugin_textdomain' );



//register custom post types
require_once A13FE_BASE_DIR.'supports/cpt.php';

//add theme features
require_once A13FE_BASE_DIR.'features/_all.php';

//add theme shortcodes
require_once A13FE_BASE_DIR.'shortcodes/_all.php';

//add theme widgets
require_once A13FE_BASE_DIR.'widgets/_all.php';

//theme settings
require_once A13FE_BASE_DIR.'settings/_all.php';

if( is_admin() ){
	//Design importer
	require_once A13FE_BASE_DIR . 'design_importer/importer.php';
}
//image resizing on the fly
add_action('after_setup_theme', 'a13fe_load_image_resizing_library');


//if WPBakery Page Builder is active add its enhancements
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active('js_composer/js_composer.php') ) {
	require_once A13FE_BASE_DIR.'supports/wpbakery_pb_extensions/extend.php';
}

//if Elementor is active
if ( is_plugin_active( 'elementor/elementor.php' ) ) {
	require_once A13FE_BASE_DIR.'supports/elementor_extensions/extend.php';
}

<?php
/*
Plugin Name: Trakt.tv Wordpress Plugin
Plugin URI: http://www.ljasinski.pl/wordpress-plugins/trakttv-wordpress-widget/
Description: Gets your last seen and rated movies and episodes from the service
Version: 1.2
Author: Studio Multimedialne ljasinski.pl
Author URI: http://www.ljasinski.pl
License: GPL2
*/

/*

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 *
 * @todo: info stylesheet overwrite in dashboard
 * @todo: sync info about my other plugins somehow
 */

// -- path definitions
define('LJPL_TRAKTTV_DIR', plugin_dir_path(__FILE__));
define('LJPL_TRAKTTV_URL', plugin_dir_url(__FILE__));

// -- activation, deactivation and uninstall

register_activation_hook(__FILE__, 'trakttv_activation');
register_deactivation_hook(__FILE__, 'trakttv_deactivation');

function trakttv_activation( ) {
	// -- register uninstaller
	register_uninstall_hook( __FILE__, 'trakttv_uninstall' );
}

function trakttv_deactivation( ) {
	return 1;
}

function trakttv_uninstall( ) {
	//TODO: Clean seen cache
	return 1;
}

// #############################################################################
// ### Options page

include_once ( LJPL_TRAKTTV_DIR . 'includes/dashboard/trakttv-dashboard.php' );

// #############################################################################
// ### Ładujemy własny CSS

add_action( 'wp_print_styles', 'trakttv_widget_add_stylesheet' );
/**
 * Adds custom stylesheet to head section of the page
 */
function trakttv_widget_add_stylesheet( ) {

	$theme_style_file = get_stylesheet_directory() . '/user-trakttv.css';

	if ( file_exists($theme_style_file) ) {
		wp_register_style( 'user-trakttv', $theme_style_file );
		wp_enqueue_style( 'user-trakttv' );
	} elseif(get_option( 'ljpl-trakttv-use-css', 1 ) ) {
		wp_register_style( 'trakttvWidgetStyle', LJPL_TRAKTTV_URL . '/assets/css/trakttv.css' );
		wp_enqueue_style( 'trakttvWidgetStyle' );
	}
}

// #############################################################################
// ### Ładujemy widget

add_action('widgets_init', 'ljpl_trakttv_load_actions_widget');

function ljpl_trakttv_load_actions_widget( ) {
	register_widget ("ljpl_Trakt_Actions_Widget");
}


// #############################################################################
// ### Klasa widgetu - magic starts here

include_once( LJPL_TRAKTTV_DIR . 'includes/widgetclass-trakttv.php' );




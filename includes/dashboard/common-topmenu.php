<?php

// SOURCE: http://wordpress.stackexchange.com/questions/6311/how-to-check-if-an-admin-submenu-already-exists
if(!function_exists('ljpl_admin_menu_defined')) {
	function ljpl_admin_menu_defined( $handle, $sub = false ) {
		if( !is_admin() || (defined('DOING_AJAX') && DOING_AJAX) )
			return false;
		
		global $menu, $submenu;
		$check_menu = $sub ? $submenu : $menu;
		
		if( empty( $check_menu ) )
			return false;
		foreach( $check_menu as $k => $item ){
			if( $sub ) {
				foreach( $item as $sm ) {
					if($handle == $sm[2])
						return true;
				}
			} else {
				if( $handle == $item[2] )
					return true;
			}
		}
		return false;
	}
}

if(!function_exists('ljpl_admin_dashboard')) {
	function ljpl_admin_dashboard() {
		include_once(plugin_dir_path(__FILE__) . '/ljpl-admin-template.php');
	}
}

if(!function_exists('ljpl_admin_menu')) {
	function ljpl_admin_menu() {
		if(!ljpl_admin_menu_defined('ljpl-admin'))
			add_menu_page ( 'LJPL Plugins', 'LJPL', 'manage_options', 'ljpl-admin', 'ljpl_admin_dashboard' );
			add_submenu_page('ljpl-admin','','','manage_options','ljpl-admin','ljpl_admin_dashboard');
	}
}


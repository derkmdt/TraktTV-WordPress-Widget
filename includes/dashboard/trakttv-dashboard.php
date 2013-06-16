<?php

/**
 * ljpl_trakttv_admin()
 * Displays plugin's settings page
 * @return void
 */
function ljpl_trakttv_admin( ) {  
    include_once( LJPL_TRAKTTV_DIR . 'includes/dashboard/trakttv-template.php' );
}  


/**
 * ljpl-trakttv_admin_actions
 *
 * @return void
 */
function ljpl_trakttv_admin_actions( ) {  

	// -- common branded menu LJPL
	include_once( LJPL_TRAKTTV_DIR .  'includes/dashboard/common-topmenu.php' );
	
	// -- submenu for TraktTV Widget 
	add_submenu_page('ljpl-admin', 'TraktTV Plugin Options', 'TraktTV Plugin Options', 'manage_options', 'ljpl-trakttv', 'ljpl_trakttv_admin');
    add_action( 'admin_init', 'ljpl_trakttv_register_options' );
}  
  
add_action('admin_menu', 'ljpl_trakttv_admin_actions');

function ljpl_trakttv_register_options() {
	register_setting( 'ljpl-trakttv-admin', 'ljpl-trakttv-use-css' );
	register_setting( 'ljpl-trakttv-admin', 'ljpl-trakttv-has-private-account' );
	register_setting( 'ljpl-trakttv-admin', 'ljpl-trakttv-login' );
	register_setting( 'ljpl-trakttv-admin', 'ljpl-trakttv-password' );
	register_setting( 'ljpl-trakttv-admin', 'ljpl-trakttv-api-key' );

}

function ljpl_trakttv_get_default_options() {
     $options = array(
          'ljpl-trakttv-use-css' => 1,
          'ljpl-trakttv-has-private-account' => false,
          'ljpl-trakttv-login' => '',
          'ljpl-trakttv-password' => '',
          'ljpl-trakttv-api-key' => ''
     );
     return $options;
}

register_activation_hook(__FILE__,'ljpl_trakt_widgets_activate');

function ljpl_trakt_widgets_activate() {

}

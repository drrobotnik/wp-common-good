<?php 
/*
Plugin Name: WP Common Good
Plugin URI: http://www.caavadesign.com
Description: Common good is an attempt to create a WP knowledge base of common issues within a wordpress site. Part support ticketting, part help desk.
Version: 0.1
Author: Brandon Lavigne
Author URI: http://www.caavadesign.com
*/

/**
 * Copyright (c) 2013 Brandon Lavigne. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
require_once('config.php');
require_once('rpc.php');
require_once('meta_boxes/meta_box.php');


function add_tabs() {
	$tabs = array(
		// The assoc key represents the ID
		// It is NOT allowed to contain spaces

		 'wpcg-inquire' => array(
			 'title' => 'Inquire'
			,'content' => 'Content'
		 )
	);
	foreach ( $tabs as $id => $data ) {
		get_current_screen()->add_help_tab( array(
			 'id'       => $id
			,'title'    => __( $data['title'], 'some_textdomain' )
			// Use the content only if you want to add something
			// static on every help tab. Example: Another title inside the tab
			,'content'  => '<p></p>'
			,'callback' => 'prepare'
		) );
	}
}

function prepare( $screen, $tab ) {
		printf(
		 '<p>%s</p>'
		,__(
				 //$GLOBALS['pagenow']
				 ""
			,'dmb_textdomain'
		 )
	);
		include 'templates/inquire.php';
}

function ajax_settings($post) {
	if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'wp-common-good-settings' ) ) {
		$error = false;
		$refresh = false;
		$data = array();
		$data['title'] = stripslashes( $_POST['title'] );
		$data['issue'] = stripslashes( $_POST['issue'] );
		$custom_fields[] = array('key'=>'userdata','value'=>$_POST['userdata']);
		$custom_fields[] = array('key'=>'serverdata','value'=>$_POST['serverdata']);
		$rpc = new XMLRPClientWordPress(WPCG_SITE_URL.'/xmlrpc.php', WPCG_USER, WPCG_PASSWORD);
		$result = $rpc->create_post($data['title'], $data['issue'],$custom_fields);
		if ( $refresh ) {
			//$result['topics'] = $this->get_help_topics_html( true );
		//} elseif ( !empty( $this->options['slurp_url'] ) ) {
			// It didn't change, but we should trigger an update in the background
			//wp_schedule_single_event( current_time( 'timestamp' ), self::CRON_HOOK );
		}
		die( json_encode( $result ) );
	} else {
		die( '-1' );
	}
}
function global_custom_options() {
?>
	<div class="wrap">
		<h2>WP Common Good Options</h2>

		<p>Success! Now that you've installed our humble plug-in. What type of install is this?</p>
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options');
			$admin_install = empty( get_option('wpcg-install_type') ) ? '' : 'checked="checked"';
			 ?>
			<p><input type="checkbox" name="wpcg-install_type" value="admin" <?php echo $admin_install; ?>> I'm the knowledgebase Admin.</p>
			<p><strong>Twitter ID:</strong><br />
				<input type="text" name="twitterid" size="45" value="<?php echo get_option('twitterid'); ?>" />
			</p>
				<p><strong>Facebook Page Links:</strong><br />
		<input type="text" name="fb_link" size="45" value="<?php echo get_option('fb_link'); ?>" />
	</p>

			<p><input type="submit" name="Submit" value="Store Options" /></p>
			<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="twitterid,fb_link" />
		</form>
	</div>
<?php
}
function add_global_custom_options() {
	if( !get_option( 'wpcg_admin' ) ){
		add_menu_page('WP Good', 'WP Good', 'manage_options', 'functions','global_custom_options','',100);
	}
}

// Always add help tabs during "load-{$GLOBALS['pagenow'}".
// There're some edge cases, as for example on reading options screen, your
// Help Tabs get loaded before the built in tabs. This seems to be a core error.
global $pagenow;
add_action( "load-{$pagenow}", 'add_tabs', 20 );
add_action('admin_menu', 'add_global_custom_options');
add_action( 'wp_ajax_wp_common_good_settings', 'ajax_settings' );
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

require_once('rpc.php');

//$rpc = new XMLRPClientWordPress('http://127.0.0.1/jpatience/xmlrpc.php', 'admin', 'keXeye8B');
//$rpc->create_post('title', 'body', 'category');

class example_help {
	public $tabs = array(
		// The assoc key represents the ID
		// It is NOT allowed to contain spaces

		 'EXAMPLE' => array(
		 	 'title'   => 'TEST ME!'
		 	,'content' => 'Content'
		 )
	);

	static public function init() {
		$class = __CLASS__ ;
		new $class;
	}

	public function __construct() {
		add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_tabs' ), 20 );
	}

	public function add_tabs() {
		foreach ( $this->tabs as $id => $data ) {
			get_current_screen()->add_help_tab( array(
				 'id'       => $id
				,'title'    => __( $data['title'], 'some_textdomain' )
				// Use the content only if you want to add something
				// static on every help tab. Example: Another title inside the tab
				,'content'  => '<p>Some stuff that stays above every help text</p>'
				,'callback' => array( $this, 'prepare' )
			) );
		}
	}

	public function prepare( $screen, $tab ) {
	    	printf(
			 '<p>%s</p>'
			,__(
	    			 //$GLOBALS['pagenow']
	    			 "future home of form."
				,'dmb_textdomain'
			 )
		);
	    	include 'templates/inquire.php';
	}
}
// Always add help tabs during "load-{$GLOBALS['pagenow'}".
// There're some edge cases, as for example on reading options screen, your
// Help Tabs get loaded before the built in tabs. This seems to be a core error.
global $pagenow;
add_action( 'load-'.$pagenow, array( 'example_help', 'init' ) );
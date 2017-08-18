<?php
/*
Plugin Name: A3P Basics
Plugin URI: http://www.a3p.es
Description: Varias utilidades de A3P
Version: 1.1
Author: A3P Interacción Digital S.L.
Author URI: http://www.a3p.es
*/

add_action( 'plugins_loaded', 'a3p_plugins_loaded',1);
function a3p_plugins_loaded()
{	
	$a3p_options = get_option('a3p');			

	if(is_admin())include_once "panel.php";	

	if(isset($a3p_options['modules']['cookies']))		include_once "cookies.php";	
	if(isset($a3p_options['modules']['news']))			include_once "news.php";
	if(isset($a3p_options['modules']['custom_a3p']))	include_once "custom_a3p.php";
	if(isset($a3p_options['modules']['capabilities']))	include_once "capabilities.php";	
	if(isset($a3p_options['modules']['maintenance']))	include_once "maintenance.php";	
	
	load_plugin_textdomain( 'a3p', false, basename( dirname( __FILE__ ) ) . '/languages' );
}


add_action( 'wp_enqueue_scripts', 'load_a3p_basics_frontend_style' );
function load_a3p_basics_frontend_style() {	
	wp_register_style( 'a3p_basics_frontend_css', plugins_url('/css/frontend.css', __FILE__) );                      
	wp_enqueue_style( 'a3p_basics_frontend_css' );	
}

add_action( 'admin_enqueue_scripts', 'load_a3p_basics_admin_style' );
function load_a3p_basics_admin_style() {	
	wp_register_style( 'a3p_basics_admin_css', plugins_url('/css/admin.css', __FILE__) );
	wp_enqueue_style( 'a3p_basics_admin_css' );	
	global $typenow;
	if( $typenow == 'post' ) {
		wp_enqueue_media();         
		wp_register_script( 'a3p-basics-attachment', plugins_url('/js/admin.js', __FILE__) , array( 'jquery' ) );
		wp_localize_script( 'a3p-basics-attachment', 'meta_image',
			array(
				'title' => __( 'Choose or upload a file', 'a3p' ),
				'button' => __( 'Use this file', 'a3p' ),
			)
		);
		wp_enqueue_script( 'a3p-basics-attachment' );
	}

}



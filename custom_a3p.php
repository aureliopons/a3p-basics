<?php

add_action('admin_bar_menu', 'add_to_admin_bar', 99);
function add_to_admin_bar () 
{
	global $wp_admin_bar;

	if(is_admin())
	$wp_admin_bar->add_menu(array(
		'parent' => null,
		'id' => 'a3p_logo',
		'title' => __('View website :','a3p-posts-tracking').' '.get_bloginfo('name'),
		'href' => site_url(),
		'target' => "_blank",
	));
	
	//Remove Howdy Text
    $my_account=$wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy,', '', $my_account->title );
    $wp_admin_bar->add_node( array(
        'id' => 'my-account',
        'title' => $newtitle,
    ) );


	
	$wp_admin_bar->remove_node("site-name");	
	$wp_admin_bar->remove_node("updates");	
	$wp_admin_bar->remove_node("comments");	
	$wp_admin_bar->remove_node("new-content");	
	$wp_admin_bar->remove_node("customize" );
}

add_action('admin_head','a3p_custom_admin_bar_logo');
function a3p_custom_admin_bar_logo() {
	$a3p_options = get_option('a3p');
	if($a3p_options['a3p_bar_logo'])
	 echo '<style type="text/css">
		#wpadminbar {
			background-image:url('  . $a3p_options['a3p_bar_logo'] . ')!important;
			background-position:10px 8px!important;
			background-repeat:no-repeat!important;			
		}		
		#wpadminbar .quicklinks {padding-left:166px!important;}
		</style>';
}

add_filter( 'admin_footer_text', 'a3p_admin_footer_text' );
function a3p_admin_footer_text( $footer_text ) 
{
	$a3p_options = get_option('a3p');
	if($a3p_options['a3p_footer_text'])return $a3p_options['a3p_footer_text'];
	return "";    
}

add_action('login_head', 'change_wp_logo');
function change_wp_logo() {
	$a3p_options = get_option('a3p');
	if($a3p_options['a3p_login_logo'])
	{	
	 $size = getimagesize($a3p_options['a3p_login_logo']);	 	 
	 echo '<style>h1 a { width:'.$size[0].'px!important; background-size: '.$size[0].'px '.$size[1].'px!important;background-image:url("' . $a3p_options['a3p_login_logo']. '")!important; }</style>';
	}
    else 
	 echo '<style>h1 a {display:none!important;}</style>';
}

add_action('widgets_init', 'unregister_default_widgets', 11);
function unregister_default_widgets() {
     //unregister_widget('WP_Widget_Pages');
     //unregister_widget('WP_Widget_Calendar');
	 //unregister_widget('WP_Nav_Menu_Widget');
	 //unregister_widget('WP_Widget_Search');
     //unregister_widget('WP_Widget_Text');
     //unregister_widget('WP_Widget_Categories');
     //unregister_widget('WP_Widget_Recent_Posts');
     unregister_widget('WP_Widget_Archives');
     unregister_widget('WP_Widget_Links');
     unregister_widget('WP_Widget_Meta');
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_RSS');
     unregister_widget('WP_Widget_Tag_Cloud');     
     unregister_widget('Twenty_Eleven_Ephemera_Widget');
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
function remove_dashboard_widgets() {
	global $wp_meta_boxes;
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	update_user_meta( get_current_user_id(), 'show_welcome_panel', false );
	//remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}



 

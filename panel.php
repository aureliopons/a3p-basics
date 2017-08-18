<?php

require_once('include/register-settings-api.php');

add_filter('register_settings_api', 'a3p_basics_config');

function a3p_basics_config( $options_page ) 
{
    $options_page['a3p'] = array(
		'option_name' => 'a3p',		
        'menu_title' => __('A3P Basics','a3p'),
        'page_title' => __('A3P Configuration Basics','a3p'),        
		'before_tabs_text'=> '<h1>'.__('A3P Configuration Basics','a3p').'</h1>',        
        'tabs' => array(
			'general' => array(
                'tab_title' => 'General',
                'fields' => array(
                    'modules' => array(
                        'type' => 'checkbox',
                        'title' => __('Services to activate','a3p'),
						'choices'=> array(
										'cookies'		=>__('Privacy Policy & Cookies Law Info','a3p'),										
										'news'   		=>__('Add Source Taxonomy to Posts','a3p'),										
										'capabilities' 	=>__('Allow editor profile to manage users','a3p'),
										'custom_a3p' 	=>__('Do A3P branding at dashboard','a3p'),										
										'maintenance' 	=>__('Put website in maintenance mode','a3p'),										
									),
						'default'=> array('cookies','lopd'),
                    )
                )
            )            
        )
    );
	$a3p_options = get_option('a3p');	
		
	if(isset($a3p_options['modules']['cookies']))
	 $options_page['a3p']['tabs']['cookies']= array(
                'tab_title' => __('Legal Data','a3p'),
                'fields' => array(                    
					'a3p_cookies_policy_page' => array(
                        'type' => 'select',
                        'title' => __('Legal Page','a3p'),
						'choices'=>a3p_get_pages()
						),
					'a3p_company' => array(
                        'type' => 'text',
                        'title' => __('Company','a3p'),
						),
					'a3p_alias' => array(
                        'type' => 'text',
                        'title' => __('Company Alias','a3p'),
						),
					'a3p_cif' => array(
                        'type' => 'text',
                        'title' => __('CIF/NIF','a3p'),
						),	
					'a3p_mail' => array(
                        'type' => 'text',
                        'title' => __('E-mail','a3p'),
						),					
					'a3p_phone' => array(
                        'type' => 'text',
                        'title' => __('Phone','a3p'),
						),											
					'a3p_address' => array(
                        'type' => 'text',
                        'title' => __('Address','a3p'),
						),					
					'a3p_books' => array(
                        'type' => 'text',
                        'title' => __('Reg.Books','a3p'),
						),					
                ),
            );
			
	
                    
    		
	if(isset($a3p_options['modules']['custom_a3p']))
	 $options_page['a3p']['tabs']['news']= array(
                'tab_title' => 'Branding',
                'fields' => array(
                    'a3p_footer_text' => array(
                        'type' => 'text',
                        'title' => __('Dashboard Footer Text','a3p')
                    ),
					'a3p_login_logo' => array(
                        'type' => 'text',
                        'title' => __('Logo URL','a3p'),
						'description'=> __('Recommended size of 300x100 pixels','a3p'),												
						'default'=>plugins_url('images/logo.png',__FILE__),
                    ),
					'a3p_bar_logo' => array(
                        'type' => 'text',
                        'title' => __('Bar Logo URL','a3p'),
						'description'=> __('Recommended size of 100x32 pixels','a3p'),												
						'default'=>plugins_url('images/bar_logo.png',__FILE__),
                    )
                )
            );			
	
    return $options_page;
} 

function a3p_get_pages(){
	$paginas = array();
	$pags=get_pages(array(
			'sort_order' => 'asc',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'child_of' => 0,
			'parent' => -1,			
			'number' => '',			
			'post_type' => 'page',
			'post_status' => 'publish'
		));
	foreach($pags as $pag)$paginas[$pag->ID]=$pag->post_title;
	return $paginas;		
}

add_filter( 'xmlrpc_enabled', '__return_false' );
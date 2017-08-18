<?php

add_action( 'wp_head', 'a3p_cookie_banner_head', 999 );
function a3p_cookie_banner_head(){
	?>		
	<script type="text/javascript">			
		jQuery(document).ready(function(){				
			jQuery('#a3p-cookie-banner .cookies-close').click(function() {						
				var d = new Date();
				var exdays = 90;
				d.setTime(d.getTime() + (exdays*24*60*60*1000));		
				document.cookie = 'a3p-cookie-banner=1; path=/; expires='+d.toUTCString();											 		
				jQuery('#a3p-cookie-banner').fadeOut(600);					
			});	
		});	
	</script>
	<?php	
}

add_action( 'wp_footer', 'a3p_cookie_banner', 999 );	
function a3p_cookie_banner() 
{		
	if ( isset( $_COOKIE['a3p-cookie-banner'] ) && '1' == $_COOKIE['a3p-cookie-banner'] ) return;
	
	?>

	<div id="a3p-cookie-banner" class="a3p-cookie-banner-wrap">
		<div>			
			<?php echo htmlspecialchars_decode( __('We use own and third party cookies to provide a better experience and service, according to your browsing habits. If you continue browsing, we consider that you accept their use.','a3p')); ?>
			<button type="button" class="cookies-close"><?php _e('Accept','a3p'); ?></button>
			<?php 
				$a3p_options = get_option('a3p');	
				if(isset($a3p_options['a3p_cookies_policy_page'])){
					$url = get_permalink($a3p_options['a3p_cookies_policy_page']);
					if($url)echo sprintf('<a href="%s#cookies">%s</a>',$url,__('More Information','a3p'));
				}
			?>
		</div>
	</div>

	<?php 
}

add_shortcode('legal','a3p_legal');
function a3p_legal()
{
	return a3p_do_template("legal");	
}

add_shortcode('lopd','a3p_lopd');
function a3p_lopd()
{
	return a3p_do_template("lopd");	
}	

add_filter( 'widget_text', 'do_shortcode', 11);
add_shortcode('visitanos','a3p_visitanos');
function a3p_visitanos()
{
	$a3p_options = get_option('a3p');			
	return '<h2>'.$a3p_options['a3p_company'].'</h2><p>'		
		.$a3p_options['a3p_address'].'</p><p>'
		.__('Phone','a3p').': <strong>'.$a3p_options['a3p_phone'].'</strong></p><p>'
		.__('E-mail','a3p').': <strong>'.$a3p_options['a3p_mail'].'</strong></p>';	
}



function a3p_do_template($template)
{
	require_once('include/template_engine.php');
	
	$file = plugin_dir_path( __FILE__ )."/templates/".$template."-".get_locale().".php";
	
	if(!file_exists($file))$file = plugin_dir_path( __FILE__ )."/templates/".$template."-en_EN.php";
	
	$plantilla = new TemplateEngine($file); 
	
	$a3p_options = get_option('a3p');			
		
	$datos=array(		
		'company' =>$a3p_options['a3p_company'],
		'alias'   =>$a3p_options['a3p_alias'  ],
		'address' =>$a3p_options['a3p_address'],
		'cif'     =>$a3p_options['a3p_cif'    ],
		'books'   =>$a3p_options['a3p_books'  ],
		'email'   =>$a3p_options['a3p_mail'   ],
		'phone'	  =>$a3p_options['a3p_phone'  ],
	  );	
	
	return apply_filters('the_content',$plantilla->render($datos));	
}

	
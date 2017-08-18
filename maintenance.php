<?php

if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

add_action("init", "do_maintenance_check");
	
function do_maintenance_check() 
{
	global $pagenow;
	if (!is_user_logged_in() && $pagenow != 'wp-login.php' && !is_admin() ) {
	
	?><!DOCTYPE html>
	<html>	
		<head>
		<title><?php echo get_bloginfo( 'title' );?></title>
			<meta http-equiv="content-type" content="text/html; charset=utf8" />
			<style>						
				body{
					 overflow:hidden;
					 margin:150px;
					 font-family: verdana, arial, helvetica, sans-serif;					 					 
					 text-align:center;					 
					 background:#f6f6f6;
					}
				a {color:#333;font-weight:bold;text-decoration:none}
				a:hover {color:#333;font-weight:bold;text-decoration:none}														
		   </style>
		</head>
		<body>	
		<?php
			
			$a3p_options = get_option('a3p');
			if($a3p_options['a3p_login_logo']){
				echo "<p><img src='".$a3p_options['a3p_login_logo']."'></p>";				
			}			
			echo "<h1>".get_bloginfo( 'title' )."</h1>";
			echo "<h3>Estamos realizando algunos ajustes en nuestra web, por favor visítenos en una hora. Gracias</h3>";			
			$datos = array();
			if($a3p_options['a3p_mail'])
				$datos[]=__('E-mail','a3p').': <a href="mailto:'.$a3p_options['a3p_mail'].'">'.$a3p_options['a3p_mail'].'</a>';
			if($a3p_options['a3p_phone'])
				$datos[]=__('Phone','a3p').': <strong>'.$a3p_options['a3p_phone'].'</strong>';
			if(count($datos))echo "<p>".implode(" - ",$datos)."</p>";
			?>			
		</body>
	</html>		
	<?php
	exit;
	}
}	

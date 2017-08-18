<?php 

add_action('admin_notices', 'showAdminMessages');

function showAdminMessages()
{
	$required_plugins = array(
		
		array(
			'path' => 'taxonomy-widget/taxonomy-widget.php',
			'name' => 'Taxonomy Widget',
			'url'  => 'http://wordpress.org/plugins/taxonomy-widget/',
		),	
		array(
			'path' => 'ajaxify-comments/ajaxify-comments.php',
			'name' => 'Ajaxify Comments',
			'url'  => 'http://wordpress.org/plugins/ajaxify-comments/',
		),
/*		
		array(
			'path' => 'wp-simile-timeline/timeline.php',
			'name' => 'Simile Timeline',
			'url'  => 'http://wordpress.org/plugins/wp-simile-timeline/',
		),	
*/		
	);
	
	$optional_plugins = array(
		array(
			'path' => 'bulkpress/bulkpress.php',
			'name' => 'BulkPress',
			'url'  => 'http://wordpress.org/plugins/bulkpress/',
		),
		array(
			'path' => 'bulkpress-export/bulkpress-export.php',
			'name' => 'BulkPress Export',
			'url'  => 'http://wordpress.org/plugins/bulkpress-export/',
		),	
		array(
			'name' => 'Easy Filter',
			'path' => 'easy-filter/ez-filter.php',		
			'url'  => 'http://wordpress.org/plugins/easy-filter/',
		),		
	);
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	$plugin_messages = array();
	
	foreach ($required_plugins as $plugin)
	 if(!is_plugin_active( $plugin['path'] ))
		$plugin_messages[] = __('This system requires you to install','ainia') . ' <a target="_blank" href="'.$plugin['url'].'">'.$plugin['name'].'</a>';

	if(count($plugin_messages) > 0)
	{
		echo '<div id="message" class="error">';

			foreach($plugin_messages as $message)
			{
				echo '<p><strong>'.$message.'</strong></p>';
			}

		echo '</div>';
	}

	$plugin_messages = array();
	
	foreach ($optional_plugins as $plugin)
	 if(!is_plugin_active( $plugin['path'] ))
		$plugin_messages[] = __('It is recommended you to install','ainia') . ' <a target="_blank" href="'.$plugin['url'].'">'.$plugin['name'].'</a>';

	if(count($plugin_messages) > 0)
	{
		echo '<div id="message" class="updated">';

			foreach($plugin_messages as $message)
			{
				echo '<p><strong>'.$message.'</strong></p>';
			}

		echo '</div>';
	}
}
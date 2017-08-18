<?php

//--------------------- CREAR LA TAXONOMIA -------------------------------------------------------------

// http://shibashake.com/wordpress-theme/custom-post-type-permalinks-part-2 

add_action("init","iniciar_taxonomia_source");
function iniciar_taxonomia_source()
{
		register_taxonomy('source', array('post'), array(
			  'public'      =>true,
			  'labels'      =>array(
				  'name'                        =>__('Source','a3p'),					
				  'menu_name'                   =>__('Sources','a3p'),
				  'singular_name'               =>__('Source','a3p'),								
				  'search_items'                =>__('Search by Source','a3p'),
				  'popular_items'               =>__('Popular Sources','a3p'),
				  'all_items'                   =>__('All Sources','a3p'),
				  'edit_item'                   =>__('Edit Source','a3p'),
				  'update_item'             	=>__('Update Source','a3p'),
				  'add_new_item'                =>__('Add Source','a3p'),
				  'new_item_name'               =>__('New Source Name','a3p'),
				  'separate_items_with_commas'	=>__('Separate sources with commas','a3p'),
				  'add_or_remove_items'     	=>__('Add or remove sources','a3p'),
				  'choose_from_most_used'       =>__('Choose from most used source','a3p'),
			  ),
			  'hierarchical' =>false,			  
			  'rewrite'     =>array(
				  'with_front'              	=>true,
				  'slug'                    	=>'source',
			  ),
			  
		));		
}

add_action( 'admin_menu' , 'remove_source_meta' );
function remove_source_meta() {
	remove_meta_box( 'tagsdiv-source', 'post', 'side' );
}



//--------------------- AÑADIR CAMPOS A LA TAXONOMIA -------------------------------------------------------------

add_action('source_add_form_fields', 'source_metabox_add', 10, 1);
function source_metabox_add($tag) { 
?>	
    <div class="form-field">
        <label for="source_url"><?php _e('URL','a3p'); ?></label>
        <input name="source_url" id="source_url" type="text" value="" size="40" aria-required="true" />
    </div>    
<?php }   

function get_source_url($term_id)
{
	return get_option('source_url_'.$term_id);	
}  

add_action('source_edit_form_fields', 'source_metabox_edit', 10, 1);    
function source_metabox_edit($tag) { 
	$url = get_source_url($tag->term_id);
?>
	<table class="form-table">
        <tr class="form-field">
        <th scope="row" valign="top">
            <label for="source_url"><?php _e('URL','a3p'); ?></label>
        </th>
        <td>
            <input name="source_url" id="source_url" type="text" value="<?php echo $url;?>" size="40" aria-required="true" />
			<?php if($url)echo '<br/> &nbsp; <a href="'.$url.'" target="_blank">'.__('View','a3p').'</a>';?>
        </td>
        </tr>				
    </table>
<?php }

add_action('created_source', 'save_source_metadata', 10, 1);    
add_action('edited_source', 'save_source_metadata', 10, 1);
function save_source_metadata($term_id)
{
	if (!$term_id)return;	    
	if ( isset( $_POST['source_url'] ) ) 
		update_option( 'source_url_'.$term_id, $_POST['source_url'] );		
}

//------- AÑADIR COLUMNAS AL POST -------------------------

add_filter('manage_edit-post_columns', 'a3p_news_register_post_columns');
function a3p_news_register_post_columns($columns){  
  unset($columns['tags']);
  $columns['source'] = __('Source','a3p');
  $columns['attachment']  = __('Attachment','a3p');
  $columns = array_merge(
					array_slice($columns, 0, 2), 
					array('thumbnail'=>__('Thumbnail','a3p')),
					array_slice($columns, 2)
					); 
  return $columns;
}

add_action('manage_posts_custom_column', 'a3p_news_handle_post_columns');
function a3p_news_handle_post_columns( $column ){
  
  global $post;
  if( !$post || $post->post_type != 'post' ) return;
  
  switch($column){
	case 'thumbnail': echo get_the_post_thumbnail( $post->ID, array(60,60) );
					  break;
					
	case 'attachment': echo get_post_meta( $post->ID, 'article_attachment', true );
					   break;
					
	case 'source':  $sources = wp_get_post_terms( $post->ID, 'source', true );	
					if(count($sources)){
						$url_articulo = get_post_meta($post->ID,'article_url',true);
						$url_source = get_option( 'source_url_'.$sources[0]->term_id);					
						if($url_articulo)
							 echo '<a href="'.$url_articulo.'" target="_blank">'.$sources[0]->name.'</a>';
						else if($url_source)
							 echo '<a href="'.$url_source.'" target="_blank">'.$sources[0]->name.'</a>';
						else echo $sources[0]->name;
					}
					break;
  
  }  
}

add_filter('manage_edit-post_sortable_columns', 'a3p_news_register_posts_sortable_columns');
function a3p_news_register_posts_sortable_columns( $columns ){
  $columns['source'] = 'source';  
  return $columns;
}


//------------- EDITAR METABOXES DEL TIPO POST PARA AÑADIR LA FUENTE ---------------------------------------------------------------

add_action('do_meta_boxes','my_post_remove_meta_box',1);  
function my_post_remove_meta_box()
{  	
	remove_meta_box('slugdiv'          			, 'post', 'normal' );
	remove_meta_box('postcustom'        		, 'post', 'normal');		
	remove_meta_box('trackbacksdiv'     		, 'post', 'normal');
}


add_action( 'add_meta_boxes', 'add_post_source_metabox_event' );
function add_post_source_metabox_event()
{
	global $post;
	if( !$post || $post->post_type != 'post' ) return;
	
	add_meta_box( 'post-source-metabox-id', 'Fuente', 'add_post_source_metabox', 'post', 'normal', 'high' );
}

function add_post_source_metabox( $post )
{
	$sources_posts = get_the_terms( $post->ID, 'source');
	$id_source = count($sources_posts) ? $sources_posts[0]->term_id : 0;
	
	$article_attachment = get_post_meta( $post->ID, 'article_attachment', true );
	$article_url = get_post_meta( $post->ID, 'article_url', true );
	$sources = get_terms("source", array('hide_empty'=>false));
	wp_nonce_field( 'my_post_source_metabox_nonce', 'my_post_source_metabox_nonce' );
	?>
	<p>
		<label for="article_source"><?php _e('Source','a3p');?></label>
		<select name="article_source" id="article_source">
			<option value=""><?php _e('- Select Source -','');?></option>
		<?php foreach($sources as $source){?>
			<option value="<?php echo $source->term_id?>" <?php selected( $source->term_id, $id_source ); ?>><?php echo $source->name?></option>
		<?php }?>			
		</select>
	</p>	
	<p>
		<label for="article_url"><?php _e('Original Article URL','a3p');?></label>
		<input type="url" name="article_url" id="article_url" size="100" value="<?php echo $article_url; ?>" />
		<?php if($article_url) echo " <a href='".$article_url."' target='_blank'>".__("View",'a3p')."</a>"; ?>
	</p>
	<p>
		<label for="article_attachment"><?php _e( 'Attachment Upload', 'a3p' )?></label>
		<input type="url" name="article_attachment" id="article_attachment" size="100" value="<?php if ( isset ( $article_attachment ) ) echo $article_attachment; ?>" />
		<input type="button" id="article_attachment_button" class="button" value="<?php _e( 'Choose or upload a file', 'a3p' )?>" />
		<?php if($article_attachment) echo " <a href='".$article_attachment."' target='_blank'>".__("Download",'a3p')."</a>"; ?>
	</p>	
	<?php	
}


add_action( 'save_post', 'save_post_source_metabox' );
function save_post_source_metabox( $post_id )
{
	
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['my_post_source_metabox_nonce'] ) || !wp_verify_nonce( $_POST['my_post_source_metabox_nonce'], 'my_post_source_metabox_nonce' ) ) return;
		
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;	
	
	
	if( isset( $_POST['article_source'] ) )
		wp_set_object_terms( $post_id, intval( $_POST['article_source'] ) , 'source', false);
	
		
	if( isset( $_POST['article_url'] ) )
		update_post_meta( $post_id, 'article_url', esc_attr( $_POST['article_url'] ) );
		
	if( isset( $_POST['article_attachment'] ) )
		update_post_meta( $post_id, 'article_attachment', esc_attr( $_POST['article_attachment'] ) );

}

//---------------------- filtro para buscar los botones --------------------------------

add_filter( 'the_content', 'a3p_news_content_filter' );
function a3p_news_content_filter($content) {
  
  global $post;
  if( !$post ||  $post->post_type != 'post' || !is_single($post) ) return $content;
  
  $article_url =get_post_meta($post->ID,'article_url',true);
  $terms=wp_get_post_terms($post->ID,"source");
  if(count($terms))
       $prefix = '<p class="source">'.__( 'Source', 'a3p' ).
			     ': <a target="_blank" href="'.($article_url ? $article_url:get_term_link($terms[0],'source')).'">'.$terms[0]->name.'</a></p>';
			  
  else $prefix = '<p class="source">'.__( 'Source', 'a3p' ).
			     ': <a target="_blank" href="'.$article_url.'">'.parse_url($article_url, PHP_URL_HOST).'</a></p>';
	  
  
			  

  $sufix='';			  
  $article_attachment =get_post_meta($post->ID,'article_attachment',true);
  if($article_attachment)
	  $sufix .= sprintf('<a href="%s" target="blank" class="download_source">%s</a>',
					$article_attachment,
					__('Download','a3p'));					
	    
  //$sufix .= show_post_attachments($post);

  return $prefix . $content . $sufix;			  
}


function show_post_attachments($post) 
{
	$args = array( 'post_type' 		=> 'attachment', 
				   'post_status' 	=> null, 
				   'post_parent' 	=> $post->ID, 
				   'numberposts' 	=> '999', 
				   'post_mime_type' => array( 'application/pdf','application/vnd.ms-excel','application/msword', 'application/x-gzip', 'application/zip' )
				   ); 
	
	$attachments = get_posts( $args );
	
	if ( $attachments ) :
		
		$download = '<h4>'.__('Downloads','a3p').'</h4> <ul class="downloads">';
		
		foreach ( $attachments as $attachment ) :
				
				setup_postdata($attachment);
				
				// SETUP THE ATTACHMENT ICON
				$attachment_icon = get_post_mime_type( $attachment->ID );
				$attachment_icon = explode( '/',$attachment_icon );
				$attachment_icon = plugins_url('/images/'.$attachment_icon[1].'.png', __FILE__);							
				$attachment_icon = '<img style="margin:0;vertical-align:middle;" src="' . $attachment_icon . '" alt="' . get_the_title($attachment->ID) . '" title="' . get_the_title($attachment->ID) . '" />';
			
				// MAKE THE ATTACHMENT LIST ITEM
				$download .= '<li><a href="' . wp_get_attachment_url($attachment->ID) . 
							 '" target="_blank">' . $attachment_icon . get_the_title($attachment->ID) . '</a></li>';
				
				wp_reset_postdata();
		endforeach;
		
		$download .= '</ul><div class="clear"></div>';
				
		return $download;
		
	endif;		
	
	return '';
	
}



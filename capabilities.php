<?php

  // Un usuario que no es admin no puede crear un admin
  function ainia_editable_roles( $roles ){
    if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
      unset( $roles['administrator']);
    }
    return $roles;
  }

  // Un usuario que no es admin no puede borrar o editar a un admin
  function ainia_map_meta_cap( $caps, $cap, $user_id, $args ){

    switch( $cap ){
        case 'edit_user':
        case 'remove_user':
        case 'promote_user':
            if( isset($args[0]) && $args[0] == $user_id )
                break;
            elseif( !isset($args[0]) )
                $caps[] = 'do_not_allow';
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        case 'delete_user':
        case 'delete_users':
            if( !isset($args[0]) )
                break;
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        default:
            break;
    }
    return $caps;
  }


add_action( 'admin_init', 'controlar_roles');
function controlar_roles() 
{
	$role_object = get_role( 'editor' );
	$role_object->add_cap( 'list_users' );
	$role_object->add_cap( 'edit_users' );
	$role_object->add_cap( 'remove_users' );
	$role_object->add_cap( 'delete_users' );
	$role_object->add_cap( 'create_users' );
	$role_object->add_cap( 'add_users' );
	$role_object->add_cap( 'promote_users' );
	$role_object->add_cap( 'manage_categories' );
	
	
	$role_object = get_role( 'subscriber' );
	$role_object->add_cap( 'manage_categories' );
	
	add_filter( 'editable_roles','ainia_editable_roles');
    add_filter( 'map_meta_cap', 'ainia_map_meta_cap',10,4);
}


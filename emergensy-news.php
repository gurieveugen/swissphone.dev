<?php
$caps = get_role('subscriber');
$caps = $caps->capabilities;
$caps['manage_emergency'] = true;
add_role( 'emergencyeditor' , 'Emergency Editor', $caps );
global $wp_roles;
$wp_roles->add_cap('editor','manage_emergency');
$wp_roles->add_cap('administrator','manage_emergency');

add_action( 'init', 'emergency_registration' );

function emergency_registration() {
    
   $labels = array(
    'name'          => __('Emergency News', 'SwissPhone'),
    'singular_name' => __('Emergency News', 'SwissPhone'),
    'add_new'       => __('Add News', 'SwissPhone'),
    'add_new_item'  => __('Add News', 'SwissPhone'),
    'edit_item'     => __('Edit News', 'SwissPhone'),
    'new_item'      => __('Add News', 'SwissPhone'),
    'view_item'     => __('View News', 'SwissPhone'),
    'search_items'  => __('Search News', 'SwissPhone'),
    'not_found'     => __('No Emergency News', 'SwissPhone'),
    'not_found_in_trash'    => __('No News found in Trash', 'SwissPhone'), 
    'parent_item_colon'     => '',
    'menu_name'     => 'Emergency News'
   );
    
   $args = array(
    'labels'        => $labels,
    'public'        => true,
    'publicly_queryable' => true,
    'show_ui'       => true, 
    'show_in_menu'  => true, 
    'query_var'     => true,
    'rewrite'       => true,
    'capability_type' => 'post',
    'capabilities'  => array(
            'edit_post' => 'manage_emergency', 
            'read_post' => 'read_post', 
            'edit_posts'=> 'manage_emergency',
            'edit_others_posts' => 'manage_emergency',
            'delete_post' => 'manage_emergency',
            'publish_posts' => 'manage_emergency',
            'read_private_posts' => 'manage_emergency',
    ),
    'has_archive'   => true, 
    'hierarchical'  => true,
    'menu_position' => null,
    'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
  );
  
  register_post_type( 'emergency_news', $args); 
    
}

add_action( 'save_post', 'emergency_save_post' );
add_action( 'delete_post', 'emergency_delete_post' );
add_action( 'trash_post', 'emergency_delete_post' );

function emergency_save_post( $entry_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $entry_id;
    if ( $_POST['post_type'] != 'emergency_news' ) return $entry_id;
    purge_all_c_cache();
}

function emergency_delete_post( $pid ) {
    if ( $ps = get_post( $pid ) and $ps->post_type == 'emergency_news' ) {
        purge_all_c_cache();
    } 
}

function available_emergency_news () {
    global $GEO_location;
    if ( 'switzerland' != $GEO_location['term']->slug ) return false;
    return get_posts( array('post_type' => 'emergency_news' ) );
}

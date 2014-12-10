<?php
add_action( 'init', 'custom_pages_registration' );

/*add_action('init', 'cpages_rewrite');
function cpages_rewrite() {
    global $wp_rewrite;
    add_rewrite_rule('about/(.*?)([^/]+)/?$', 'index.php?custom_pages=$matches[2]', 'top');
    //$wp_rewrite->flush_rules(); // !!!
}
*/
function custom_pages_registration() {
 /*   
   $labels = array(
    'name'          => __('Custom Pages', 'SwissPhone'),
    'singular_name' => __('Custom Page', 'SwissPhone'),
    'add_new'       => __('Add New', 'SwissPhone'),
    'add_new_item'  => __('Add New Page', 'SwissPhone'),
    'edit_item'     => __('Edit Pages', 'SwissPhone'),
    'new_item'      => __('New Page', 'SwissPhone'),
    'view_item'     => __('View Page', 'SwissPhone'),
    'search_items'  => __('Search Pagess', 'SwissPhone'),
    'not_found'     => __('Pages not found', 'SwissPhone'),
    'not_found_in_trash'    => __('No Pages in Trash', 'SwissPhone'), 
    'parent_item_colon'     => '',
    'menu_name'     => 'About Us'
   );
    
   $args = array(
    'labels'        => $labels,
    'public'        => true,
    'publicly_queryable' => true,
    'show_ui'       => true, 
    'show_in_menu'  => true, 
    'query_var'     => true,
  //  'rewrite'       => array( 'slug' => '', 'with_front' => false ),
    'rewrite'      => true,
    'capability_type' => 'page',
    'has_archive'   => false, 
    'hierarchical'  => true,
    'menu_position' => null,
    'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' )
  );
  
  register_post_type( 'custom_pages', $args );
  //flush_rewrite_rules();*/
}

function is_custom_page_available() {
    
}

function get_root_about_page() {
    $pg = get_posts(array(
        'post_type' => 'custom_pages',
        'post_parent' => 0,
        'country' => get_geo_slug()
    ));
    return array_shift($pg);
}

function custom_page_sidebar() {
    
    if ( ! is_singular( 'custom_pages' ) ) return false;
    $about = get_root_about_page();
    ?>
    <div class="widget-block left-menu">
      <h2><span><?php echo $about->post_title; ?></span></h2>
          <ul class="category_autoexpand">
              <?php 
                $pgs = get_posts(array(
                    'post_type' => 'custom_pages',
                    'post_parent' => $about->ID,
                ));
                foreach ( $pgs as $pg ) {
                    $subs =  $pgs = get_posts(array(
                        'post_type'     => 'custom_pages',
                        'post_parent'   => $pg->ID,
                    ));
                    $list = '';
                    $active = is_single($pg->ID);
                    foreach( $subs as $sub ) {
                        $active |= $act = is_single($sub->ID);
                        $list .= '<li '.($act?'class="active"':''). '><a href="' . get_permalink($sub->ID) . '" >' . $sub->post_title . '</a></li>';
                    }

                    echo '<li '.($active?'class="parent extended"':'').'><a href="',get_permalink($pg->ID),'" >',$pg->post_title,'</a>',(
                        $list
                        ? ( '<ul '.($active?'':'style="display: none;"').">$list</ul>")
                        : ''
                    ), '</li>'; 
                }
              ?>
          </ul>
      </h2>
    </div>
    <?php
}


/* add_action( 'save_post', 'save_custom_pages' );
function emergency_save_post( $entry_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $entry_id;
} */


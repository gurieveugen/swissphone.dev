<?php
add_action('quick_edit_custom_box',  'aiosp_add_quick_edit', 10, 2);
 
function aiosp_add_quick_edit($column_name, $post_type) {
    if ( ! in_array($column_name, array( 'seokeywords', 'seotitle', 'seodesc' ) ) ) return;
    ?>
    <br style="clear: both;" />
    <fieldset class="inline-edit-col-left">
    <div class="inline-edit-col">
    <label>
        <?php 
            switch( $column_name ) {
                case 'seotitle': 
                    ?>
                    <span class="title">SEO Title</span>
                    <span class="input-text-wrap"><input type="text" name="t_aiosp_title" id="t_aiosp_title" value="" /></span>
                    <?php   
                    break;
                case 'seokeywords':
                    ?>
                    <span class="title">SEO Description</span>
                    <span class="input-text-wrap"><textarea name="t_aiosp_description" id="t_aiosp_description" ></textarea></span>
                    <?php
                    break;
                    
                case 'seodesc':
                    ?>
                    <span class="title">SEO Keywords</span>
                    <span class="input-text-wrap"><textarea name="t_aiosp_keywords" id="t_aiosp_keywords" ></textarea></span>
                    <?php
                    break;
            } 
        ?>
    </label>
    </div>
    </fieldset>
    <?php
}

add_action('save_post', 'aiosp_save_quick_edit_data');
 
function aiosp_save_quick_edit_data($post_id) {
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;    
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }   
    if ( isset($_POST['t_aiosp_title']) ) update_post_meta( $post_id, '_aioseop_title', stripslashes($_POST['t_aiosp_title']));    
    if ( isset($_POST['t_aiosp_description']) ) update_post_meta( $post_id, '_aioseop_description', stripslashes($_POST['t_aiosp_description']));    
    if ( isset($_POST['t_aiosp_keywords']) ) update_post_meta( $post_id, '_aioseop_keywords', stripslashes($_POST['t_aiosp_keywords']));    
}



// Add to our admin_init function
add_action('admin_footer', 'aiosp_quick_edit_javascript');
 
function aiosp_quick_edit_javascript() {
    global $current_screen;
    if (($current_screen->id != 'edit-post') || ($current_screen->post_type != 'post')) return; 
    ?>
    <script type="text/javascript">
    function set_inline_aiosdata( ttl, desc, key ) {
        inlineEditPost.revert();
        jQuery('#t_aiosp_title').val(ttl);
        jQuery('#t_aiosp_description').html(desc);
        jQuery('#t_aiosp_keywords').html(key);
    }
    </script>
    <?php
}

// Add to our admin_init function
add_filter('post_row_actions', 'aiosp_expand_quick_edit_link', 10, 2);
 
function aiosp_expand_quick_edit_link($actions, $post) {
    global $current_screen;
    if (($current_screen->id != 'edit-post') || ($current_screen->post_type != 'post')) return $actions; 
 
    $ttl = get_post_meta( $post->ID, '_aioseop_title', true);    
    $dsc = get_post_meta( $post->ID, '_aioseop_description', true);    
    $key = get_post_meta( $post->ID, '_aioseop_keywords', true);   
    $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';
    $actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline' ) ) . '" ';
    $actions['inline hide-if-no-js'] .= " onclick=\"set_inline_aiosdata('".esc_js($ttl)."', '".esc_js($dsc)."', '".esc_js($key)."' ); \">"; 
    $actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
    $actions['inline hide-if-no-js'] .= '</a>';
    return $actions;    
}
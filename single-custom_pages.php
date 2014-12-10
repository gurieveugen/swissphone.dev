<?php
/**
 * @package WordPress
 * @subpackage SwissPhone
 */
 the_post();
 global $post;
 $terms = get_the_terms( $post->ID, 'country' );
 $avail = false;
 $slugs = get_geo_slug_array();
 foreach( $terms as $term ) {
     if ( in_array( $term->slug, $slugs ) ) {
         $avail = true;
         break;
     }
 }
 if ( ! $avail ) {
     wp_redirect(home_url());
     die();
 }
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
      <div class="left">
        <?php custom_page_sidebar(); ?>
      </div>
      
      <div class="right">
          <div id="post-<?php the_ID(); ?>" class="post">
            <h1><?php the_title(); ?></h1>
            <div class="entry-content">
              <div class="top-left-bg">
                <div class="top-right-bg">
                  <div class="list-style">
                        <?php the_content(); ?>
                        <?php edit_post_link('Edit', '<span class="edit-link">', '</span>' ); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="bot-bg-page">&nbsp;</div>
    </div>
    
<?php get_footer(); ?>

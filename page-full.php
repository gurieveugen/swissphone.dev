<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Full Page
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>" class="post full-page">
	    <h1><?php the_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">Pages:', 'after' => '</div>' ) ); ?>
					<?php edit_post_link('Edit', '<span class="edit-link">', '</span>' ); ?>
		    </div>
		  </div>	 
		</div>
<?php endif; ?>
	
<?php get_footer(); ?>

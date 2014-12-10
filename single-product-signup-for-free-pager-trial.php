<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>

	<div class="crumbs-block"><?php show_bread_crumbs() ?></div>
	<?php if ( have_posts() ) : the_post(); ?>

	  <div id="signup-for-free-pager-trial" class="landing-post two-variant">
	    <h1><?php the_title(); ?></h1>
		  <div class="landing-page cf">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">Pages:', 'after' => '</div>' ) ); ?>
				<?php edit_post_link('Edit', '<span class="edit-link">', '</span>' ); ?>
		  </div>
	<?php endif; ?>
	
<?php get_footer(); ?>

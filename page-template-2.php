<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Page Template 2
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
		<?php dynamic_sidebar('pages-sidebar2'); ?>
	  </div>
	  
	  <div class="right">

<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>" class="post">
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
	  </div>

<?php endif; ?>

	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); ?>

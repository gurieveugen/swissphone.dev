<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Blog Page
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php include(TEMPLATEPATH . '/sidebar-blog.php'); ?>	
	  </div>
	  
	  <div class="right">
<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>" class="top-blog">
	    <h1>Most Recent Blog Posts</h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			    <li>
				    <h2><a href="#">News title goes here in this place holder.</a></h2>
					<p>News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place. News post description goes here in this place.</p>
				</li>
			    <li>
				    <h2><a href="#">News title goes here in this place holder.</a></h2>
					<p>News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place. News post description goes here in this place.</p>
				</li>
			    <li>
				    <h2><a href="#">News title goes here in this place holder.</a></h2>
					<p>News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place. News post description goes here in this place.</p>
				</li>
			    <li>
				    <h2><a href="#">News title goes here in this place holder.</a></h2>
					<p>News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place. News post description goes here in this place.</p>
				</li>
			    <li>
				    <h2><a href="#">News title goes here in this place holder.</a></h2>
					<p>News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place holder. News post description goes here in this place. News post description goes here in this place.</p>
				</li>
			  </ul>
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

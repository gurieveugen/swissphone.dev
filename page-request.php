<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Request Page
 */
wp_redirect(home_url('/contact-us/?subject=consultation'));
die();
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php include(TEMPLATEPATH . '/sidebar-contact.php'); ?>	
	  </div>
	  
	  <div class="right">
<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content contact-page">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <?php the_content(); ?>
			  <form action=" " class="contact-form cf">
			    <p><label>Your Name:</label><input type="text" /></p>
				<p><label>Email:</label><input type="text" /></p>
				<p><label>Phone Number:</label><input type="text" /></p>
				<p><label>Company:</label><input type="text" /></p>
				<p><label>Best Time To call:</label>
					<select class="styled">
					  <option>Please Select</option>
					  <option>Please Select</option>
					  <option>Please Select</option>
					  <option>Please Select</option>
					</select></p>
				<p><label>Interested In:</label>
					<select class="styled">
					  <option>Please Select</option>
					  <option>Please Select</option>
					  <option>Please Select</option>
					  <option>Please Select</option>
					</select></p>
				<p><label>How Did You Hear About Us</label>
					<select class="styled">
					  <option>Please Select</option>
					  <option>Please Select</option>
					  <option>Please Select</option>
					  <option>Please Select</option>
					</select></p>
				 <div class="sub-input"><input type="submit" value="submit form" /></div>
			  </form>
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

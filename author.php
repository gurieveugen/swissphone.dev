<?php
/**
 *
 * @package WordPress
 * @subpackage Base_theme
 */
?>
<?php get_header(); ?>

    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php include(TEMPLATEPATH . '/sidebar-news.php'); ?>	
	  </div>
	  
	  <div class="right">

			<?php global $post; setup_postdata($post); ?>

			<h1>Author Archives: <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) )?> " title="<?php echo esc_attr( get_the_author() ) ?>" rel='me'><?php the_author() ?></a></h1>

		<?php if ( get_the_author_meta( 'description' ) ) : ?>
			<div id="entry-author-info">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
				</div>
				<div id="author-description">
					<h2>About <?php the_author() ?></h2>
					<?php the_author_meta( 'description' ); ?>
				</div>
			</div>
		<?php endif; ?>

			<?php include('loop.php'); ?>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>

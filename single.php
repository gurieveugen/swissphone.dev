<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>

    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php if (is_singular('news')) {
	get_sidebar('news');
} else {
	get_sidebar('blog');	
}?>	
	  </div>
	  
	  <div class="right">

<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>" class="post">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <small><?php the_date('d.m.Y') ?></small>
			  <?php the_content(); ?>
			  <?php wp_link_pages( array( 'before' => '<div class="page-link"> Pages:', 'after' => '</div>' ) ); ?>

				<?php if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ),  60  ); ?>
						</div>
						<div id="author-description">
							<h2>About <?php the_author() ?></h2>
							<?php the_author_meta( 'description' ); ?>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									View all posts by <?php the_author() ?> <span class="meta-nav">&rarr;</span>
								</a>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php comments_template( '', true ); ?>
			</div>
		  </div>
		</div>
	  </div>
<?php endif; ?>

	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>

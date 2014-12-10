<?php
/**
 * @package WordPress
 * @subpackage SwissPhone
 */

 global $posts;
 $PT = '';
 if ( is_home() ) {
 	$PT = __('Most recent posts', 'SwissPhone');
 } elseif (is_category()) {
 	$PT = __('Post Category "', 'SwissPhone') . single_cat_title( '' , false) . '"';
 } elseif (is_tag()) {
 	$PT = __('Posts Tagged "', 'SwissPhone') . single_tag_title( '' , false) . '"';
 } elseif( is_day() ) {
 	$PT = __('Daily Archive ', 'SwissPhone') . strftime( ' %B %e %Y', strtotime($posts[0]->post_date) );
 } elseif( is_month() ) {
 	$PT = __('Monthly Archive', 'SwissPhone') . strftime( ' %B %Y', strtotime($posts[0]->post_date) );
 } elseif( is_year() ) {
 	$PT = __('Yearly Archive', 'SwissPhone') . strftime( ' %Y', strtotime($posts[0]->post_date) );
 } else {
 	$PT = __('Posts Archive', 'SwissPhone');
 }
?>
<?php if ( ! have_posts() ) : ?>

	<div id="post-0" class="error404 not-found">
		<h1><?php _e('Not Found', 'SwissPhone'); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			<p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'SwissPhone'); ?></p>
			<?php get_search_form(); ?>
		    </div>
		  </div>
		</div>
	</div>
	
<?php endif; ?>
    <div class="top-news">
	    <h1><?php echo $PT; ?></h1>
	    <?php if ( is_category()) {
				$category_description = category_description();
				if ( ! empty( $category_description ) )
					echo '<div class="archive-meta">' . $category_description . '</div>';
			}
		?>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
<?php while ( have_posts() ) : the_post(); ?>

				<li id="post-<?php the_ID(); ?>">
				  <div class="img"><?php echo image_tag_resized(get_post_thumb_src(get_the_ID()), 167, 112); ?></div>
				  <div class="text">
				    <h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<!--<small><?php the_date('d.m.Y') ?></small>-->
						<?php the_excerpt(); ?>
					<div class="link-post">
						<a href="<?php the_permalink(); ?>" class="more"><?php _e('read more'); ?></a>
					</div>
				  </div>
				</li>

<?php endwhile; ?>
			  </ul>
			</div>
		  </div>
		</div>
	</div>
		
<?php if ( $wp_query->max_num_pages > 1 ) : ?>

	<div id="nav-below" class="navigation cf">
		<div class="nav-previous">
			<?php next_posts_link( __('<span class="meta-nav">&larr;</span> Older posts', 'SwissPhone') ); ?>
		</div>
		<div class="nav-next">
			<?php previous_posts_link( __('Newer posts <span class="meta-nav">&rarr;</span>', 'SwissPhone') ); ?>
		</div>
	</div>
	
<?php endif; ?>

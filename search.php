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
<?php get_sidebar(); ?>	
	  </div>
	  
	  <div class="right">
	  	<div class="top-news">
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			
			<?php if ( have_posts() ) : ?>
				<h1><?php _e('Search Results for:', 'SwissPhone'); echo get_search_query(); ?></h1>
				<?php 
				global $in_search_context;
				$in_search_context = true;
				get_search_form(); 
				$in_search_context = false;
				?>
				<ul>
				<?php while ( have_posts() ) : the_post(); ?>
					<li id="post-<?php the_ID(); ?>">
					  <div class="img"><?php echo image_tag_resized(get_product_thumbnail_src(get_the_ID()), 167, 112) ?></div>
					  <div class="text">
					    <h2>
					    	<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					    	<?php 
					    		global $post;
					    		echo " ($post->post_type)";
					    	?> 
					    </h2>
						<small><?php the_date('d.m.Y') ?></small>
						<?php the_excerpt(); ?>
						<div class="link-post"><span class="comments"><?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?></span>|<a href="<?php the_permalink(); ?>" class="more">read more</a></div>
					  </div>
					</li>
				<?php endwhile; ?>
				</ul>
				
										
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
				
					<div id="nav-below" class="navigation cf">
						<div class="nav-previous">
							<?php next_posts_link( '<span class="meta-nav">&larr;</span> Older posts' ); ?>
						</div>
						<div class="nav-next">
							<?php previous_posts_link( __('Newer posts <span class="meta-nav">&rarr;</span>', 'SwissPhone') ); ?>
						</div>
					</div>
					
				<?php endif; ?>
				
				
			<?php else : ?>
			<div id="post-0" class="error404 not-found">
				<h1>Not Found</h1>
				<div class="entry-content">
				  <div class="top-left-bg">
					<div class="top-right-bg">
					<p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
					<?php 
					global $in_search_context;
					$in_search_context = true;
					get_search_form(); 
					$in_search_context = false;
					?>
					</div>
				  </div>
				</div>
			</div>
			<?php endif; ?>
		  </div>
	  	</div>
	   </div>
	   </div>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>

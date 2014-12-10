<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
		<?php include(TEMPLATEPATH . '/sidebar-news.php'); ?>	
	  </div>
	  <div class="right">
	    <div class="top-news">
		<?php 
		
		global $GEO_location;
		$local_news 	= @$_GET['local_news'];
		$local_title 	= __('Most recent local news', 'swissphone'); 
		$world_title 	= __('Most recent international news', 'swissphone'); 
		$args = array(
			'post_type'		=> 'news',
			'paged'			=> ($_t = get_query_var('paged'))?$_t:1
		);
		
		if ( is_geo_local() ) { 
			$args['country']=$local_news;
			echo "<h2>$local_title</h2>";
		} else {
			echo "<h2>$world_title</h2>";
		}
		$exclude_IDs = array();
		query_posts($args);
		if ( ! have_posts() ) {
			unset($args['country']);
			query_posts($args);
		}
		global $wp_query;
		//var_dump($wp_query);
		?>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			  	
				<?php while ( have_posts() ) : the_post(); $exclude_IDs[] = get_the_ID();  ?>
				
					<li id="post-<?php the_ID(); ?>">
						<div class="img"><?php the_post_thumbnail('thumbnail'); ?></div>
						<div class="text">
						    <h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
							<small><?php the_date('d.m.Y') ?></small>
							<?php the_excerpt(); ?>
							<div class="link-post">
								<span class="comments">
									<?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>
								</span>
								|
								<a href="<?php the_permalink(); ?>" class="more">read more</a>
							</div>
						</div>
					</li>
				
				<?php endwhile; ?>
				
			  </ul>
			</div>
		  </div>
		</div>
		</div>
		
		<div class="bottom-news">
		<?php 
			$args = array(
				'post_type'		=> 'news',
				'post__not_in'	=> $exclude_IDs	
			);
		if ( ! is_geo_local() ) { 
			$args['country']=$local_news;
			echo "<h1>$local_title</h1>";
			$add_ref = add_query_arg('show','local');
			$add_txt = __('see all local news');			
		} else {
			echo "<h1>$world_title</h1>";
			$add_ref = add_query_arg('show','international');
			$add_txt = __('see all international news');
		}
		$add_link = '<a href="'.$add_ref.'">'.$add_txt.'</a>';
		$bot_news = new WP_Query($args);
		?>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			  	<?php foreach( $bot_news->posts as $news) {
			  		$date = date('d.m.Y', strtotime($news->post_date) );
					$link = get_permalink($news->ID);
					$ttl  = $news->post_title;
					$num  = get_comments_number($news->ID);
					$word = ($num == 1)?'Comment':'Comments';
					echo '
				  	<li>
					  <h2><a href="'.$link.'">'.$ttl.'</a></h2>
					  <small>'.$date.'</small>
					  <div class="link-post">
					  	<span class="comments"><a href="'.$link.'#comments">'.$num.' ' .$word.'</a></span>
					  	|
					  	<a href="'.$link.'" class="more">read more</a>
					  </div>
					</li>';
			  	} ?>
			  </ul>
			  <p class="all-news"><?php echo $add_link; ?></p>
			</div>
		  </div>
		</div>
		</div>
<?php global $wp_query;
	 if ( $wp_query->max_num_pages > 1 ) : ?>

	<div id="nav-below" class="navigation cf">
		<div class="nav-previous">
			<?php next_posts_link( '<span class="meta-nav">&larr;</span> Older posts' ); ?>
		</div>
		<div class="nav-next">
			<?php previous_posts_link( 'Newer posts <span class="meta-nav">&rarr;</span>' ); ?>
		</div>
	</div>
	
<?php endif; ?>
	  	
	  	
		
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>

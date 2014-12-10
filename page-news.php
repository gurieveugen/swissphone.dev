<?php
/**
 *
 * @package WordPress
 * @subpackage SwissPhone
 */
/**
 * Template Name: News Page
 */
?>
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
		$is_event = false;
		$slug = get_query_var('news_category');
		$newscat = get_term_by('slug', $slug, 'news_category');
		$args = array(
			'post_type'	=> 'news',
			'paged'		=> ($_t = get_query_var('paged'))?$_t:1,
			'country'	=> get_geo_slug(),
			'orderby'	=> 'post_date',
			'order'		=> 'DESC'
		);
		if ( false !== stripos($slug, 'event') || false !== stripos($slug, 'veranstaltung' ) || false !== stripos($slug, 'evenemen')) {
			$is_event = true;
			$args['meta_key'] = 'date_start';
			$args['orderby'] = 'meta_value';
			$args['order'] = 'ASC';
		}
		if ($newscat) {
			$args['news_category'] = get_query_var('news_category');
			echo '<h2>' . remove_brs($newscat->name) . '</h2>';
		} else {
			echo '<h2>' . __('Most recent News', 'SwissPhone') . '</h2>';
		}
		query_posts($args);
		
		if ( $is_event ) {
			$args['orderby'] = "post_date";
			$args['meta_key'] = "always_on_top";
			$args['meta_value'] = 'yes';
			$featured = get_posts( $args );
			global $wp_query;
			$fids = array();
			foreach( $featured as $ftr ) {
				$fids[] = $ftr->ID;
			}
			for ( $i = 0; $i < $wp_query->post_count; $i++ ) {
				if ( in_array( $wp_query->posts[$i]->ID, $fids ) ) unset( $wp_query->posts[$i] );
			}
			$wp_query->posts = array_values(array_merge( $featured, $wp_query->posts ));
			$wp_query->post_count = count($wp_query->posts);
			rewind_posts();
		}
		?>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
				<?php
					global $post; 
					setlocale(LC_TIME, strtolower(ICL_LANGUAGE_CODE) .'_' . strtoupper(ICL_LANGUAGE_CODE));
                    
                    function _loc_hack( $string ) {
                        if ( 'fr' == strtolower(ICL_LANGUAGE_CODE) ) {
                             $string = str_replace( 
                                array( "January","February","March","April","May","June","July","August","September","October","November","December" ), 
                                array( 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ), 
                                $string);
                        } else {
                            $string = utf8_encode($string);
                        } 
                        return $string;
                    }
                    
                    if ( in_array( $slug , array( 'nachrichten-de', 'news-en', 'nouvelles-fr' ) ) and $em = available_emergency_news() ) foreach ( $em  as $news ) : ?>

                    <li id="post-<?php echo $news->ID ?>">
                        <div class="img"><?php echo get_the_post_thumbnail( $news->ID, 'thumbnail'); ?></div>
                        <div class="text">
                            <h2><a href="<?php echo get_permalink( $news->ID ); ?>" rel="bookmark" ><?php echo $news->post_title ?></a></h2>
                            <?php echo apply_filters( 'get_the_excerpt', $news->post_excerpt ); ?>
                            <div class="link-post">
                                <a href="<?php get_permalink( $news->ID ); ?>" class="more" ><?php _e('read more', 'SwissPhone'); ?></a>
                            </div>
                        </div>
                    </li>                              
                    <?php endforeach; ?>
                    
                    <?php $appendix = ''; ?>
                    
					<?php while ( have_posts() ) : the_post(); ob_start(); ?>
					<li id="post-<?php the_ID(); ?>">
						<div class="img"><?php the_post_thumbnail('thumbnail'); ?></div>
						<div class="text">
							<!-- <small><?php the_date('d.m.Y') ?></small> -->
							<?php
							$style='';
                            $delay=false;
							if ( $is_event ) {
								$meta = get_post_custom( $post->ID );
								$ds = $meta['date_start'][0];
								$de = $meta['date_end'][0];
								if ( (($de > $ds) && ($de < time())) || (! $de and $ds < time() )) {
								     $style = 'style="color: #aaaaaa;"';
                                     $delay = true;
                                }
								?><h2><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $style; ?> ><?php the_title(); ?></a></h2><?php
								echo '<p ',$style,'>';
                                //setlocale(LC_ALL, "de_DE");
                                echo '<span></span>';
								echo _loc_hack(strftime( '%B %e', $ds ));
								if ( $de > $ds ) {
									echo _loc_hack(strftime( ' - %B %e, %Y', $de ));
								} else {
									echo strftime( ', %Y', $ds );
								}
								echo '<br />', $meta['location'][0], ', ', $meta['country'][0];
								$web = $meta['website'][0];
								/*if ($web) {
									$web = str_ireplace('http://', '', $web);
									echo
								}*/
								echo '</p>'; 
							} else {
								?><h2><a href="<?php the_permalink(); ?>" rel="bookmark" ><?php the_title(); ?></a></h2><?php
								the_excerpt();
							} 
							?>
							<div class="link-post">
								<!-- 
									<span class="comments">
									<?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>
								</span>
								| -->
								<a href="<?php the_permalink(); ?>" class="more" <?php echo $style; ?>><?php _e('read more', 'SwissPhone'); ?></a>
							</div>
						</div>
					</li>
			     <?php 
			         $_tdata = ob_get_clean(); 
			         if ( $delay ) { 
			             $appendix .= $_tdata;
                     } else { 
                         echo $_tdata;
                     }
			     ?>
				<?php endwhile; ?>
				<?php echo $appendix; ?>
			  </ul>
			</div>
		  </div>
		</div>
		</div>
		
<?php global $wp_query;
	 if ( $wp_query->max_num_pages > 1 ) : ?>

	<div id="nav-below" class="navigation cf">
		<div class="nav-previous">
			<?php next_posts_link( '<span class="meta-nav">&larr;</span>'. __('Older news','SwissPhone') ); ?>
		</div>
		<div class="nav-next">
			<?php previous_posts_link( __('Recent news', 'SwissPhone' ) . '<span class="meta-nav">&rarr;</span>' ); ?>
		</div>
	</div>
	
<?php endif; ?>
	  	
	  	
		
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>

<?php
/**
 * @package WordPress
 * @subpackage Base_theme
 */
?>
	    <?php /* <div class="widget-block">
		  <h2><span><?php _e('Overview'); ?></span></h2>
		  <ul>
		  	<?php
		  		foreach( get_terms('country') as $term ) {
		  			$st = ( $term->slug == @$_GET['local_news'] )?'class="parent"':'';
		  			echo '<li '.$st.' ><a href="'.home_url('/news/?local_news=' . $term->slug).'">'.__($term->name .' News').'</a></li>';
		  		}
			?>
		  </ul>
		</div> */
		?>


	    <div class="widget-block">
		  <h2><span><?php _e('Categories'); ?></span></h2>
		  <ul>
		  	<?php 
		  		Global $GEO_location;
		  		$terms = get_terms(	'news_category', array(
		  			'taxonomy'		=>  'news_category',
					'hide_empty'	=>	false
				));
		  		foreach( $terms as $term ) {
		  			$args = array(
						'news_category'	=>	$term->slug,
						'post_type'		=> 	'news',
						'country'		=>  get_geo_slug()
					);
					$ps	= new WP_Query($args);
					/*$list = '';
					foreach( $ps->posts as $news) {
						$list .= '<li><a href="'.get_permalink($news->ID).'">'.$news->post_title.'</a></li>';
					}
					if ( !empty($list)) {
						echo '<li><a href="#" class="category-title-link">'.$term->name.'</a>
						<ul>'.$list.'</ul></li>';
					}*/
					if (count($ps->posts)) echo '<li><a href="'.get_term_link($term).'" >'.force_brs($term->name).'</a></li>';
		  		}
			?>
		  </ul>
		</div>
			

<?php dynamic_sidebar('news-sidebar'); ?>


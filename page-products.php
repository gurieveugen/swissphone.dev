<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Products Page
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
			<?php global $ptype; 
			$ptype = 'product'; 
			get_sidebar('solutions'); ?>	
	  </div>
	  <div class="right">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <?php //the_content(); ?>
			  <ul class="solutions-list">
			  	<?php
				Global $GEO_location;
				$word = 'product';
				$terms = get_terms( 'product_category', array(
					'hide_empty' => false,
					'taxonomy'	=> 'product_category',
					'orderby'	=> 'term_group',
					'order'		=> 'ASC'
				));
				foreach($terms as $term) {
					$link = get_term_link($term, 'product_category');
					$fp = new WP_Query(array(
						'post_type'	=> 'product',
						'product_category' => $term->slug,
						'country'			=> get_geo_slug(),
						'posts_per_page'	=> 1
					));
					if (! count($fp->posts) ) continue;
					$src = get_taxonomy_thumb($term->term_id);
					if (!$src) $src = get_product_thumbnail_src( @$fp->posts[0]->ID );
					$img = image_tag_resized( $src, 158, 163);
					$exr = term_description( $term->term_id, 'product_category' );
					//$inq = home_url('/inquire-about-solution-or-product/?subject_ID='.$sol->ID);
					$lbl = __('See All Products', 'SwissPhone');
					$_tname = remove_brs($term->name);
					echo 
<<<SOLUTION_ITEM
				<li>
				  <div class="rep-bg">
				    <div class="top-bg">
				      <div class="bot-bg cf">
				        <div class="img"><a href="$link">$img</a></div>
				        <div class="text">
				          <h2><a href="$link">$_tname</a></h2>
					      $exr
					      <div class="bot-link">
					        <span class="red"><a href="$link">$lbl  <small>&nbsp;</small></a></span>
					      </div>
				        </div>
				      </div>
				    </div>
				  </div>
				</li>
SOLUTION_ITEM;
				}
				?>
			  </ul>
			</div>
		  </div>
		</div>
		
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); ?>

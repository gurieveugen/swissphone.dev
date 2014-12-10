<?php
/**
 *
 * @package WordPress
 * @subpackage SwissPhone
 */
$the_query = new WP_Query(array(
	'post_type' => array('service', 'product', 'solution' ),
	'page'		=> ($_t = get_query_var('paged'))?$_t:1,
	'service_category' => get_query_var('service_category'),
	'country'	=> get_geo_slug(),
	'orderby'	=> 'menu_order',
	'order'		=> 'ASC'
	
)); 
global $gContext;
$gContext =  'service_category';
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
			<?php get_sidebar('solutions'); ?>	
	  </div>
	  <div class="right">
		<h1><?php echo single_c_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
		     <?php echo term_description(); ?>
			  <ul class="solutions-list">
			  	<?php while ($the_query->have_posts()) : $the_query->the_post();
					$img = image_tag_resized(get_product_thumbnail_src( get_the_ID() ), 158, 163);
					$inq = home_url('/inquire-about-solution-or-product/?subject_ID='.get_the_ID());
				?>
				<li>
				  <div class="rep-bg">
				    <div class="top-bg">
				      <div class="bot-bg cf">
				        <div class="img"><a href="<?php the_permalink(); ?>"><?php echo $img ?></a></div>
				        <div class="text">
				          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					      <?php the_excerpt(); ?>
					      <div class="bot-link">
					        <span class="red"><a href="<?php the_permalink(); ?>"><?php _e( 'View Segment', 'SwissPhone'); ?><small>&nbsp;</small></a></span>
					        <?php /* 
							 * <span class="red"><a href="<?php echo $inq; ?>"><?php _e('Inquire about solution','SwissPhone'); ?><small>&nbsp;</small></a></span>
							 */ ?>
					      </div>
				        </div>
				      </div>
				    </div>
				  </div>
				</li>
				<?php endwhile; ?>
			  </ul>
			</div>
		  </div>
		</div>
		
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); ?>



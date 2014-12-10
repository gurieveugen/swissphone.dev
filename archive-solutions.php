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
			<?php get_sidebar('solutions'); ?>	
	  </div>
	  <div class="right">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul class="solutions-list">
			  	<?php while (have_posts()) : the_post();
					$img = image_tag_resized(get_product_thumbnail_src( get_the_ID() ), 158, 163);
					$inq = home_url('/inquire-about-solution-or-product/?subject_ID='.get_the_ID());
				?>
				<li>
				  <div class="rep-bg">
				    <div class="top-bg">
				      <div class="bot-bg cf">
				        <div class="img"><?php echo $img ?></div>
				        <div class="text">
				          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					      <?php the_excerpt(); ?>
					      <div class="bot-link">
					        <span class="red"><a href="<?php the_permalink(); ?>">view solution  <small>&nbsp;</small></a></span>
					        <span class="red"><a href="<?php echo $inq; ?>">inquire about solution <small>&nbsp;</small></a></span>
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



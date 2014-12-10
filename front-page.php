<?php
/**
 *
 * @package WordPress
 * @subpackage SwissPhone
 */
 
  /*  query_posts(array(
        'post_type'     => 'homepage',
        'posts_per_page' => 1,
        'country'       => get_geo_slug()
    ));*/
?>
<?php get_header(); ?>
    <div class="big-slider">
      <?php GLOBAL $HVSlider;
	  $HVSlider->start();   ?>	
	  <div class="img-slider">
	    <ul>
	      %SLIDE_START%	 
		  <li>
		  	%IMG%
			<div class="text cf">
			  <h2><a href="%PARAMETER_LINK_URL%">%CONTENT%</a></h2>
			  <div class="view-link"><span><a class="min-width: 50px;" href="%PARAMETER_LINK_URL%">%PARAMETER_LINK_TEXT%</a></span></div>
			</div>
		  </li>
		  %SLIDE_END%
		</ul>
	  </div>
	  <?php $HVSlider->end();  ?>
	  <div class="carousel-pagination"></div>
	</div>

  	<?php 
    
  		/*global $GEO_location;
  		$sols = new WP_Query(array(
			'post_type' => 'solution',
			'country'	=> get_geo_slug(),
			'orderby'	=> 'menu_order, post_title', 
			'posts_per_page' => 20
		));
		
		$list =	'';
		foreach ( $sols->posts as $sol ) {
			$list .= '
			<li>
			  <div class="content">
			    <div class="img-block"><a href="'.get_permalink($sol->ID).'">'
			    .image_tag_resized( get_product_thumbnail_src($sol->ID) , 162, 167, esc_attr($sol->post_title))
			    .'</a></div>
				<div class="tit-block">
				  <h3><a href="'.get_permalink($sol->ID).'">'.$sol->post_title.'</a></h3>
				</div>
			  </div>
			</li>';
		}
		
		if ( ! empty($list) ) {
			$sttl = __('Solutions', 'SwissPhone');
			echo 
<<<SOL_SLIDER
	<div class="small-slider cf">
	  <h2><span>$sttl</span></h2>
	  <div class="slider">
		<div class="img-slider">
		  <ul>
		  	$list
		  </ul>
		</div>
	  </div>
	</div>
	<script type="text/javascript">
		jQuery('.small-slider .img-slider').carousel({
			btnsPosition : 'outside',
			dispItems	: 5
		});
	</script>			
SOL_SLIDER;
		}
*/	
  	GLOBAL $HVSliderB;
	$HVSliderB->start(); ?>
		
	<div class="small-slider cf">
	  <h2><span><?php echo _e('Solutions', 'SwissPhone'); ?></span></h2>
	  <div class="slider">
		<div class="img-slider">
		  <ul>
		  	%SLIDE_START%	 
			<li>
			  <div class="content">
			    <div class="img-block"><a href="%PARAMETER_LINK_URL%" title="%TITLE%">%IMG%</a></div>
				<div class="tit-block">
				  <h3><a href="%PARAMETER_LINK_URL%">%TITLE%</a></h3>
				</div>
			  </div>
			</li>
		  	%SLIDE_END%
		  </ul>
		</div>
	  </div>
	</div>
	
	<script type="text/javascript">
		jQuery('.small-slider .img-slider').carousel({
			btnsPosition : 'outside',
			dispItems	: 5
		});
		jQuery(function($){
			var currentTallest = 0;
			$('.small-slider .tit-block h3 a').each(function(){
				var tt = $(this).height();
				if ( tt > currentTallest ) currentTallest = tt;
			}).each(function(){
				var tt = $(this).height();
				if ( tt < currentTallest ) {
					var $mv = (currentTallest - tt) / 2;
					$(this).css({
						paddingTop: $mv + 'px',
						paddingBottom: $mv + 'px',
						display: 'block'
					});
				}
			});		
		});
		
	</script>
				
  	<?php $HVSliderB->end(); ?>
  		
	<div class="home-page cf">
	  	<?php global $GEO_location; 
		$sb_name = strtolower('country-sidebar-' . $GEO_location['countryCode']);
	  	if (is_active_sidebar( $sb_name )) {
	  		dynamic_sidebar( $sb_name );
		} else {
			dynamic_sidebar( 'country-sidebar-other' );
			//dynamic_sidebar( 'Homepage Switzerland' );
		}	
		?>
	</div>
	<script type="text/javascript">
	    var home_blocks_names = ['left','center','right'];
		jQuery("div.home-page").children().each(function(index){
			jQuery(this).wrap('<div class="' + home_blocks_names[index] +'"></div>');
		});
	</script>
<?php get_footer(); ?>

<?php
/**
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
  </div>
  <!-- end content -->
  
  <!-- footer -->
  <div class="footer-block cf">
    
	<div class="top-footer">
	  <div class="text">
			<div class="textwidget"><?php if ($ph = get_current_homepage( 'phone' )) echo __('Call Us: ', 'SwissPhone') . $ph;  ?></div>
	  </div>
	  
	  <div class="share">
			<div class="execphpwidget"><ul>
				<li class="tweet"><a href="<?php echo get_current_homepage( 'twitter_url' ); ?>">Tweet</a></li>
				<li class="facebook"><a href="<?php echo get_current_homepage( 'fb_url' ); ?>">Facebook</a></li>
				<li class="rss"><a href="<?php echo get_current_homepage( 'rss_url' ); ?>">Rss</a></li>
			</ul></div>

	  </div>
	</div>
	
	<div class="bot-footer cf">
	  <div class="left">
	    <div class="main-menu-footer">
		  <?php get_footer_top_menu(); ?>
		</div>
		
		<div class="secondary-menu-footer">
		  <?php get_footer_bottom_menu(); ?>
		</div>
		<p class="copyright">
<?php /*if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar CopyRight Text') ) : ?>	 
			__
<?php endif;*/
		_e('Copyright 2011 Swissphone Group all rights reserved', 'SwissPhone');
 ?>	</p>
 	
	  </div>
	  
	  <div class="right">
	    <?php get_footer_links_menu(); ?>
	  </div>
	</div>

  </div>
  <!-- end footer -->
</div>
</div>
<?php wp_footer();
if (isset($_GET['debug'])) {
	global $debug_msg, $wp_query;
	echo '<pre style="display: none">',$debug_msg;
    var_dump($wp_query);
	echo '</pre>';
}
?>
    <script type="text/javascript" >
  		$ = jQuery;
  		jQuery('ul.category_autoexpand li ul').each(function(){
  			if ( ! $(this).closest('li').is('.extended') ) jQuery(this).css('display','none'); 
  		});
		jQuery('ul.category_autoexpand').children('li').children('a').click(function(){
			var li = jQuery(this).closest('li');
			if ( li.is('.extended') ) {
			} else {
				var lis = jQuery('ul.category_autoexpand li.extended').removeClass('extended').removeClass('parent').children('ul').slideUp('fast');
				li.addClass('extended').addClass('parent');
				li.children('ul').slideDown('fast');
			}
			return true;
		});
		$('.wpcf7-form').addClass('contact-form');
    </script>
  
  	<script type="text/javascript">
		jQuery(function($){
			var currentTallest = 0;
			$('#product-component-slider .tit-block-list h3 a').each(function(){
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
			setInterval('check_wpcf7_status()', 500);
		});
		function check_wpcf7_status() {
		    jQuery('form.wpcf7-form img.ajax-loader').each(function(){
		        if (jQuery(this).css('visibility') == 'hidden') { 
		          jQuery(this).closest('form').find('input.wpcf7-submit').removeAttr('disabled');
		        } else {
		          jQuery(this).closest('form').find('input.wpcf7-submit').attr('disabled','disabled');  
		        }
		    });
		}
	</script>
	<div style="display:none;"><?php echo get_post_meta(CURRENT_PAGE_ID, 'google_adwords_code', true); ?></div>
</body>
</html>

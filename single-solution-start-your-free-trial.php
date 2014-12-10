<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
?>
<?php get_header(); ?>

	<div class="crumbs-block"><?php show_bread_crumbs() ?></div>
	<?php if ( have_posts() ) : the_post(); ?>

	  <div id="start-your-free-trial" class="landing-post">
	    <h1><?php the_title(); ?></h1>
		  <div class="landing-page cf">
				<?php the_content(); ?>
			   <script type="text/javascript">
				   var last_selected_country = 'USA';
				   jQuery(function($){
					   setInterval(function(){
						   vl = $('#cf7-country').val().toLowerCase().replace(" ","-");
						   if(vl != last_selected_country) {
							   last_selected_country = vl;
						   } else {
							   return true;
						   }
						   $('.cf7-country-' + vl).show();
						   $('p[class^="cf7-country"]').not('.cf7-country-' + vl).hide()
							   .find('select').val('').end()
							   .find('span.select').html('---');
					   }, 500);
				   });
			   </script>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">Pages:', 'after' => '</div>' ) ); ?>
				<?php edit_post_link('Edit', '<span class="edit-link">', '</span>' ); ?>
				<?php if (isset($_GET['fname'])) { ?>
				<script type="text/javascript">
				var cf7_form_params = new Array("<?php echo $_GET['fname']; ?>", "<?php echo $_GET['company']; ?>", "<?php echo $_GET['email']; ?>", "<?php echo $_GET['state']; ?>", "<?php echo $_GET['phone']; ?>");

				jQuery(document).ready(function() {
					setTimeout(function() {
						if (cf7_form_params[0] != '') { jQuery('#cf7-full-name').val(cf7_form_params[0]); jQuery('#cf7-full-name').removeClass('watermark'); }
						if (cf7_form_params[1] != '') { jQuery('#cf7-company').val(cf7_form_params[1]); jQuery('#cf7-company').removeClass('watermark'); }
						if (cf7_form_params[2] != '') { jQuery('#cf7-email').val(cf7_form_params[2]); jQuery('#cf7-email').removeClass('watermark'); }
						if (cf7_form_params[3] != '') {
							jQuery('#cf7-state option[value=\''+cf7_form_params[3]+'\']').attr('selected', 'selected'); 
							jQuery('#selectstate').html(cf7_form_params[3]);
						}
                        if (cf7_form_params[4] != '') { jQuery('#cf7-phone').val(cf7_form_params[4]); jQuery('#cf7-phone').removeClass('watermark'); }
					}, 0);
				});
				</script>
				<!-- Google Code for re720 demo Conversion Page -->
                <script type="text/javascript">
                /* <![CDATA[ */
                var google_conversion_id = 1037012922;
                var google_conversion_language = "en";
                var google_conversion_format = "3";
                var google_conversion_color = "ffffff";
                var google_conversion_label = "GO1PCKravwIQup--7gM";
                var google_conversion_value = 0;
                /* ]]> */
                </script>
                <script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
                </script>
                <noscript>
                <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1037012922/?value=0&label=GO1PCKravwIQup--7gM&guid=ON&script=0"/>
                </div>
                </noscript>
				<?php } ?>
                <script type="text/javascript">
                var fb_param = {};
                fb_param.pixel_id = '6007664294082';
                fb_param.value = '0.00';
                (function(){
                var fpw = document.createElement('script');
                fpw.async = true;
                fpw.src = (location.protocol=='http:'?'http':'https')+'://connect.facebook.net/en_US/fp.js';
                var ref = document.getElementsByTagName('script')[0];
                ref.parentNode.insertBefore(fpw, ref);
                })();
                </script>
                <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6007664294082&value=0" /></noscript>                
		  </div>
	<?php endif; ?>
	
<?php get_footer(); ?>

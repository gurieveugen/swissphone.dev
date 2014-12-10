<?php
/**
 * Template Name: Adwords Tracking
 * @package WordPress
 * @subpackage SwissPhone
 */
get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php get_sidebar(); ?>
	  </div>
	  
	  <div class="right">
        <?php the_post(); ?>
	  <div id="post-<?php the_ID(); ?>" class="post">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <div class="list-style">
				<?php the_content(); 
				    if ( isset($_GET['formsent']) and (int) $_GET['formsent'] ) : ?>
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
				<?php endif; ?>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>
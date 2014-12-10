<?php
/**
 * @package WordPress
 * @subpackage adility
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php include(TEMPLATEPATH . '/sidebar-news.php'); ?>	
	  </div>
	  
	  <div class="right">
		<?php include("loop.php"); ?>		
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
<?php get_footer(); ?>

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
<?php get_sidebar(); ?>
	  </div>
	  
	  <div class="right">

		<div id="post-0" class="post error404 not-found">
		<h1>Not Found</h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
					<p>Apologies, but the page you requested could not be found. Perhaps searching will help.</p>
					<?php get_search_form(); ?>
			</div>
		  </div>
		</div>
		</div>

	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
	<script type="text/javascript">
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>
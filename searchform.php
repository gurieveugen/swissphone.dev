<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" >
		  <div class="bg-search">&nbsp;</div>
		  <p>
			<input type="text" value="<?php _e('Search', 'SwissPhone'); ?>" id="s" onFocus="if (this.value=='<?php _e('Search', 'SwissPhone'); ?>'){this.value='';}" onBlur="if (this.value==''){this.value='<?php _e('Search', 'SwissPhone'); ?>';}" name="s" />
			<input type="submit" id="searchsubmit" value=" " />
			<input type="hidden" name="lang" value="<?php echo(ICL_LANGUAGE_CODE); ?>"/>
		  </p>
		  <?php /*
		  <script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery("#searchsubmit").click(function(){
					var href = document.location.href;
					var s = jQuery("#s").val();
					//var href = jQuery(location).attr("href");
					var new_href = document.location.href+'?s='+s;
					alert(new_href);
					document.location.href = new_href;
				});
			});
		  </script>
		  */?>
		  <?php 
		  global $in_search_context;
		  if ($in_search_context) : ?>
		 <div class="select">
		  <select class="styled" name="post_type">
				<option value="any"><?php _e('All content'); ?></option>
				<option value="news"><?php _e('News'); ?></option>
				<option value="solution"><?php _e('Solutions'); ?></option>
				<option value="product"><?php _e('Products'); ?></option>
				<option value="service"><?php _e('Segments'); ?></option>
				<option value="dealer"><?php _e('Dealers'); ?></option>
				<option value="post"><?php _e('Blog'); ?></option>
				<option value="page"><?php _e('Pages'); ?></option>
		  </select>
		  </div>
		  <?php endif; ?>
		  
</form>
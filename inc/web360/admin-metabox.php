<div style="padding: 15px;">
	<div>
		<input type="checkbox" value="yes" name="use_web360" <?php echo $use360?'checked="checked"':''; ?> />
		<?php _e( 'Use WebRotate360 image rotator', 'Web360' ); ?>
	</div>
	
	<div class="web360_container">
		<br style="clear: both;" />
		<?php 
			if (!empty($atc360->rotators)) $atc360->add($Rotator360->create());
			$view_cnt = 0;  
		?>
		<?php foreach ( $atc360->rotators as $rot ) : ?>
			<div class="web360_view" >
				<input type="hidden" name="rotator_ids[]" value="<?php echo $rot->id; ?>" />
				<strong class="view_title">View <span class="view_number"><?php echo ++$view_cnt; ?></span></strong>
				<?php if ($image = $rot->images[0]) :	$url   = $rot->source() . '/images/' . $image;	?>
					<img class="image" src="<?php echo $url; ?>" alt="<?php echo $image; ?>" width="90" height="90" />
				<?php else: ?>
					<div class="image"></div>
				<?php endif; ?>
				<div class="view-vcard">
					Images : <span class="images_count"><?php echo count($rot->images); ?></span><br />
					<small id="upload_result_<?php echo $rot->id; ?>"></small>
				</div>
				<div class="button web360pload" style="padding: 0px; width:90px; height: 30px;"><div id="web360_upload_button_<?php echo $rot->id; ?>"></div></div>
				<div class="button web360cancel" style="display: none" id="web360_cancel_button_<?php echo $rot->id; ?>">Cancel</div>
				<div style="clear: both;"><input type="checkbox" name="delete_ids[<?php echo $rot->id; ?>]" value="yes" /> Delete this view </div>
			</div>
			<script type="text/javascript">
				jQuery(function($){
					init_web360_swfuploader(<?php echo $rot->id; ?>);
				});
			</script>

		<?php endforeach; ?>
	</div>
	<div style="clear: both;"></div>
	<input type="button" class="button" onclick="return sp_add_view();" value="Add View" />
		
</div>
<script type="text/javascript">
	var web360uploaders = new Array();
	function init_web360_swfuploader(id) {
		 web360uploaders.push(new SWFUpload({ 
			upload_url 		: "<?php echo home_url( '/?web360=upload' ); ?>", 
			flash_url 		: "<?php echo home_url( '/wp-includes/js/swfupload/swfupload.swf' ); ?>",
			post_params		: {"web360id" : id }, 
			file_size_limit : "32 MB",
			
			button_placeholder_id : "web360_upload_button_" + id,
			button_width 	: 90, 
			button_height 	: 30, 
			button_text 	: '<strong class="button">Add Images</strong>',
			button_text_style: ".button { color: #000000; padding: 0px; font-family: Arial, Helvetica; background-color: #3333FF;  }",
			button_text_left_padding : 5, 
			button_text_top_padding : 5,
			
			
			file_dialog_start_handler 	: sp_fileDialogStart,
			file_queued_handler 		: sp_fileQueued,
			file_queue_error_handler 	: sp_fileQueueError,
			file_dialog_complete_handler: sp_fileDialogComplete,
			upload_start_handler 		: sp_uploadStart,
			upload_progress_handler 	: sp_uploadProgress,
			upload_error_handler 		: sp_uploadError,
			upload_success_handler 		: sp_uploadSuccess,
			upload_complete_handler 	: sp_uploadComplete,
			
			custom_settings : {
				progressTarget : "upload_result_" + id,
				cancelButtonId : "web360_cancel_button_" + id,
				secondButtonId : "web360_del_button_" + id
			}
		}));	
	}
	
	function sp_add_view() {
		
		jQuery.ajax( {
			url: '/?web360=new',
			dataType: "json",
			success: function(id) {
				var cont = jQuery( '.web360_container' );
				var newView = jQuery(' \
					<div class="web360_view" > \
						<input type="hidden" name="rotator_ids[]" value="' + id + '" /> \
					<strong class="view_title">New View</strong> \
					<div class="image"></div> \
					<div class="view-vcard"> \
						Images : <span class="images_count">0</span><br /> \
						<small id="upload_result_' + id + '"></small> \
					</div> \
					<div class="button web360pload" style="padding: 0px; width:90px; height: 30px;"><div id="web360_upload_button_' + id + '"></div></div> \
					<div class="button web360cancel" style="display: none" id="web360_cancel_button_' + id + '">Cancel</div> \
					<div style="clear: both;"><input type="checkbox" name="delete_ids[' + id + ']" value="yes" /> Delete this view </div> \
				</div>');
				cont.append(newView);
				init_web360_swfuploader(id);
			}
		});
		
		return false;
		
	}
	
</script>
 
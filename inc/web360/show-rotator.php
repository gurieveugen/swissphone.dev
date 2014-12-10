<div style="width: 270px; height: 300px;">
	<div id="Web360RotatePlayer" class="wr360_player"></div>
	<script language="javascript" type="text/javascript">
	  var fPlayerVersion = swfobject.getFlashPlayerVersion();
	  _imageRotator.settings.jsScriptOnly  = !fPlayerVersion.major;
	  _imageRotator.settings.swfFileURL    = "<?php echo get_bloginfo( 'template_url' ), '/inc/web360/imagerotator.swf'; ?>";
	  _imageRotator.licenseFileURL		   = "<?php echo get_bloginfo( 'template_url' ), '/inc/web360/license.lic' ?>";
	  _imageRotator.settings.configFileURL = "<?php echo $this->rotators[0]->xml_url(); ?>";
	  _imageRotator.runImageRotator("Web360RotatePlayer");
	  function reload_web360_player( xml ) {
	  		if (_imageRotator.settings.jsScriptOnly) {
	  			 _imageRotator.reload( xml );
	  		} else {
		  		_imageRotator.settings.configFileURL = xml; 
		  		_imageRotator.runImageRotator('Web360RotatePlayer');
		  	}
	  }
	</script>
</div>
<?php if ( count( $this->rotators ) > 1 ) : ?>
<div style="width: 270px; float: left;">
	<?php foreach( $this->rotators as $rot ) : ?>
		<img style="float: left; margin: 5px; width: 80px; height: 80px;" width="80" height="80" 
			onclick="reload_web360_player('<?php echo $rot->xml_url();?>');"
			src="<?php echo $rot->image_url(0); ?>" />
	<?php endforeach; ?>
	<div style="clear: both;"></div>
</div>
<?php endif; ?>
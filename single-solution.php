<?php 

	global $posts, $post;
	if ($posts[0]->post_name == 'ffrs-start-your-free-trial') {
		include "single-solution-start-your-free-trial.php";
	} else if ($posts[0]->post_parent) {
		include "page.php";
	} else {
	global $show_inquiry_button;
	//$cl = get_locale();
	//$contact_page = array('en_EN' => '/about/contact-us/', 'de_DE' => '/de/ueber-uns/kontakt/', 'fr_FR' => '/fr/a-propos/contactez-nous/');
	$_type = get_post_meta( $posts[0]->ID,'inquiry_button_type',true);
	if ( 'off' == $_type ) {
		$show_inquiry_button = '';
	} else {
		if ( $_type && 'custom' == $_type) {
			$_label =  get_post_meta( $posts[0]->ID,'inquiry_button_text',true);
			$_link  =  get_post_meta( $posts[0]->ID,'inquiry_button_link',true);
		}
		if (empty($_label)) {
			$_label = is_singular('solution')
					? __('inquire about solution', 'SwissPhone')
					:(is_singular('product')
						? __('inquire about product', 'SwissPhone')
						: __('inquire about service', 'SwissPhone')
					);
		}
		if (empty($_link)) {
			$_link = add_query_arg( array('subject'=>'productsolution', 'ps'=>$posts[0]->ID), get_permalink(get_translated_id(68)) );
		}
		$show_inquiry_button = 
		'<div class="inquire-button"><span><a href="'.$_link.'">'
		. $_label
		.'<small>&nbsp;</small></a></span></div>';
	}
	
	function get_sb_block_title( $block ) {
		$list = array(
			'references' => __('References','SwissPhone'),
			'keywords'	 => __('Keywords','SwissPhone'),
			'downloads'	 => __('Downloads','SwissPhone'),
			'industries' => __('Industries','SwissPhone'),
			'tech_specs' => __('Technical Specifications','SwissPhone'),
			'components' => __('Components','SwissPhone'),
			'custom'	 => __('Custom', 'SwissPhone' ),
		);
		global $post;
		($_title = get_post_meta($post->ID, 'metabox_title_' . $block , true))
		||
		($_title = $list[$block]);
		return icl_t( 'SwissPhone', $block . '_block_title_' . $post->ID, $_title );
	}
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page solutions-individual">
	  <div class="left" data-test="some test">
<?php include(TEMPLATEPATH . '/sidebar-solutions.php'); ?>	
	  </div>
	  
	  <div class="right">
<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<div class="single-share-block">
			<span class='st_twitter' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText=''></span><span class='st_email' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText=''></span>
			<iframe src="http://www.facebook.com/plugins/like.php?app_id=117636131638863&amp;href=<?php echo urlencode( get_permalink( get_the_ID() ) );?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>
		</div>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <div class="cf">
				  <?php 
				  $custom_html = get_post_meta(get_the_ID(),'show_custom_layout',true);
				  if ('yes' == $custom_html) :
				  	global $post;
					echo '<div class="custom-markup">' . apply_filters('the_content', str_ireplace('[inquire-button]', $show_inquiry_button, $post->post_content)) . '</div>';
				  else :
				  	$hide_right = get_post_meta(get_the_ID(),'hide_right_column',true);
					 ?>
				  <div class="left-entry" <?php if ( $hide_right == 'yes' ) echo 'style="width: 100%"; '; ?>>
				<?php 
				  if ( $_he = get_post_meta( $post->ID, 'html_excerpt', true ) ) {
				  	echo '<div class="single-solution-excerpt-block">' . $_he . '</div>';
				  }
				  the_content();
					($specs = get_post_meta( get_the_ID(), 'tech_specs', true))
					|| ($specs = array());
					$cnt = 0;
					$techsp = '';
					$_numcol = get_post_meta( $post->ID, 'show_tech_specs_column_3', true);
					if ( ! in_array( $_numcol, array( 1, 2, 3 ) ) ) $_numcol = 2;
					foreach ( $specs as $spec ) {
						$_add = '';
						if ($_numcol > 1) {
							$_add .= '<td>'.nl2br($spec['value']).'</td>'; 
						}
						if ($_numcol > 2 ) {
							$_add .= '<td>' . nl2br(@$spec['additional_cell']) . '</td>'; 
						} 
						$techsp .= '<tr '.(($cnt = 1 - $cnt)?'class="grey"':'').'>
				    			<td valign="top">'.$spec['title'].'</td>
								'.$_add.'
				  			</tr>';
					}
					if ($techsp) : ?>
						<div class="technical-list">
						    <div class="tit-block">
					          <h2><span><?php echo get_sb_block_title('tech_specs') ?></span></h2>
					        </div>				
							<table cellspacing="0" cellpadding="1">
								<?php echo $techsp; ?>
							</table>				
							<div class="bot-bg"><div>&nbsp;</div></div>
					  	</div>
					<?php endif; ?>
				 
				  </div>
				  <?php if ( $hide_right != 'yes' ) : ?>
				  <div class="right-entry">
				    <div class="gallery-container cf">
				    	<?php show_product_images( get_the_ID() ); ?>
					</div>
					<?php 
						/*global $show_inquiry_button;
						if ($show_inquiry_button ) {
							if ('yes' == get_post_meta( get_the_ID(), 'show_button', true)) {*/
								echo $show_inquiry_button; 
					/*		}
						}*/
					$_custom = get_post_meta( get_the_ID(), 'attached_custom_block', true);
					if (! empty($_custom)) {
						echo '<div class="custom-product-info" >', $_custom, '</div>';
					}
			    ob_start(); $bl_count = 0; ?>
			    <div class="tit-block">
		          <h2><span><?php _e('Additional Information','SwissPhone'); ?></span></h2>
		        </div>
		        
				<div class="bg-color">
			  	  <?php
					/*global $ngg, $nggdb; 
				  	$gid = get_post_meta(get_the_ID(), 'industries_gallery', true);
					$img_list = $nggdb->get_gallery($gid);
					if ( ! $img_list ) $img_list = array();
					$indlist = '';
					foreach($img_list as $img) {
						$indlist .= 
						'<li>
							<div class="img">'.image_tag_resized($img->imageURL, 68, 45).'</div>
							<div class="text">
				  				<h3>'.$img->alttext.'</h3>
				  				<p>'.$img->description.'</p>
							</div>
							<div class="clear"></div>
						</li>';
					}
					if ($indlist) {
						echo $indlist;
						$bl_count++;
					}*/
					
					$reflist	= get_post_meta( get_the_ID(), 'keywords', true);
					if ( ! $reflist ) $reflist = array();
					$list 		= '';
					foreach ($reflist as $itm ) {
						$title 	= apply_filters('product_keyword_link_title'	, $itm['title']);
						$link	= apply_filters('product_keyword_link_URL'	, $itm['value']);
						if (intval($link)) $link = get_permalink(intval($link)); 
						if (empty($link)) {
							$list .= '<li><span>'.$title.'</span></li>';
						} else {
							$targ	= $itm['target']?'target="'.$itm['target'].'"':'';
							$list 	.= '<li><a href="'.$link.'" '.$targ.'>'.$title.'</a></li>';
						} 
					}
					$list = apply_filters('product_keyword_list', $list);
					if ( !empty($list)) {
						$bl_count++;
						echo apply_filters(
							'product_keyword_widget',
							'<div class="widget-list">
				    			<h2>'.apply_filters('product_keywords_widget_title', get_sb_block_title('keywords')).'</h2>
								<ul>
									'.$list.'
								</ul>
				  			</div>');
					}
					
					$reflist	= get_post_meta( get_the_ID(), 'custom', true);
					if ( ! $reflist ) $reflist = array();
					$list 		= '';
					foreach ($reflist as $itm ) {
						$title 	= apply_filters('product_custom_link_title'	, $itm['title']);
						$link	= apply_filters('product_custom_link_URL'	, $itm['value']);
						if (intval($link)) $link = get_permalink(intval($link)); 
						if (empty($link)) {
							$list .= '<li><span>'.$title.'</span></li>';
						} else {
							$targ	= $itm['target']?'target="'.$itm['target'].'"':'';
							$list 	.= '<li><a href="'.$link.'" '.$targ.'>'.$title.'</a></li>';
						} 
					}
					$list = apply_filters('product_custom_list', $list);
					if ( !empty($list)) {
						$bl_count++;
						echo apply_filters(
							'product_custom_widget',
							'<div class="widget-list">
				    			<h2>'.apply_filters('product_custom_widget_title', get_sb_block_title('custom')).'</h2>
								<ul>
									'.$list.'
								</ul>
				  			</div>');
					}					
					
					$reflist	= get_post_meta( get_the_ID(), 'industries', true);
					if ( ! $reflist ) $reflist = array();
					$list 		= '';
					foreach ($reflist as $itm ) {
						$title 	= apply_filters('product_industry_link_title'	, $itm['title']);
						$link	= apply_filters('product_industry_link_URL'	, $itm['value']);
						if (intval($link)) $link = get_permalink(intval($link)); 
						if (empty($link)) {
							$list .= '<li><span>'.$title.'</span></li>';
						} else {
							$targ	= $itm['target']?'target="'.$itm['target'].'"':'';
							$list 	.= '<li><a href="'.$link.'" '.$targ.'>'.$title.'</a></li>';
						} 
					}
					$list = apply_filters('product_industry_list', $list);
					if ( !empty($list)) {
						$bl_count++;
						echo apply_filters(
							'product_industry_widget',
							'<div class="widget-list">
				    			<h2>'.apply_filters('product_industry_widget_title', get_sb_block_title('industries')).'</h2>
								<ul>
									'.$list.'
								</ul>
				  			</div>');
					}

				  	$reflist	= get_post_meta( get_the_ID(), 'references', true);
					if ( ! $reflist ) $reflist = array();
					$list 		= '';
					foreach ($reflist as $itm ) {
						$title 	= apply_filters('product_reference_link_title'	, $itm['title']);
						$link	= apply_filters('product_reference_link_URL'	, $itm['value']);
						if ( empty($link) ) {
							$list .= '</ul><h3 style="font-weight: 600; color: #000">'.$title.'</h3><ul>';
						} else {
							if (intval($link)) $link = get_permalink(intval($link)); 
							$targ	= $itm['target']?'target="'.$itm['target'].'"':'';
							$list .= '<li><a href="'.$link.'" '.$targ.'>'.$title.'</a></li>';
						} 
					}
					$list = apply_filters('product_reference_list', $list);
					if ( !empty($list)) {
						$bl_count++;
						echo apply_filters(
							'product_reference_widget',
							'<div class="widget-list">
				    			<h2>'.apply_filters('product_reference_widget_title', get_sb_block_title('references') ).'</h2>
								<ul>
									'.$list.'
								</ul>
				  			</div>');
					}
					$reflist	= get_post_meta( get_the_ID(), 'downloads', true);
					if ( ! $reflist ) $reflist = array();
					$list 		= '';
					foreach ($reflist as $itm ) {
						$title 	= apply_filters('product_download_link_title'	, $itm['title']);
						$link	= apply_filters('product_download_link_URL'	, $itm['value']);
						if (intval($link)) $link = get_permalink(intval($link));
						if (empty($link)) {
							$list .= '<li><span>'.$title.'</span></li>';
						} else {
							$targ	= $itm['target']?'target="'.$itm['target'].'"':'';
							$list 	.= '<li><a href="'.$link.'" '.$targ.'>'.$title.'</a></li>';
						} 
					}
					$list = apply_filters('product_downloads_list', $list);
					if ( !empty($list)) {
						$bl_count++;

						echo apply_filters(
							'product_downloads_widget',
							'<div class="widget-list">
				    			<h2>'.apply_filters('product_downloads_widget_title', get_sb_block_title('downloads')).'</h2>
								<ul>
									'.$list.'
								</ul>
				  			</div>');
					}
				  ?>
				</div>
				<div class="bot-bg"><div>&nbsp;</div></div>
				<?php $res = ob_get_contents(); 
				
				ob_end_clean(); 
				if ($bl_count) echo $res;
				?>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			  </div>
			  
			</div>
		  </div>
		</div>
		<?php 
			error_reporting( E_ALL );
		  	$reflist	= get_post_meta( get_the_ID(), 'components', true);
			if ( ! $reflist ) $reflist = array();
			$list 		= '';
			foreach ($reflist as $itm ) {
				$title 	= apply_filters('product_component_title'	, $itm['title']);
				$ID		= $itm['value'];
				$_vals	= explode( '-', $ID);
				if ( count($_vals) == 1 && intval($ID) ) {
					$img    = get_product_thumbnail_src( $ID );
					$_link	= get_permalink($ID);
				} elseif ( count($_vals) == 2 && intval($_vals[1]) ) {
					$img	= get_taxonomy_thumb( $_vals[1], $_vals[0] );
					$_link  = get_term_link( (int) $_vals[1], $_vals[0] );
				} else {
					$_link	= $ID;
					$img    = $itm['additional_cell'];
				}
				$targ	= $itm['target']?'target="'.$itm['target'].'"':'';
				$list 	.= 
				'<li><div class="content-list">
					<div class="img-block-list">
						<a href="'.$_link.'" '.$targ.'>'.image_tag_resized($img, 149, 153).'</a>
					</div>
					<div class="tit-block-list">
					  <h3><a href="'.$_link.'" '.$targ.'>'.$title.'</a></h3>
					</div>
				</div></li>';				
			}
			if ( !empty($list)) {
				$_tt = (get_post_meta( get_the_ID(), 'hide_comp_title', true) != 'yes')
						?'<h2><span>'.apply_filters('product_component_widget_title', get_sb_block_title('components') ).'</span></h2>'
						:''
						;
				
				echo apply_filters(
					'product_component_widget',
					'<div class="widget-bootom cf">
		    			'.$_tt.'
		    			<div class="slider" id="product-component-slider">
							<div class="img-slider">
								<ul>
									'.$list.'
								</ul>
							</div>
						</div>
		  			</div>');
				  if ( count($reflist) > 4 ) 
				  echo '
					  <script type="text/javascript">
					  (function($){
					  	$( "#product-component-slider .img-slider" ).carousel({
					  		loop	: true,
					  		btnsPosition : "outside" ,
					  		dispItems:	4
					  	});
					  })(jQuery);
					  </script>		  			
		  			';
			}
		  ?>
		  
		  
		</div>

<?php endif; ?>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); 

}

?>

<?php

function show_sortable_metabox( $args = array() ){
	
	extract(wp_parse_args($args, array(
		'id'		=>	0,
		'name'		=>	'',
		'title'		=>	'',
		'labels'	=>	array( 'title', 'value', '', '' ),
		'hide_empty'=> 'false',
		'footer'	=> '',
		'buttons' 	=> '',
		'column_3' 	=> 0,
		'additional_cell'	=> '',
		'target_style' => 'display: none',
		'before_table' => '',
	))); 
	$data	= ($_t = get_post_meta( $id, $name, true))
			? $_t
			: array();
	$tbody	= '';
	$table_row	= new FileTemplateProcessor( 'tpl/metabox_sortable_row.tpl'); 
	foreach ( $data as $item ) {
		$item['title'] = esc_attr($item['title']);
		$item['value'] = esc_html($item['value']);
		$item['target'] = ('_blank' == @$item['target'])?'checked':'';
		$item['target_style'] = $target_style;
		if (! $column_3 ) {
			$item['additional_cell'] = '';
		} else {
			$item['additional_cell'] = '<textarea name="meta_'.$name.'_values2[]">'.esc_html(@$item['additional_cell']).'</textarea>';
		}	
		$item['name'] = $name;
		$tbody .= $table_row->apply( $item );
	}
	
	return hv_tpl_out( 'tpl/metabox_table_sortable.tpl', array(
		'title'		=>	$title,
		'table_id'	=>	$name,
		'name'		=>	$name,
		'th_title'	=>	$labels[0],
		'th_value'	=>	$labels[1],
		'th_target'	=>	$labels[2],
		'th_additional' => $labels[3],
		'additional_cell' => $additional_cell,
		'table_body'	=> $tbody,
		'hide_empty'	=> $hide_empty,
		'additional_buttons'=>	$buttons,
		'table_footer'	=> $footer,
		'target_style'	=> $target_style,
		'before_table'	=> $before_table
	)); 

}

function update_metabox_sortable( $id, $name ) {
	$data = array_combine($_POST["meta_${name}_titles"], $_POST["meta_${name}_values"]);
	$meta = array();
	if ( isset($_POST['metabox_title_' . $name]) ) {
		update_post_meta( $id, 'metabox_title_' . $name, $_POST['metabox_title_' . $name]);
	}
	update_post_meta( $id, 'show_'.$name.'_column_3' , @$_POST['show_'.$name.'_column_3']);
	foreach( $_POST["meta_${name}_titles"] as $key => $val) {
		$new_entry = array(
				'title'	=> stripslashes($val),
				'value'	=> @stripslashes($_POST["meta_${name}_values"][$key]),
				'target' => @stripslashes($_POST["meta_${name}_targets"][$key])
			);
		if ( isset($_POST["meta_${name}_values2"][$key])) {
			$new_entry['additional_cell'] = stripslashes($_POST["meta_${name}_values2"][$key]);
		}
		if ( !empty($val)) {
			array_push($meta, $new_entry);
		}
	}
	update_post_meta( $id, $name, $meta);
}

function show_product_metabox( $entry ) {
	
	wp_nonce_field('sp_prod_param_nonce', 'sp_prod_param');
	if ($entry->post_parent) return;
	ob_start();
	$_type = get_post_meta( $entry->ID, 'inquiry_button_type', true );
	$_sel2 = ( $_type && 'custom' == $_type)?' checked="checked" ':'';
	$_sel3 = ( $_type && 'off' == $_type)?' checked="checked" ':'';
	$_sel1 = (!$_type || 'default' == $_type || (empty($_sel2) && empty($_sel3)))?' checked="checked" ':'';
	echo 
		'<input type="checkbox" value="yes" name="show_custom_layout" ',(('yes' == get_post_meta( $entry->ID, 'show_custom_layout', true))?'checked':''),'  /> Use custom HTML markup for this page. <br />'
		,'<input type="checkbox" value="yes" name="hide_right_column" ',(('yes' == get_post_meta( $entry->ID, 'hide_right_column', true))?'checked':''),'  /> Hide right column. <br /><br />'
		,'Inquiry button type: <input type="radio" value="default" name="inquiry_button_type"  ',$_sel1,' /> Default'
			, ' &nbsp; <input type="radio" value="custom" name="inquiry_button_type" ',$_sel2,' /> Custom'   
			, ' &nbsp; <input type="radio" value="off" name="inquiry_button_type" ',$_sel3,' /> Off <br />'   
		,'Inquiry button text: <input type="text" size="40" value="',get_post_meta( $entry->ID, 'inquiry_button_text', true ),'" name="inquiry_button_text" /><br />' 
		,'Inquiry button link: <input type="text" size="40" value="',get_post_meta( $entry->ID, 'inquiry_button_link', true ),'" name="inquiry_button_link" /><br />' 
		,'Custom content block: <br />';
	wp_editor( get_post_meta( $entry->ID , 'attached_custom_block', true), 'attached_custom_block', array() );
	echo '<h4>',__( 'Link to Video', 'SwissPhone'),'</h4>'
		,'<input type="text" name="video_thumbnail" size="50" value="',get_post_meta( $entry->ID, 'video_thumbnail', true ),'" /><br />' 
		,'<h4>',__( 'Image Gallery', 'SwissPhone'),'</h4>'
		,nggallery_select_dropdown( $entry->ID, 'attached_image_gallery')
		,'<br />'
		,'<input type="checkbox" value="yes" name="image_rotation_off" ',(('yes' == get_post_meta( $entry->ID, 'image_rotation_off', true))?'checked':''),'  /> Force image rotation off, <br />'
		//,'<h4>',__('Industries Gallery', 'SwissPhone'),'</h4>'
		//,nggallery_select_dropdown( $entry->ID, 'industries_gallery')
		//,'<br />'
		/*,'<h4>',__('Custom HTML', 'SwissPhone'),'</h4>'
		,'<br />'*/
		//,'<input type="checkbox" value="yes" name="show_button" ',(('yes' == get_post_meta( $entry->ID, 'show_button', true))?'checked':,'),'  /> Show "Inquiry about ..." button <br />'

		;
	$controls = ob_get_clean();
	$comp_list	=	'';
	$comps = get_posts(array(
		'post_type' => array('product','solution', 'service', 'page'),
		'showposts'	=> -1,
		'sort_column' => 'post_title'
	));
	foreach ( $comps as $comp ) {
		$comp_list .= '<option value="'.$comp->ID.'">'.$comp->post_title.'</option>';
	}
	
	$all_terms = get_terms(array(
		'service_category', 'solution_category', 'product_category', 'news_category', 'category' 
	), array(
		'hide_empty' => 0
	));
	foreach ( $all_terms as $term ) {
		$comp_list .= '<option value="'.$term->taxonomy.'-'.$term->term_id.'" >'.remove_brs($term->name).' (category) </option>';
	} 
	
	
	$int_list = '';
	$ipages = new WP_Query(array(
		'post_type' => array('product','solution', 'service', 'page'),
		'showposts'	=> -1,
		'sort_column' => 'post_title'
	));
	foreach ( $ipages->posts as $comp ) {
		$int_list .= '<option value="'.$comp->ID.'">'.$comp->post_title.'</option>';
	} 
	
	$int_selector = '<select class="internal-page-selector">
						'.$int_list.'
					</select>';

	($_tech_column_count = get_post_meta( $entry->ID, 'show_tech_specs_column_3', true ))
	||
	($_tech_column_count = 2);
	
	$sortable_data = array(
		array(
			'name'	=>	'tech_specs',
			'title'	=>	__('Technical Specifications', 'SwissPhone'),
			'labels'=>	array( 'title', 'value1', '', 'value2' ),
			'column_3' => 1, 
			'before_table' => 
				'<br />'.__('Number of Columns', 'SwissPhone')
				.'<input type="radio" name="show_tech_specs_column_3" value="1" '.((1==$_tech_column_count)?'checked':'').' />1 '
				.'<input type="radio" name="show_tech_specs_column_3" value="2" '.((2==$_tech_column_count)?'checked':'').' />2 '
				.'<input type="radio" name="show_tech_specs_column_3" value="3" '.((3==$_tech_column_count)?'checked':'').' />3 '
				.'<br />',
			'additional_cell' => '<textarea name="meta_tech_specs_values2[]"></textarea>',
		),
		array(
			'name'	=>	'keywords',
			'title'	=>	__('Keywords', 'SwissPhone'),
			'labels'=>	array( 'title', 'URL', 'target', '' ),
			'target_style'	=> '',
		),
		array(
			'name'	=>	'custom',
			'title'	=>	__('Custom', 'SwissPhone'),
			'labels'=>	array( 'title', 'URL', 'target', '' ),
			'target_style'	=> '',
		),		
		array(
			'name'	=>	'downloads',
			'title'	=>	__('Downloads', 'SwissPhone'),
			'labels'=>	array( 'title', 'URL or ID', 'target', '' ),
			'target_style'	=> '',
			'buttons'=> '<input type="button" class="select-dld button" value="select" />'  
		),
		array(
			'name'	=>	'industries',
			'title'	=>	__('Industries', 'SwissPhone'),
			'labels'=>	array( 'title', 'URL or ID', 'target', '' ),
			'buttons' => $int_selector,
			'target_style'	=> ''
		),
		array(
			'name'	=>	'references',
			'title'	=>	__('References', 'SwissPhone'),
			'labels'=>	array( 'title', 'URL or ID', 'target', '' ),
			'buttons' => $int_selector,
			'target_style'	=> ''
			
		),		
		array(
			'name'	=>	'components',
			'before_table' => 
				'<br /><input type="checkbox" name="hide_comp_title" value="yes" '
				.((get_post_meta( $entry->ID, 'hide_comp_title', true )=='yes')?'checked':'')
				.' /> '.__('Hide title', 'SwissPhone').'<br />',
			'title'	=>	__('Components', 'SwissPhone'),
			'labels'=>	array( 'title', 'ID or URL', 'target', 'Custom image' ),
			'buttons'=> '
				<select id="new-comp-selector">
					'.$comp_list.'
				</select>
			',
			'column_3'		  => 1,
			'target_style'	  => '',
			'additional_cell' => '<textarea name="meta_components_values2[]"></textarea>',
		)
	);
	
	foreach ( $sortable_data as $i_data ) {
		$i_data['id'] = $entry->ID;
		($_ttl = get_post_meta( $entry->ID, 'metabox_title_' . $i_data['name'], true ))
		||
		($_ttl = $i_data['title']);
		if ( in_array($i_data['name'], array( 'keywords', 'custom' )) ) {
			$i_data['title'] = '<input type="text" size="50" value="'.$_ttl.'" name="metabox_title_' . $i_data['name']. '" /> (<small>' . $i_data['title'] . '</small>)' ;
		} else {
			$i_data['title'] = '<input type="hidden" value="" name="metabox_title_' . $i_data['name']. '" />' . $i_data['title'];
		}
		$controls .= show_sortable_metabox($i_data);
	}
	
		
	$box_tpl	= new FileTemplateProcessor('tpl/metabox.tpl', array(
		'controls'	=>	$controls,
		'base_folder_url'	=> get_bloginfo('template_url')
	));
	echo $box_tpl->apply();

}

function nggallery_select_dropdown( $id, $name ){
	global $nggdb;
	$res = '<select name="'.$name.'">';
	$gallerylist = $nggdb->find_all_galleries();
	$sel_gal = intval(get_post_meta($id, $name, true));
	$res .= '<option value="0" '.($sel_gal==$gal->gid?'selected':'').' >'. __('none','SwissPhone') . '</option>';
	foreach ($gallerylist as $gal) {
		$res .= '<option value="'.$gal->gid.'" '.($sel_gal==$gal->gid?'selected':'').' >'. $gal->title . '</option>';
	}
	$res .= '</select>';
	return $res;
}

function show_dealer_metabox( $dealer ) {
	
	wp_nonce_field('sp_dealer_param_nonce', 'sp_dealer_param');
	$custom_data	=	get_post_custom($dealer->ID);
	echo '<table>
		<tr>
			<td>Zipcode:</td>
			<td><input type="text" size="10" name="zipcode" value="'.esc_attr($custom_data['zipcode'][0]).'" /></td>
		</tr>
		<tr>
			<td>Country:</td>
			<td><select name="country">'.list_countries( $custom_data['country'][0], true).'</select></td>
		</tr>
        <tr>
			<td>State:</td>
			<td><input type="text" name="state" size="50" value="'.esc_attr($custom_data['state'][0]).'" /></td>
		</tr>
		<tr>
			<td>City:</td>
			<td><input type="text" name="city" size="50" value="'.esc_attr($custom_data['city'][0]).'" /></td>
		</tr>		
		<tr>
			<td>Address:</td>
			<td><input type="text" name="address" size="50" value="'.esc_attr($custom_data['address'][0]).'" /></td>
		</tr>
		<tr>
			<td>Phone:</td>
			<td><input type="text" name="phone" size="30" value="'.esc_attr($custom_data['phone'][0]).'" /></td>
		</tr>
		<tr>
			<td>Fax:</td>
			<td><input type="text" name="fax" size="30" value="'.esc_attr($custom_data['fax'][0]).'" /></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="email" size="40" value="'.esc_attr($custom_data['email'][0]).'" /></td>
		</tr>		
		<tr>
			<td>Web:</td>
			<td><input type="text" name="web" size="50" value="'.esc_attr($custom_data['web'][0]).'" /></td>
		</tr>
		<tr>
			<td>Portfolio:</td>
			<td><textarea style="width: 400px; height: 300px;" name="portfolio" >'.esc_textarea($custom_data['portfolio'][0]).'</textarea></td>
		</tr>		
				
	</table>';
	
}

function sp_save_custom_data( $entry_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $entry_id;
	if ( in_array($_POST['post_type'], array('solution', 'product')) ) {
		if ( ! wp_verify_nonce( $_POST['sp_prod_param'], 'sp_prod_param_nonce' ) ) return $entry_id;
		
		foreach ( array('tech_specs', 'keywords', 'references', 'downloads', 'components', 'industries') as $tname	) 
		{
			update_metabox_sortable($entry_id, $tname);		
		}
		
		update_post_meta($entry_id, 'attached_image_gallery', intval($_POST['attached_image_gallery']));
		update_post_meta($entry_id, 'attached_custom_block', stripslashes($_POST['attached_custom_block']));
		update_post_meta($entry_id, 'industries_gallery', intval($_POST['industries_gallery']));
		foreach ( array('video_thumbnail', 'video_thumbnail', 'video_thumbnail' ,'video_thumbnail', 
					'inquiry_button_type', 'inquiry_button_text', 'inquiry_button_link', 'show_custom_layout', 'hide_right_column' ) 
					as $_fname ) {
			update_post_meta($entry_id, $_fname, $_POST[$_fname] );
		}
	} elseif ( $_POST['post_type'] == 'dealer' ) {
		if ( ! wp_verify_nonce( $_POST['sp_dealer_param'], 'sp_dealer_param_nonce' ) ) return $entry_id;
		delete_post_meta($entry_id, 'coord_lng');
		delete_post_meta($entry_id, 'coord_lat');
		foreach( array( 'phone', 'address', 'zipcode', 'country', 'state', 'fax', 'email', 'web', 'portfolio', 'city' ) as $fname )
			update_post_meta($entry_id, $fname, stripslashes($_POST[$fname]) );
		

		
	} elseif( $_POST['post_type'] == 'homepage' ) {
		if ( ! wp_verify_nonce( $_POST['sp_homepage_param'], 'sp_homepage_param_nonce' ) ) return $entry_id;
		foreach( array( 'phone', 'fb_url', 'twitter_url', 'rss_url','random_order' ) as $fname )
			update_post_meta($entry_id, $fname, stripslashes(@$_POST[$fname]) );
	} elseif ( $_POST['post_type'] == 'news' ) {
		update_post_meta( $entry_id, 'is_news', ('yes' == $_POST['is_news'])?'yes':'no' );
		update_post_meta( $entry_id, 'always_on_top', ('yes' == $_POST['always_on_top'])?'yes':'no' );
		update_post_meta( $entry_id, 'unlimited_event', ('yes' == $_POST['unlimited_event'])?'yes':'no' );
		update_post_meta( $entry_id, 'date_start', strtotime($_POST['date_start']) );
		update_post_meta( $entry_id, 'date_end', strtotime($_POST['date_end']) );
		foreach( $_POST['news_meta'] as $key => $value ) {
			update_post_meta( $entry_id, $key, stripslashes(strip_tags($value)) );
		}
	}
	return $entry_id;
}

function add_admin_hv_style() {
	wp_enqueue_style( 'hv-admin-style', get_bloginfo('template_url') . '/css/admin.css' );	
}

function show_homepage_metabox( $page ) {
	wp_nonce_field('sp_homepage_param_nonce', 'sp_homepage_param');
	$custom_data	=	get_post_custom($page->ID);
	echo '<table>
		<tr>
			<td>'.__('Random banners order:', 'SwissPhone').'</td>
			<td><input type="checkbox" name="random_order" value="yes" '.((@$custom_data['random_order'][0] == 'yes')?'checked':'').' /></td>
		</tr>	
		<tr>
			<td>'.__('Phone:', 'SwissPhone').'</td>
			<td><input type="text" size="50" name="phone" value="'.esc_attr(@$custom_data['phone'][0]).'" /></td>
		</tr>
		<tr>
			<td>'.__('Facebook URL:', 'SwissPhone').'</td>
			<td><input type="text" size="50" name="fb_url" value="'.esc_attr(@$custom_data['fb_url'][0]).'" /></td>
		</tr>
		<tr>
			<td>'.__('Twitter URL:', 'SwissPhone').'</td>
			<td><input type="text" size="50" name="twitter_url" value="'.esc_attr(@$custom_data['twitter_url'][0]).'" /></td>
		</tr>	
		<tr>
			<td>'.__('RSS URL:', 'SwissPhone').'</td>
			<td><input type="text" size="50" name="rss_url" value="'.esc_attr(@$custom_data['rss_url'][0]).'" /></td>
		</tr>
	</table>';
}

function show_news_metabox( $news ) {
	$meta = get_post_custom( $news->ID );
	( $date_start = @$meta['date_start'][0] ) || ( $date_start = time() + 86400 );
	( $date_end = @$meta['date_end'][0] ) || ( $date_end = time() + 86400 );
	?>
	<table>
		<tr>
			<td><?php _e('Show as Event', 'SwissPhoneAdmin'); ?></td>
			<td><input type="checkbox" name="is_news" value="yes" <?php if ('yes' == $meta['is_news'][0] ) echo 'checked="checked"'; ?> /></td>
		</tr>
		<tr>
			<td><?php _e('Always on Top', 'SwissPhoneAdmin'); ?></td>
			<td><input type="checkbox" name="always_on_top" value="yes" <?php checked('yes',$meta['always_on_top'][0]); ?> /></td>
		</tr>
		<tr>
			<td><?php _e('Unlimited event', 'SwissPhoneAdmin'); ?><br /><small><?php _e('Uses current date as event date.', 'SwissPhoneAdmin'); ?></small></td>
			<td><input type="checkbox" class="unlim-switcher" name="unlimited_event" value="yes" <?php checked('yes',$meta['unlimited_event'][0]); ?> /></td>
		</tr>					
		<tr class="unlim-switch">
			<td><?php _e('Event Start', 'SwissPhoneAdmin'); ?></td>
			<td><input class="datepicker" type="text" name="date_start" value="<?php echo date('d.m.Y', $date_start ); ?>" /></td>
		</tr>
		<tr class="unlim-switch">
			<td><?php _e('Event End', 'SwissPhoneAdmin'); ?></td>
			<td><input class="datepicker" type="text" name="date_end" value="<?php echo date('d.m.Y', $date_end ); ?>" /></td>
		</tr>
		<?php foreach ( 
				array( 	'country' 	=> __('Country', 'SwissPhoneAdmin'), 
						'location' 	=> __('Location', 'SwissPhoneAdmin'), 
						'place' 	=> __('Place', 'SwissPhoneAdmin'), 
						'website'	=> __('Web Address', 'SwissPhoneAdmin'),
					) as $key => $title ) : 
		?>
		<tr>
			<td><?php echo $title; ?></td>
			<td><input type="text" name="news_meta[<?php echo $key; ?>]" 
				value="<?php echo esc_attr($meta[$key][0]); ?>"  style="width: 300px;" /></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<script src="<?php bloginfo('template_url'); ?>/js/jquery-ui-datepicker.min.js"></script>
	<script type="text/javascript">
		jQuery(function($){
			$('input.datepicker').datepicker({dateFormat: 'dd.mm.yy'});
			function check_unlim_switch( obj ) {
				if ( $(obj).is(':checked') ) { 
					$('.unlim-switch').hide();
				} else {
					$('.unlim-switch').show();
				} 
			}
			check_unlim_switch($('input.unlim-switcher').change(function(){
				check_unlim_switch( this );
			}));
		});
	</script>
	<?php
}

add_action( 'admin_init', 'add_admin_hv_style' );
add_action( 'admin_menu', 'add_admin_options' );

add_action( 'save_post', 'sp_save_custom_data' );

function add_admin_options() {
	add_options_page('Slider options', 'Slider Options', 'manage_options', 'hivista_slider', 'hivista_slider_options');
}

function hivista_slider_options() {
	echo '<div class="wrap">';
	$opts = get_option( 'hv_slider_single', array() );
	$tn = wp_verify_nonce($_POST['hv_slider_opts_nonce'], 'hv_slider_options');
	//var_dump($tn);
	if ($tn) {
		foreach( array('transition_time', 'slide_interval','transition_time_h', 'slide_interval_h') as $fname ) {
			if (isset($_POST[$fname])) $opts[$fname] = $_POST[$fname];
		}
		update_option('hv_slider_single',$opts);
		/*echo '
			<div class="updated">
				<p>Settings updated. </p>
			</div>';*/
	}
	$defaults = array(
		'transition_time' => '500',
		'slide_interval'  => '5000',
		'transition_time_h' => '500',
		'slide_interval_h'  => '5000'
	);
	//var_dump($opts);
	$opts += $defaults;
	//var_dump($opts);
	echo '
	<h2>Slider options</h2>
	<form action="'.add_query_arg('updated','1').'" method="post">';
	wp_nonce_field('hv_slider_options', 'hv_slider_opts_nonce');
	echo '
	<table>
		<tr><td colspan="2"><h3>Single product/solution slider</h3></td></tr>
		<tr>
			<td>Transition duration (ms)</td>
			<td><input type="text" name="transition_time" size="10" value="'.esc_attr($opts['transition_time']).'" /></td>
		</tr>
		<tr>
			<td>Transition interval (ms)</td>
			<td><input type="text" name="slide_interval" size="10" value="'.esc_attr($opts['slide_interval']).'" /></td>
		</tr>
		<tr><td colspan="2"><h3>Homepage slider</h3></td></tr>
		<tr>
			<td>Transition duration (ms)</td>
			<td><input type="text" name="transition_time_h" size="10" value="'.esc_attr($opts['transition_time_h']).'" /></td>
		</tr>
		<tr>
			<td>Transition interval (ms)</td>
			<td><input type="text" name="slide_interval_h" size="10" value="'.esc_attr($opts['slide_interval_h']).'" /></td>
		</tr>
		<tr><td colspan="2"><input type="submit" value="save" /></td></tr>
	</table>
	</form>
		';
}

add_action( 'admin_enqueue_scripts', 'sp_admin_scripts' );
function sp_admin_scripts() {
	wp_enqueue_style( 'jquery-ui-blitzer', get_bloginfo('template_url') . '/css/blitzer/jquery-ui-custom.css' );
}

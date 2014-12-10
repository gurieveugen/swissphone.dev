<?php
class HV_Slider2 {

	var $version  	= 1.2;
	var $compat		= 'SwissPhone';
	var $sizes 		= array();
	var $cur_size 	= 'default';
	var $group 		= '';
	var $pattern_all = '';
	var $pattern_slide = '';
	var $pattern_control = '';
	var $parameters = array();

	function HV_Slider2($w,$h){
		
		$labels = array(
		    'name' 			=>	_x('Slides', 'post type general name'),
		    'singular_name' => 	_x('Slide', 'post type singular name'),
		    'add_new' 		=> 	__('Add New', 'SwissPhone'),
		    'add_new_item' 	=> 	__('Add New Slide', 'SwissPhone'),
		    'edit_item' 	=> 	__('Edit Slide', 'SwissPhone'),
		    'new_item' 		=> 	__('New Slide', 'SwissPhone'),
		    'view_item' 	=> 	__('View Slide', 'SwissPhone'),
		    'search_items' 	=> 	__('Search Slides', 'SwissPhone'),
		    'not_found' 	=>  __('No Slides found', 'SwissPhone'),
		    'not_found_in_trash'=> __('No deleted Slides found', 'SwissPhone'),
		    'parent_item_colon' => ''
		  );
		  $args = array(
		    'labels' 	=> $labels,
		    'public' 	=> false,
		    'publicly_queryable' => false,
		    'show_ui' 	=> true,
		    'query_var' => false,
		    'rewrite' 	=> false,
		    'capability_type' => 'post',
		    'hierarchical' 		=> true,
		    'supports' 	=> array('thumbnail','title', 'page-attributes')
		  );
		register_post_type('home_slide',$args);

		$this->add_size($this->cur_size,$w,$h);
		add_action('admin_init',array(&$this,'setupMetaboxes'));
		add_action('wp_head',array(&$this,'addHeader'));
	}

	function add_size($nm,$w,$h) {
		$this->sizes[$nm] = array($w, $h);
	}

	function add_parameter($sName,$sTitle,$xDefault) {
		$this->parameters[] = array(
								'name' 	  => $sName,
								'title'	  => $sTitle,
								'default' => $xDefault
							);
	}

	function run($return = false){
		$slides = new WP_Query(array(
			'post_type'			=>'home_slide',
			'posts_per_page'	=> -1,
			'country'			=> get_geo_slug(),
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC'
		));
		$slides = $slides->posts;
		$sControls = '';
		$sSlides = '';
		$cnt = 1;
		if (is_array($slides)) {
			foreach ($slides as $sl) {
				$orig_id = $sl->ID;
				$url = get_post_thumb_src($sl->ID);
				$is_term = false;
				if ($pid = get_post_meta($sl->ID, 'product_service_solution', true)) {
					if (intval($pid)) {
						$sl = get_post($pid);
						if (!$url) $url = get_product_thumbnail_src($pid);
					} elseif (stripos($pid, 'post') !== false) {
						$pid = intval(str_ireplace('post-', '', $pid)); 
						$sl  = get_post($pid);
						if (!$url) $url = get_product_thumbnail_src($pid);
					} elseif (stripos($pid, 'product') !== false) {
						$pid = intval(str_ireplace('product-', '', $pid));
						$sl  = get_term($pid, 'product_category');
						$is_term = true;
					} elseif (stripos($pid, 'solution') !== false) {
						$pid = intval(str_ireplace('solution-', '', $pid));
						$sl  = get_term($pid, 'solution_category');
						$is_term = true;
					} elseif (stripos($pid, 'service') !== false) {
						$pid = intval(str_ireplace('service-', '', $pid));
						$sl  = get_term($pid, 'service_category');
						$is_term = true;
					}
					if ($is_term) {
						$sl->ID = $sl->term_id;
						$sl->post_title = force_brs($sl->name);
						if (!$url) $url = get_taxonomy_thumb($sl->term_id);
					}
				}
				$aSearch = array();
				$aSearch['%TITLE%']  = $tit = $sl->post_title;
				//$aSearch['%CONTENT%']= $sl->post_content;
				$aSearch['%ID%']= 'slide-element-'.$sl->ID;
				$aSearch['%CNT%']= $cnt;
				$aSearch['%IMG%']= image_tag_resized($url, $this->sizes[$this->cur_size][0], $this->sizes[$this->cur_size][1], $tit);
				if ($is_term) {
					$aSearch['%PARAMETER_LINK_URL%'] = get_term_link($sl, $sl->taxonomy);
				} elseif (($sl->post_type != 'home_slide') && !strlen($aSearch['%PARAMETER_LINK_URL%'])) {
					$aSearch['%PARAMETER_LINK_URL%'] = get_permalink($sl->ID);
				}
				foreach ($this->parameters as $aParam) {
					$val = get_post_meta($orig_id,'slider_param_'.$aParam['name'],true);
					if (empty($val)) $val = $aParam['default'];
					if (empty($val) && strtoupper($aParam['name']) == 'LINK_URL') continue;
					$aSearch['%PARAMETER_'.strtoupper($aParam['name'])."%"] = trim($val);
				}
				
				$sSlides   .= str_replace(array_keys($aSearch),array_values($aSearch),$this->pattern_slide);
				$sControls .= str_replace(array_keys($aSearch),array_values($aSearch),$this->pattern_control);
				if ( ++$cnt > 30) break;
			}
		}
		$res = str_replace(array('%SLIDES%','%CONTROLS%'),array($sSlides,$sControls),$this->pattern_all);
		if (empty($slides)) $res = '';
		if ($return) return	$res;
		echo $res;
		return true;
	}

	function start(){
		ob_start();
	}

	function replace_tag_pair($tag = 'SLIDE', &$pat = '') {
		$break = explode("%{$tag}_START%",$pat);
		if (count($break)<2) return '';
		$break_2 = explode("%{$tag}_END%",$break[1]);
		$cont = $break_2[0];
		$pat = str_replace("%{$tag}_START%{$cont}%{$tag}_END%","%{$tag}S%",$pat);
		return $cont;
	}

	function end($return = false){
		$pat = ob_get_contents();
		ob_end_clean();
		$this->pattern_slide = $this->replace_tag_pair('SLIDE',$pat);
		$this->pattern_control = $this->replace_tag_pair('CONTROL',$pat);
		$this->pattern_all = $pat;
		return $this->run($return);
	}


	function addHeader() {
		if (!is_front_page()) return;
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.small-slider .img-slider').carousel({
					btnsPosition : 'outside',
					dispItems	: 5
				});
			});
		</script>
		<?php
		
	}

	function setupMetaboxes() {
		add_meta_box('hv_slider2_box','Slide Parameters',array(&$this,'showMetabox'),'home_slide','normal','high');
		add_action('save_post', array(&$this,'saveSlide'));
	}

	function saveSlide($nPostID) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $nPostID;
		if ( 'home_slide' != $_POST['post_type'] ) return $nPostID;
		if ( !wp_verify_nonce(@$_POST['slide_save_nonce'],'slide_save') ) return $nPostID;
		foreach ($this->parameters as $aParam) :
			update_post_meta($nPostID,'slider_param_'.$aParam['name'],$_POST['slider_param_'.$aParam['name']]);
		endforeach;
		update_post_meta($nPostID, 'product_service_solution', $_POST['product_service_solution']);
		if ( isset($_POST['existing_page_select']) && !empty($_POST['existing_page_select']) ) {
			update_post_meta($nPostID,'slider_param_link_url', $_POST['existing_page_select']);
		}
	}

	function showMetabox($oPost) {
		wp_nonce_field('slide_save','slide_save_nonce');
		?>
		<table style="width: 100%">
		<?php foreach ($this->parameters as $aParam) :
				echo "<tr>";
				echo "<td style='width: 150px;'>{$aParam['title']}</td>";
				$cur = get_post_meta($oPost->ID,'slider_param_'.$aParam['name'],true);
				if (empty($cur)) $cur = $aParam['default'];
				echo '<td><input type="text" style="width: 100%" name="slider_param_'.$aParam['name'].'" value="'.$cur.'" /></td>';
				echo "</tr>";
			endforeach;
			if (0 == count($this->parameters)) {
				echo "<tr><td>No Options available</td></tr>";
			}
		?>
		<tr>
			<td>Use product,service or solution as slide:</td>
			<td><?php $this->show_product_selector(get_post_meta($oPost->ID,'product_service_solution',true)) ?></td>
		</tr>
		<?php
			$int_list = '';
			$ipages = new WP_Query(array(
				'post_type' => array('product','solution', 'service', 'page', 'post', 'news'),
				'showposts'	=> -1,
				'orderby'   => 'title',
				'order'		=> 'ASC'
			));
			$cur = get_post_meta($oPost->ID,'slider_param_link_url',true);
			foreach ( $ipages->posts as $comp ) {
				$url = get_permalink($comp->ID);
				$sel = ($cur == $url)?'selected':'';
				$int_list .= '<option value="'.esc_attr($url).'" '.$sel.' >'.$comp->post_title.' - ('.$comp->post_type.') </option>';
			} 
			$all_terms = get_terms(array(
				'service_category', 'solution_category', 'product_category', 'news_category', 'category' 
			), array(
				'hide_empty' => 0
			));
			foreach ( $all_terms as $term ) {
				$url = get_term_link($term);
				$sel = ($cur == $url)?'selected':'';
				$int_list .= '<option value="'.esc_attr($url).'" '.$sel.' >'.remove_brs($term->name).' (category) </option>';
			} 
			
		?>
		<tr>
			<td><?php _e('Link to page', 'SwissPhone'); ?></td>
			<td>
				<select name="existing_page_select">
					<option value=""><?php _e('Custom link', 'SwissPhone'); ?></option>
					<?php echo $int_list; ?> 
				</select>
			</td>
		</tr>	
		</table>
		<?php
	}
	
	function show_product_selector($cur = 0) {
		echo '<select name="product_service_solution">';
		echo '<option value="0"></option>';
		$ps = new WP_Query(array(
			'post_type' => array('product','service','solution'),
			'showposts' => -1,
			'orderby'   => 'title',
			'order'		=> 'ASC'
		));
		foreach($ps->posts as $ps) {
			$act = '';
			$id = 'post-' . $ps->ID;
			if ( $cur == $ps->ID || $cur == $id ) $act = 'selected';
			echo '<option value="'.$id.'" '.$act.' >'.$ps->post_title.'</option>';
		}
		foreach( array( 'service', 'solution', 'product' ) as $pt ) {
			
			$all_terms = get_terms( $pt . '_category', array('hide_empty' => 0));
			foreach ( $all_terms as $term ) {
				$id  = $pt .'-' . $term->term_id;
				$sel = ($cur == $id)?'selected':'';
				echo '<option value="'.$id.'" '.$sel.' >'.remove_brs($term->name).' ( '.$tp.' category ) </option>';
			}
			
		}
		echo '</select>';
	}

}

global $HVSliderB;
$HVSliderB = new HV_Slider2(162,167);
$HVSliderB->add_parameter( 'link_url' , __('slide URL','SwissPhone'), '');


<?php
class HV_Slider {

	var $version  	= 1.2;
	var $compat		= 'SwissPhone';
	var $sizes 		= array();
	var $cur_size 	= 'default';
	var $group 		= '';
	var $pattern_all = '';
	var $pattern_slide = '';
	var $pattern_control = '';
	var $parameters = array();

	function HV_Slider($w,$h){
		
		$labels = array(
		    'name' 			=>	_x('Banners', 'post type general name'),
		    'singular_name' => 	_x('Banner', 'post type singular name'),
		    'add_new' 		=> 	__('Add New', 'SwissPhone'),
		    'add_new_item' 	=> 	__('Add New Banner', 'SwissPhone'),
		    'edit_item' 	=> 	__('Edit Banner', 'SwissPhone'),
		    'new_item' 		=> 	__('New Banner', 'SwissPhone'),
		    'view_item' 	=> 	__('View Banner', 'SwissPhone'),
		    'search_items' 	=> 	__('Search Banners', 'SwissPhone'),
		    'not_found' 	=>  __('No Banners found', 'SwissPhone'),
		    'not_found_in_trash'=> __('No deleted Banners found', 'SwissPhone'),
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
		    'supports' 	=> array('thumbnail','title','editor','page-attributes')
		  );
		register_post_type('top_slide',$args);

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
		$ord = get_current_homepage( 'random_order' );
		if ('yes' == $ord ) {
			$ord = 'rand';
		} else {
			$ord = 'menu_order';
		}
		
		$slides = new WP_Query(array(
			'post_type'			=>'top_slide',
			'posts_per_page'	=> 20,
			'country'			=> get_geo_slug(),
			'orderby'			=> $ord
		));
		
		
		$slides = $slides->posts;
		$sControls = '';
		$sSlides = '';
		$cnt = 1;
		if (is_array($slides)) foreach ($slides as $sl) {
				$cnt++;
				$aSearch = array();
				$aSearch['%TITLE%']  = $tit = $sl->post_title;
				$aSearch['%CONTENT%']= preg_replace('/<(\/?)([^>]*)>/im', '<$1span>', strip_tags($sl->post_content, '<h2><h3><h4><h5><h6>' ));
				$aSearch['%ID%']= 'slide-element-'.$sl->ID;
				$aSearch['%CNT%']= $cnt++;
				$aSearch['%IMG%']= image_tag_resized(get_post_thumb_src($sl->ID), $this->sizes[$this->cur_size][0], $this->sizes[$this->cur_size][1], $tit);
				foreach ($this->parameters as $aParam) {
					$val = get_post_meta($sl->ID,'slider_param_'.$aParam['name'],true);
					if (empty($val)) $val = $aParam['default'];
					$aSearch['%PARAMETER_'.strtoupper($aParam['name'])."%"] = $val;
				}
				$sSlides   .= str_replace(array_keys($aSearch),array_values($aSearch),$this->pattern_slide);
				$sControls .= str_replace(array_keys($aSearch),array_values($aSearch),$this->pattern_control);
		}
		$res = str_replace(array('%SLIDES%','%CONTROLS%'),array($sSlides,$sControls),$this->pattern_all);
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
		$opts = get_option( 'hv_slider_single', array('transition_time' => '500',
				'slide_interval'  => '5000',
				'transition_time_h' => '500',
				'slide_interval_h'  => '5000')
		);
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('div.big-slider .img-slider ul').cycle({
				//autoSlide		:	<?php echo (intval($opts['slide_interval_h']))?'true':'false'?>,
				/*nextBtn			:	'',
				prevBtn			:	'',
				loop			:	true,
				pagination		:	true,
				dispItems		:	1,*/
				timeout:  <?php echo intval($opts['slide_interval_h']); ?>,
				//combinedClasses	:	true,
				speed			:	<?php echo intval($opts['transition_time_h']); ?>,
				pager			:	'div.big-slider div.carousel-pagination',
				activePagerClass:   'active'
				/*effect			:	"fade",
				btnsPosition 	: 'outside'*/
			});
		});
		</script>
		<?php
		
	}

	function setupMetaboxes() {
		add_meta_box('hv_slider_box','Slide Parameters',array(&$this,'showMetabox'),'top_slide','normal','high');
		add_action('save_post', array(&$this,'saveSlide'));
	}

	function saveSlide($nPostID) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $nPostID;
		if ( 'top_slide' != $_POST['post_type'] ) return;
		foreach ($this->parameters as $aParam) :
			update_post_meta($nPostID,'slider_param_'.$aParam['name'],$_POST['slider_param_'.$aParam['name']]);
		endforeach;
		if ( @$_POST['existing_page_select'] && !empty($_POST['existing_page_select']) ) {
			update_post_meta($nPostID,'slider_param_link_url', $_POST['existing_page_select']);
		}
	}

	function showMetabox($oPost) {
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
					<option value=" "><?php _e('Custom link', 'SwissPhone'); ?></option>
					<?php echo $int_list; ?> 
				</select>
			</td>
		</tr>
		</table>
		<?php
	}

}

global $HVSlider;
$HVSlider = new HV_Slider(980,371);
$HVSlider->add_parameter( 'link_url' , __('CTA URL','SwissPhone'), '');
$HVSlider->add_parameter( 'link_text' , __('CTA text','SwissPhone'), '');


?>
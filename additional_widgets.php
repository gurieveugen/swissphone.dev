<?php 
class HV_Categories_Widget extends WP_Widget {

	function HV_Categories_Widget() {
		$widget_ops = array( 'classname' => 'hv-categories', 'description' => __('Displays category list for current entry type.') );
		$this->WP_Widget( 'hv-categories', __('Context Categories'), $widget_ops );
	}

	function widget( $args, $instance ) {
		
		extract( $args );
		
		$content	=	'';
  		global $GEO_location;
  		$terms = get_terms(	'news_category', array(
  			'taxonomy'		=>  'news_category',
			'hide_empty'	=>	false
		));
  		foreach( $terms as $term ) {
			$ps	= new WP_Query(array(
				'news_category'	=>	$term->slug,
				'country'		=> $GEO_location['term']->slug,
				'post_type'		=> 'news'
			));
			$list = '';
			foreach( $ps->posts as $news ) {
				$list .= '<li><a href="'.get_permalink($news->ID).'">'.apply_filters('widget_post_title',$news->post_title).'</a></li>';
			}
			if ( !empty($list)) {
				$content .= '<li><a href="'.get_term_link($term).'" class="category-title-link">'.remove_brs($term->name).'</a>
				<ul>'.$list.'</ul></li>';
			}
  		}
		
		if ( empty($content) ) return '';
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title'] );
		if ( $title ) echo $before_title . $title . $after_title;
		echo '<ul class="category_autoexpand">' . $content . '</ul>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( array('title') as $val ) {
			$instance[$val] = strip_tags( $new_instance[$val] );
		}
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 
			'title' 		=> 'title', 
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title"); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php 
	}

	function register_self() {
		register_widget( __CLASS__ );
	}
	
}

add_action( 'widgets_init', array( 'HV_Categories_Widget', 'register_self') );

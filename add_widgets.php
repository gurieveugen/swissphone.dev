<?php 
		
class HV_SP_Widget_Testimonial extends WP_Widget {

	function HV_SP_Widget_Testimonial() {
		$widget_ops = array('classname' => 'widget_testimonial', 'description' => __('User testimonial widget', 'SwissPhone'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('hv-sp-textimonial', __('Testimonial', 'SwissPhone' ), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		$clnt = apply_filters( 'widget_text', $instance['name'], $instance );
		$link = $instance['link'];
		echo $before_widget;
		echo '
		<div class="top-bg">&nbsp;</div>
		<div class="text">
		  <p><span class="left">&nbsp;</span>'.$text.'<span class="right">&nbsp;</span></p>
		  <p><a href="'.$link.'">'.$clnt.'</a></p>
		</div>
		<div class="bot-bg1">&nbsp;</div>
		<div class="bot-bg2">&nbsp;</div>
		';
		echo $after_widget;
	}

	/*function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		return $instance;
	}*/

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'name' => '', 'text' => '', 'link' => '' ) );
		$text = esc_textarea($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name:', 'SwissPhone'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" 
		name="<?php echo $this->get_field_name('name'); ?>" 
		type="text" value="<?php echo esc_attr($instance['name']); ?>" />
		</p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		
		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:', 'SwissPhone'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" 
		name="<?php echo $this->get_field_name('link'); ?>" 
		type="text" value="<?php echo esc_attr($instance['link']); ?>" />
		</p>
<?php
	}
} 

class HV_SP_Widget_Contacts extends WP_Widget {

	function HV_SP_Widget_Contacts() {
		$widget_ops = array('classname' => 'widget_contacts', 'description' => __('User testimonial widget', 'SwissPhone'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('hv-sp-contacts', __('Contact', 'SwissPhone' ), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title 	= apply_filters( 'widget_title', $instance['title'], $instance );
		$text	= apply_filters( 'widget_text', $instance['text'], $instance );
		$addr 	= array(); 
		foreach( explode("\n",$instance['addr']) as $add ) {
			$addr[] = '"' . esc_js($add) . '"';
		}
		echo $before_widget;
		echo $before_title . $title. $after_title;
		echo '<div class="text">';
		echo wpautop($text);
		?>
		<div id="contact_locator_canvas" style="width: 184px; height: 184px;"></div>	
		<script src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var $mark_list = [<?php echo implode(", \n",$addr); ?>];
				var $all_mark  = Array();
				var $processed = 0;
		       	geocoder = new google.maps.Geocoder();
				if (geocoder) {
					var opt =  {
				  		zoom: 15,
				  		center: new google.maps.LatLng( 0, 0),
				  		mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					var bounds = new google.maps.LatLngBounds();
					var $c_map = new google.maps.Map( document.getElementById('contact_locator_canvas'), opt );
					for (i in $mark_list) {
						geocoder.geocode( { 'address': $mark_list[i] } , function( results, status ) {
						  $processed++; 
					      if (status == google.maps.GeocoderStatus.OK) {
					      	$all_mark[$all_mark.length] = new google.maps.Marker({
				            	animation: google.maps.Animation.DROP,
				            	map: $c_map,
				            	position: results[0].geometry.location
			        		});
			        		bounds.extend(results[0].geometry.location);
			        		if ($processed == $mark_list.length)
			        			$c_map.fitBounds(bounds);
					      } else {
	
					      	 // alert('Geocode was not successful for the following reason: ' + status);
					      }
			    		});
			    	}
			    }
			});		    
			
		    </script>

		
		
		<?php
		echo '</div>';
		echo $after_widget;
		
	}

	/*function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		return $instance;
	}*/

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'addr' => '' ) );
		$text = esc_textarea($instance['text']);
		$addr = esc_textarea($instance['addr']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'SwissPhone'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
		name="<?php echo $this->get_field_name('title'); ?>" 
		type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		
		<p><label for="<?php echo $this->get_field_id('addr'); ?>"><?php _e('Addresses (one per line):', 'SwissPhone'); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('addr'); ?>" name="<?php echo $this->get_field_name('addr'); ?>"><?php echo $addr; ?></textarea>
		</p>
<?php
	}
} 


		
class HV_SP_News_Tabbed extends WP_Widget {

	function HV_SP_News_Tabbed() {
		$widget_ops = array( 'classname' => 'news-tabbed', 'description' => __( 'Tabbed News Widget for homepage', 'SwissPhone' ) );
		$control_ops = array( 'width' => 200, 'height' => 250 );
		$this->WP_Widget( 'news-tabbed', __('Tabbed News', 'SwissPhone'), $widget_ops, $control_ops );
	}
	/*
	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		$title_news = apply_filters('widget_title', __("Local News", "SwissPhone") );
		$title_int  = apply_filters('widget_title', __("International News", "SwissPhone") );
		$cnt		= $instance['news_count'];
		?>	
		<div class="tit-block" id="home-news-tabs">
		  <?php if (is_geo_local()) :  ?>
		  <div class="border">
		    <div class="left-top-bg">&nbsp;</div>
			<div class="right-top-bg">&nbsp;</div>
			<div class="right-bot-bg">&nbsp;</div>
			<h2><a href="#local-news-tab"><?php echo $title_news; ?></a></h2>
		  </div>
		  <?php endif; ?>
  		  <div class="border">
		    <div class="left-top-bg">&nbsp;</div>
			<div class="left-bot-bg">&nbsp;</div>
			<div class="right-top-bg">&nbsp;</div>
			<div class="right-bot-bg">&nbsp;</div>
			<h2><a href="#international-news-tab"><?php echo $title_int; ?></a></h2>
		  </div>
		  <div class="clear"></div>
		</div>
		<?php if (is_geo_local()) :  ?>
		<div id="local-news-tab" style="display: none">
			<ul>
				<?php 
					$loc_news = new WP_Query(array(
						'post_type'	=> 'news',
						'country'	=> get_geo_slug(),
						'posts_per_page'	=> $cnt
						
					));
					$exclude = array();
					foreach( $loc_news->posts as $news ) {
						setup_postdata($news);
						global $post_for_exr;
						$post_for_exr = $news;
						$exr 	= apply_filters('the_excerpt', get_the_excerpt());
						$link 	= get_permalink($news->ID);
						$exclude[] = $news->ID; 
						echo '<li>
							    <h3><a href="'.$link.'">'.$news->post_title.'</a></h3>
								<small>'.date('m.d/y', strtotime($news->post_date)).'</small>
								'.$exr.'
							  </li>';
					}
				?>
			</ul>
			<p class="read"><a href="<?php echo home_url('/news'); ?>"><?php _e('read all news', "SwissPhone"); ?></a></p>
		</div>
		<?php endif; ?>
		<div id="international-news-tab" style="display: none">
			<ul>
				<?php 
					$loc_news = new WP_Query(array(
						'post_type'	=> 'news',
						'post__not_in'	=> $exclude,
						'posts_per_page'	=> $cnt
					));
					foreach( $loc_news->posts as $news ) {
						setup_postdata($news);
						$exr 	= apply_filters('the_excerpt', get_the_excerpt());
						$link 	= get_permalink($news->ID); 
						echo '<li>
							    <h3><a href="'.$link.'">'.$news->post_title.'</a></h3>
								<small>'.date('m.d/y', strtotime($news->post_date)).'</small>
								'.$exr.'
							  </li>';
					}
				?>
			</ul>
			<p class="read"><a href="<?php echo home_url('/news/?local=international'); ?>"><?php _e('read all news', "SwissPhone"); ?></a></p>
		</div>
				
		<div class="bot-bg"><div>&nbsp;</div></div>
	  
	  <script type="text/javascript">
	   <?php if (is_geo_local()) :  ?>
	   	var $active_news_tab = "#local-news-tab";
	   <?php else: ?>
	   	var $active_news_tab = "#international-news-tab";
	   <?php endif; ?>
	    jQuery($active_news_tab).show();
	  	jQuery(function($){
	  		$('#home-news-tabs h2 a').click(function(){
	  			var $news_tab = $(this).attr('href');
	  			if ( $news_tab == $active_news_tab) return false;
	  			$($active_news_tab).hide();
	  			$($news_tab).show();
	  			$('.active-tab').removeClass('active-tab').addClass('inactive-tab');
	  			$(this).removeClass('inactive-tab').addClass('active-tab');
	  			$active_news_tab = $news_tab;
	  			return false
	  		});
	  	});
	  </script>
		<?php	
		echo $after_widget;
	}
	 *
	 */

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		$_term_ids = array();
		foreach ( array_values($instance['categories']) as $_tcat ) {
			$_term_ids[] = get_translated_id($_tcat, 'term_news_category');
		}
		if ( count($_term_ids) ) {
			$_term = get_term( $_term_ids[0], 'news_category' );
			$title_news = remove_brs($_term->name);
		} else {
			$title_news = apply_filters('widget_title', __("News", "SwissPhone") );
			$_terms = array();
		} 
		if ( !empty($instance['title']) ) {
			$title_news = apply_filters('widget_title', __( $instance['title'], "SwissPhone") );
		}
		$cnt		= intval($instance['news_count']);
		?>	
		<div class="tit-block" idtt="home-news-tabs">
		  <div class="border">
		    <div class="left-top-bg">&nbsp;</div>
			<div class="right-top-bg">&nbsp;</div>
			<div class="right-bot-bg">&nbsp;</div>
			<h2><?php echo $title_news; ?></h2>
		  </div>
		  <div class="clear"></div>
		</div>
		<div id="local-news-tab">
			<ul>
				<?php
				    if ( isset($_term) and in_array( $_term->slug , array( 'nachrichten-de', 'news-en', 'nouvelles-fr' ) ) and $em = available_emergency_news() ) foreach ( $em  as $news ) {
				        $link   = get_permalink($news->ID);
                        $exr    = $news->post_excerpt;
				        echo '<li>
                                <h3><a href="',$link,'"  style="color: #CC0033;">',$news->post_title,'</a></h3>
                                <span style="display: block; overflow: hidden;"><p>',$exr,'... <a href="',get_permalink($news->ID),'" class="more" >',__('more', 'SwissPhone'),'</a></p></span>
                              </li>';
				    }
					$args = array(
                        'post_type' => 'news',
                        'country'   => get_geo_slug(),
                        'posts_per_page' => -1,
                        'orderby'  => ($instance['order_by'] == 'date')?'date':'menu_order',
                        'order'  => ($instance['order'] == 'ASC')?'ASC':'DESC',
                    );
					
                    if ( isset($_term) and (false !== stripos($_term->slug, 'event') or false !== stripos($_term->slug, 'veranstaltung' ) or false !== stripos($_term->slug, 'evenemen')) ) {
                        $is_event = true;
                        $args['meta_key'] = 'date_start';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'ASC';
                    } else {
                        $is_event = false;
                    }
                    
                    $loc_news = new WP_Query( $args );
					
					
					if ( $is_event ) {
						$args['orderby'] = "post_date";
						$args['meta_key'] = "always_on_top";
						$args['meta_value'] = 'yes';
						$featured = get_posts( $args );
						$fids = array();
						foreach( $featured as $ftr ) {
							$fids[] = $ftr->ID;
						}
						for ( $i = 0; $i < $loc_news->post_count; $i++ ) {
							if ( in_array( $loc_news->posts[$i]->ID, $fids ) ) unset( $loc_news->posts[$i] );
						}
						$loc_news->posts = array_values(array_merge( $featured, $loc_news->posts ));
						$loc_news->post_count = count($loc_news->posts);
						$loc_news->rewind_posts();
					}
                    
					$show_date = ('yes' == @$instance['show_date']);
					$_ctime = time();
					foreach( $loc_news->posts as $news ) {
						
						if ( $is_event and get_post_meta( $news->ID, 'unlimited_event', true ) == 'yes' ) {
							$event_time = $_ctime; 
							$unlim_event = true;
						} else {
							$event_time = get_post_meta( $news->ID, 'date_start', true ); 
							$unlim_event = false;
						}
					    if ( $is_event && $_ctime > $event_time ) continue;
						if (!$cnt) break;
						if ( count($_term_ids) && ! is_object_in_term($news->ID, 'news_category', $_term_ids) ) {
							continue;
						}
						setup_postdata($news);
						global $post_for_exr;
						$post_for_exr = $news;
						($exr 	= get_post_meta( $mews->ID, 'html_excerpt', true ))
						||
						($exr 	= $news->post_excerpt );
						//$exr    = apply_filters('the_excerpt',apply_filters('get_the_excerpt', $exr ) );
						$exr  = nl2br(array_shift(explode('<!--wrap-->', wordwrap(strip_tags($exr), 100, '<!--wrap-->'))));
						
						$link 	= get_permalink($news->ID);
						if ( $unlim_event ) $exr = ''; 
						echo '<li>
							    <h3><a href="',$link,'">',$news->post_title,'</a></h3>
								<small>',$show_date?date(get_option('date_format'), strtotime($news->post_date)):'','</small>
								<span style="display: block; overflow: hidden;"><p>',$exr,'... <a href="',get_permalink($news->ID),'" class="more" >',__('more', 'SwissPhone'),'</a></p></span>
							  </li>';
						--$cnt;
					}
				?>
			</ul>
			<p class="read">
				<?php 
					$_link  = count($_term_ids)
							? get_term_link( $_term, 'news_category' )
							: home_url('/news');
				?>
				<a href="<?php echo $_link; ?>"><?php _e('All', "SwissPhone"); echo ' ', $title_news; ?></a>
			</p>
		</div>
		<div class="bot-bg"><div>&nbsp;</div></div>
		<?php	
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( array( 'order', 'order_by', 'title', 'news_count','categories', 'show_date') as $val ) {
			$instance[$val] = $new_instance[$val];
		}
		/*$_news_cat = get_terms('news_category', array(
			'taxonomy' 	=> 'news_category',
			'hide_empty'=> false
		));
		$_idlist = array();
		foreach( $_news_cat as $cat ) {
			$_idlist[] = $cat->term_id;
		}
		$_newlist = array();
		foreach( $old_instance['categories'] as $cat_id ) {
			if ( in_array($cat_id, $_idlist) || in_array($cat_id, $instance['categories']) ) {
				$_newlist[] = $cat_id;
			}
		}
		foreach ( $instance['categories'] as $cat_id ) {
			if ( ! in_array($cat_id, $_newlist) ) {
				$_newlist[] = $cat_id;
			}
		}
		$instance['categories'] = $_newlist;*/
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 
			'title'			=> '',
			'news_count' 	=> '2',
			'categories'	=> array(),
			'order_by'		=> 'date',
			'order'			=> 'DESC',
			'show_date'		=> 'no' 
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$_news_cat = get_terms('news_category', array(
			'taxonomy' 	=> 'news_category',
			'hide_empty'=> false
		));
		$_cur_cats = array();
		global $sitepress;
		foreach ( $instance['categories'] as $_tcat ) {
			$_cur_cats[] = get_translated_id($_tcat, 'term_news_category');
		}
		$ord = ($instance['order'] == 'ASC')?'ASC':'DESC';
		$sort= ($instance['order_by'] == 'date')?'date':'custom';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title", "SwissPhone"); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'news_count' ); ?>"><?php _e("Number of news", "SwissPhone"); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'news_count' ); ?>" name="<?php echo $this->get_field_name( 'news_count' ); ?>" value="<?php echo $instance['news_count']; ?>" style="width:100%;" />
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
				<?php _e('Show date : ','SwissPhone'); ?> &nbsp; 
			</label>
			<input type="radio" value="yes" id="<?php echo $this->get_field_id( 'show_date' ); ?>" <?php echo 'name="', $this->get_field_name( 'show_date' ), '" ', ('yes'== $instance['show_date'])?'checked="checked"':''; ?> /> Yes 
			<input type="radio" value="no" id="<?php echo $this->get_field_id( 'show_date' ); ?>" <?php echo 'name="', $this->get_field_name( 'show_date' ), '" ', ('yes'!= $instance['show_date'])?'checked="checked"':''; ?> /> No 
			<br />
			<label><?php _e('Display order', 'SwissPhone' ); ?></label><br />
			<input type="radio" value="date" <?php echo ($sort == 'date')?'checked="checked"':''; ?> name="<?php echo $this->get_field_name( 'order_by' ); ?>" /> By date
			<input type="radio" value="custom" <?php echo ($sort == 'custom')?'checked="checked"':''; ?> name="<?php echo $this->get_field_name( 'order_by' ); ?>" /> Manual order
			<br />
			<input type="radio" value="ASC" <?php echo ($ord == 'ASC')?'checked="checked"':''; ?> name="<?php echo $this->get_field_name( 'order' ); ?>" /> Ascedind
			<input type="radio" value="DESC" <?php echo ($ord == 'DESC')?'checked="checked"':''; ?> name="<?php echo $this->get_field_name( 'order' ); ?>" /> Desceding
			<br />
		</p>
		<p>
		<?php
		
			foreach ( $_news_cat as $_category ) {
				$_id = $_category->term_id;
				echo '<input name="'.$this->get_field_name( 'categories' ).'[]" type="checkbox" value="'.$_id.'" '.( in_array( $_id, $_cur_cats )?'checked':'' ).'/>'.remove_brs($_category->name).'<br />';
			}
		?>
		</p>
	<?php 
	}
}

class HV_SP_Widget_Recent_Posts extends WP_Widget {

	function HV_SP_Widget_Recent_Posts() {
		$widget_ops = array(
			'classname' 	=> 'hv_widget_recent_entries', 
			'description' 	=> __( "The most recent posts on your site", "SwissPhone") 
		);
		$this->WP_Widget('hv-recent-posts', __('Recent Posts (with date)', 'SwissPhone'), $widget_ops);
		$this->alt_option_name = 'hv_widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('hv_widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('posts_per_page' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
		echo $before_widget; 
		if ( $title ) echo $before_title . $title . $after_title; 
		echo '<ul>';
		while ($r->have_posts()) : $r->the_post(); 
			echo '<li><a 	href="'.get_permalink(get_the_ID()).'" 
							title="'.esc_attr(get_the_title() ? get_the_title() : get_the_ID()).'">';
			echo '<small>'.get_the_time('m.d.Y').'</small>';
			if ( get_the_title() ) 
				the_title(); 
			else 
				the_ID();
			echo '</a></li>';
		endwhile;
		echo '</ul>';
		echo $after_widget;
		
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('hv_widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['hv_widget_recent_entries']) )
			delete_option('hv_widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('hv_widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
 

function register_add_widgets() {
	register_widget( 'HV_SP_News_Tabbed' );
	register_widget( 'HV_SP_Widget_Testimonial' );
	register_widget( 'HV_SP_Widget_Recent_Posts' );
	register_widget( 'HV_SP_Widget_Contacts' );
}

add_action( 'widgets_init', 'register_add_widgets' );

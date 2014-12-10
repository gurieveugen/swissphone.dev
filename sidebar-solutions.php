<?php
/**
 * @package WordPress
 * @subpackage SwissPhone
 */
?>
	    <div class="widget-block left-menu">
		  <h2><span><?php _e('Categories','SwissPhone' ); ?></span></h2>
		  <ul class="category_autoexpand">
		  	<?php
		  		global $ptype; 
				if ( ! $ptype) {
					global $posts;
					$ptype = $posts[0]->post_type;
				}
				global $wp_query;
				if ( is_tax() && isset($wp_query->query_vars['service_category']) ) {
					$ptype = 'service';
				} 
				
				$terms = get_terms($ptype . '_category',array(
					'hide_empty'    => 0,
					'taxonomy'      => $ptype . '_category',
					//'orderby'       => 'term_order',
					'order'         => 'ASC'
				));
				($top_lvl = @$posts[0]->post_parent) || ($top_lvl = $posts[0]->ID);
				foreach($terms as $term) {
					if ( 'service' == $ptype ) {
						$plist = array( 'service', 'solution', 'products');
					} else {
						$plist = $ptype;
					}
					$sols = new WP_Query(array(
						'post_type'	=>	$plist,
						$ptype.'_category' => $term->slug,
						'country'	       => get_geo_slug(),
						'orderby'	       => 'menu_order',
						'order'		       => 'ASC',
						'posts_per_page'   => -1
					));
					$act 	= false;
					$list 	= '';
					foreach($sols->posts as $sol) {
						global $posts;
						$cls = (is_singular() && $top_lvl == $sol->ID)?'class="active"':'';
						$list.= '<li '.$cls.'  ><a href="'.get_permalink($sol->ID).'" >'.$sol->post_title."</a></li>\n";
						if ( $cls ) $act = true;
					}
					if ( is_tax() && get_query_var($ptype . '_category') == $term->slug ) $act=true;
					if ($list) {
						$list = '<ul '.($act?'':'style="display: none"').'>'.$list.'</ul>';
						echo '<li '.($act?'class="parent extended"':'').'><a href="'.get_term_link($term).'" class="category-title-link">'.force_brs($term->name).'</a>'.$list.'</li>';
					} 
				}
		  	?>
		  </ul>
		</div>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($ptype .'s-sidebar') ) : ?>	 
<?php endif; ?>


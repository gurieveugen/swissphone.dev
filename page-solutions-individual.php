<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Solutions Individual Page
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page solutions-individual">
	  <div class="left">
<?php include(TEMPLATEPATH . '/sidebar-solutions.php'); ?>	
	  </div>
	  
	  <div class="right">
<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <div class="cf">
			  <div class="left-entry">
			  <?php the_content(); ?>
			  </div>
			  
			  <div class="right-entry">
			    <ul class="big-img">
				  <li><img src="<?php bloginfo('template_url'); ?>/tets-img/test_10.gif" alt=" " /></li>
				</ul>
				
				<ul class="preview-img">
				  <li><a href="#"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_09.gif" /></a></li>
				  <li><a href="#"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_09.gif" /></a></li>
				  <li><a href="#"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_09.gif" /></a></li>
				  <li><a href="#"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_09.gif" /></a></li>
				</ul>
				
				<!-- <div class="bot-link"><span><a href="#">inquire about solution  <small>&nbsp;</small></a></span></div> -->
			  </div>
			  </div>
			  
			  <div class="cf">
			  <div class="left-entry technical-list">
			    <div class="tit-block">
		          <h2><span>Technical Specifications</span></h2>
		        </div>				
				<ul class="cf">
				  <li class="grey">
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li>
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li class="grey">
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li>
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li class="grey">
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li>
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li class="grey">
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				  <li>
				    <div class="name">Item Name</div>
					<div class="description">
					  <p>Description can be placed here. Quisque ullamcorper auctor tellus</p>
					</div>
					<div class="clear"></div>
				  </li>
				</ul>				
				<div class="bot-bg"><div>&nbsp;</div></div>
			  </div>
			  
			  <div class="right-entry">
			    <div class="tit-block">
		          <h2><span>Additional Information</span></h2>
		        </div>
				<div class="bg-color">
				  <div class="widget-industries">
				    <h2>Industries</h2>
					<ul class="cf">
					  <li>
					    <div class="img"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_08.jpg" alt=" " /></div>
						<div class="text">
						  <h3><a href="#">Industry title goes here</a></h3>
						  <p>Small description goes here in this place holder.</p>
						</div>
						<div class="clear"></div>
					  </li>
					  <li>
					    <div class="img"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_08.jpg" alt=" " /></div>
						<div class="text">
						  <h3><a href="#">Industry title goes here</a></h3>
						  <p>Small description goes here in this place holder.</p>
						</div>
						<div class="clear"></div>
					  </li>
					</ul>
				  </div>
				  
				  <div class="widget-list">
				    <h2>Reference</h2>
					<ul>
					  <li><a href="#">Reference link one will go in here</a></li>
					  <li><a href="#">Reference link one will go in here</a></li>
					  <li><a href="#">Reference link one will go in here</a></li>
					  <li><a href="#">Reference link one will go in here</a></li>
					</ul>
				  </div>
				</div>
				
				<div class="widget-list">
				  <h2>Downloads</h2>
				  <ul>
					<li><a href="#">File Download Number One</a></li>
					<li><a href="#">File Download Number Two</a></li>
					<li><a href="#">File Download Number Three</a></li>
				  </ul>
				</div>
				
				<div class="bot-bg"><div>&nbsp;</div></div>
			  </div>
			  </div>
			</div>
		  </div>
		</div>
		
		<div class="widget-bootom cf">
		  <h2><span>Components</span></h2>
		  <ul>
		    <li>
			  <div class="content-list">
			    <div class="img-block-list"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_11.jpg" alt=" " /></div>
				<div class="tit-block-list">
				  <h3><a href="#">Product title goes here</a></h3>
				</div>
			  </div>
			</li>
			<li>
			  <div class="content-list">
			    <div class="img-block-list"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_11.jpg" alt=" " /></div>
				<div class="tit-block-list">
				  <h3><a href="#">Product title goes here</a></h3>
				</div>
			  </div>
			</li>
			<li>
			  <div class="content-list">
			    <div class="img-block-list"><img src="<?php bloginfo('template_url'); ?>/tets-img/test_11.jpg" alt=" " /></div>
				<div class="tit-block-list">
				  <h3><a href="#">Product title goes here</a></h3>
				</div>
			  </div>
			</li>
		  </ul>
		</div>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">Pages:', 'after' => '</div>' ) ); ?>
		<?php edit_post_link('Edit', '<span class="edit-link">', '</span>' ); ?>
		</div>

<?php endif; ?>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); ?>

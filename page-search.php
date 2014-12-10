<?php
/**
 *
 * @package WordPress
 * @subpackage Base_Theme
 */
/**
 * Template Name: Search Page
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left">
<?php get_sidebar(); ?>
	  </div>
	  
	  <div class="right">
<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<div class="entry-content contact-page">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <form action=" " class="search-form cf">
			    <div class="text-input"><input type="text" value="Search" onFocus="if (this.value=='Search'){this.value='';}" onBlur="if (this.value==''){this.value='Search';}" /></div>
				<div class="select"><select class="styled">
					  <option>Products</option>
					  <option>Segments</option>
					  <option>Solutions</option>
					  <option>News</option>
					  <option>All Content</option>
					</select></div>
				<div class="sub-input"><input type="submit" value="Search" /></div>				 
			  </form>
		<div class="bottom-news">
		<h2 class="tit">Products</h2>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			    <li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
			  </ul>
			</div>
		  </div>
		</div>
		</div>
		<div class="wp-pagenavi">
		  <a href="#">1</a>
		  <a href="#">2</a>
		  <a href="#">3</a>
		  <a href="#">4</a>
		  <span>5</span>
		  <a href="#">6</a>
		  <a href="#">Next</a>
		</div>
		
		<div class="bottom-news">
		<h2 class="tit">services</h2>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			    <li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
			  </ul>
			</div>
		  </div>
		</div>
		</div>
		<div class="wp-pagenavi">
		  <a href="#">1</a>
		  <a href="#">2</a>
		  <a href="#">3</a>
		  <a href="#">4</a>
		  <span>5</span>
		  <a href="#">6</a>
		  <a href="#">Next</a>
		</div>
		<div class="bottom-news">
		<h2 class="tit">solutions</h2>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			    <li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
			  </ul>
			</div>
		  </div>
		</div>
		</div>
		<div class="wp-pagenavi">
		  <a href="#">1</a>
		  <a href="#">2</a>
		  <a href="#">3</a>
		  <a href="#">4</a>
		  <span>5</span>
		  <a href="#">6</a>
		  <a href="#">Next</a>
		</div>
		<div class="bottom-news">
		<h2 class="tit">news</h2>
		<div class="entry-content">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <ul>
			    <li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li>
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
				<li class="grey">
				  <h2><a href="#">Content Title</a></h2>
				  <div class="link-post"><a href="#" class="more">View More </a></div>
				</li>
			  </ul>
			</div>
		  </div>
		</div>
		</div>
		<div class="wp-pagenavi">
		  <a href="#">1</a>
		  <a href="#">2</a>
		  <a href="#">3</a>
		  <a href="#">4</a>
		  <span>5</span>
		  <a href="#">6</a>
		  <a href="#">Next</a>
		</div>
			  <?php wp_link_pages( array( 'before' => '<div class="page-link">Pages:', 'after' => '</div>' ) ); ?>
			  <?php edit_post_link('Edit', '<span class="edit-link">', '</span>' ); ?>
			</div>
		  </div>
		</div>
		</div>

<?php endif; ?>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); ?>

<?php
/**
 *
 * @package WordPress
 * @subpackage SwissPhone
 */

get_header(); ?>

    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="top-news full-page">
<?php if ( ! have_posts() ) : ?>
        <div id="post-0" class="error404 not-found">
            <h1><?php _e('FFRS News Not Found', 'SwissPhone'); ?></h1>
            <div class="entry-content">
                <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'SwissPhone'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </div>
<?php else: ?>        
    <h1 class="full-tit"><?php _e('FFRS News', 'SwissPhone'); ?></h1>

<div class="entry-content">
  <div class="top-left-bg">
    <div class="top-right-bg">
        <ul>
<?php while ( have_posts() ) : the_post(); ?>
            <li id="post-<?php the_ID(); ?>">
                <h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                <ol class="post-info cf">
                    <li><small class="date"><?php echo date("d.m.Y H:i", strtotime($GLOBALS['post']->post_date) - 8 * 3600); ?></small></li>
                    <li><iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink()); ?>&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;font=arial&amp;colorscheme=light&amp;action=like&amp;height=21" 
                        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe></li>
                    <li><span class='st_facebook_hcount' st_title='<?php echo esc_attr(get_the_title()); ?>' st_url='<?php echo esc_attr(get_permalink()); ?>' displayText='share'></span></li>
                </ol>
                <?php the_excerpt(); ?>
                <div class="link-post">
                    <a href="<?php the_permalink(); ?>" class="more"><?php _e('read more'); ?></a>
                </div>
            </li>
<?php endwhile; ?>
         </ul>
    </div>
    <?php if ( $wp_query->max_num_pages > 1 ) : ?>
        <div id="nav-below" class="navigation cf">
            <div class="nav-previous">
                <?php next_posts_link( __('<span class="meta-nav">&larr;</span> Older posts', 'SwissPhone') ); ?>
            </div>
            <div class="nav-next">
                <?php previous_posts_link( __('Newer posts <span class="meta-nav">&rarr;</span>', 'SwissPhone') ); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
</div>
</div>
</div>
<?php get_footer(); ?>

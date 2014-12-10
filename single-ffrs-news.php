<?php
/**
 *
 * @package WordPress
 * @subpackage SwissPhone
 */
?>
<?php get_header(); ?>

    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="full-page">
<?php the_post(); ?>
      <div id="post-<?php the_ID(); ?>" class="post">
        <h1 class="full-tit"><?php the_title(); ?></h1>
        <div class="entry-content">
          <div class="top-left-bg">
            <div class="top-right-bg">
                <ol class="post-info cf">
                    <li><small class="date"><?php echo date("d.m.Y H:i", strtotime($GLOBALS['post']->post_date) - 8 * 3600); ?></small></li>
                    <li><iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink()); ?>&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;font=arial&amp;colorscheme=light&amp;action=like&amp;height=21" 
                        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe></li>
                    <li><span class='st_facebook_hcount' st_title='<?php echo esc_attr(get_the_title()); ?>' st_url='<?php echo esc_attr(get_permalink()); ?>' displayText='share'></span></li>
                </ol>
              <?php the_content(); ?>
              <?php wp_link_pages( array( 'before' => '<div class="page-link"> Pages:', 'after' => '</div>' ) ); ?>

                <?php if ( get_the_author_meta( 'description' ) ) : ?>
                    <div id="entry-author-info">
                        <div id="author-avatar">
                            <?php echo get_avatar( get_the_author_meta( 'user_email' ),  60  ); ?>
                        </div>
                        <div id="author-description">
                            <h2>About <?php the_author() ?></h2>
                            <?php the_author_meta( 'description' ); ?>
                            <div id="author-link">
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                                    View all posts by <?php the_author() ?> <span class="meta-nav">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php comments_template( '', true ); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="bot-bg-page">&nbsp;</div>
    </div>
<?php get_footer(); ?>

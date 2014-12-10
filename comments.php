<?php
/**
 * @package WordPress
 * @subpackage Base_Theme
 */
?>

<div id="comments">

<?php if ( post_password_required() ) : ?>
		<p class="nopassword">This post is password protected. Enter the password to view any comments.</p>
</div>
<?php return; endif; ?>

<?php if ( have_comments() && comments_open()) : ?>
	<h3 id="comments-title">
		<?php comments_number('No Responses to ', 'One Response to ', '% Responses to ') ?>
		<em><?php the_title() ?></em>
	</h3>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link('<span class="meta-nav">&larr;</span> Older Comments'); ?></div>
				<div class="nav-next"><?php next_comments_link('Newer Comments <span class="meta-nav">&rarr;</span>'); ?></div>
			</div> 
	<?php endif; ?>
	<ol class="commentlist">
		<?	wp_list_comments();	?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link('<span class="meta-nav">&larr;</span> Older Comments'); ?></div>
				<div class="nav-next"><?php next_comments_link('Newer Comments <span class="meta-nav">&rarr;</span>'); ?></div>
			</div>
	<?php endif; ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div id="respond">

<h3 class="font-light"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
<label for="url"><small>Website</small></label></p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

<div class="sub-input"><input name="submit" type="submit" id="submit" tabindex="6" value="Submit" /></div>
<?php comment_id_fields(); ?>

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>


</div>

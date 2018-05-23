<div class="comments" id="discussion">
	<?php if (post_password_required()) : ?>
	<p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'PASSIO' ); ?></p>
</div>

	<?php return; endif; ?>

<?php if (have_comments()) : ?>

	<h2><?php comments_number(); ?></h2>

	<!-- <ul> -->
		<?php wp_list_comments('callback=better_comment&end-callback=better_comment_close'); // Custom callback in functions.php ?>
	<!-- </ul> -->

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

	<p><?php //_e( 'Comments are closed here.', 'PASSIO' ); ?></p>

<?php endif; ?>


<?php
if (wp_get_current_user()->user_login == 'guest' && comments_open()) { // don't allow the user 'guest' to make comments
	echo '<div class="comments" id="discussion"><h3 id="reply-title" class="comment-reply-title">Leave a Comment</h3><p>The user "guest" is not allowed to comment; please <a href="/wp-login.php?action=register">create a different user account</a>.</p></div>';
} else {
	comment_form(array('comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="120" rows="8"  aria-required="true" required="required"></textarea></p>', 'title_reply' => 'Leave a Comment'));
}
?>

</div>

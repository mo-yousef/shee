<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area mt-8 pt-8 border-t border-gray-200">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title text-2xl font-bold mb-6">
			<?php
			$shecy_comment_count = get_comments_number();
			if ( '1' === $shecy_comment_count ) {
				printf(
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'shecy' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $shecy_comment_count, 'comments title', 'shecy' ) ),
					number_format_i18n( $shecy_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list space-y-6">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 56,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments mt-6 text-gray-600"><?php esc_html_e( 'Comments are closed.', 'shecy' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Customizing the comment form with Tailwind classes
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields = array(
		'author' =>
			'<p class="comment-form-author"><label for="author" class="block text-sm font-medium text-gray-700">' . __( 'Name', 'shecy' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . ' class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm" /></p>',
		'email' =>
			'<p class="comment-form-email"><label for="email" class="block text-sm font-medium text-gray-700">' . __( 'Email', 'shecy' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			'<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm" /></p>',
		'url' =>
			'<p class="comment-form-url"><label for="url" class="block text-sm font-medium text-gray-700">' . __( 'Website', 'shecy' ) . '</label>' .
			'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm" /></p>',
	);

	$comment_field = '<p class="comment-form-comment"><label for="comment" class="block text-sm font-medium text-gray-700">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm"></textarea></p>';

	$submit_button = '<button name="%1$s" type="submit" id="%2$s" class="%3$s w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">%4$s</button>';

	comment_form( array(
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field' => $comment_field,
		'submit_button' => $submit_button,
		'class_form' => 'comment-form space-y-4',
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-2xl font-bold mb-4">',
		'title_reply_after'  => '</h3>',
	) );
	?>

</div><!-- #comments -->

<div id="Comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments', 'antistar' ); ?></p>
	<!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>
	
	<?php // You can start editing here -- including this comment! ?>
	
	<?php if ( have_comments() ) : ?>
	
		<h2><?php _e('Comments', 'antistar'); ?>:</h2>
		
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'antistar' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'antistar' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'antistar' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
	
		<ol class="commentlist">
			<?php
				// Loop comments
				wp_list_comments(array('callback' => 'sc_comments'));
			?>
		</ol>
	
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'antistar' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'antistar' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'antistar' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
	
	<?php
		/* If there are no comments and comments are closed, let's leave a little comment, shall we?
		 * But we don't want the comment on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<!--<p class="nocomments"><?php _e( 'Comments are closed', 'antistar' ); ?></p>-->
	<?php endif; ?>
	
	<?php 
		$args = array(
			'title_reply'			=> __('Leave a Comment', 'antistar').':',
			
			'comment_field'			=> '<p class="comment-form-comment"><label for="comment" class="label-comment">' . __( 'Comment', 'antistar' ) . '</label><textarea name="comment" cols="45" rows="3" aria-required="true"></textarea></p>',
			
			'comment_notes_before'	=> '<div class="comment">'.__('Your email address will not be published', 'antistar').'</div>',
			'label_submit'			=> __('Post Comment', 'antistar')
			
		);
		// Get comments form
		comment_form($args); 
	
	?></div>
<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) { return; } ?>

<div id="comments" class="comments-area">

    <?php
    // You can start editing here -- including this comment!
    if ( have_comments() ) :
    ?>
        <h2 class="section-title">
            <?php
            $comments_number = get_comments_number();
            if ( '1' === $comments_number ) {
                /* translators: %s: post title */
                printf( esc_html_x( 'One Reply to %s', 'comments title', 'kutak' ), get_the_title() );
            } else {
                printf(
                    /* translators: 1: number of comments, 2: post title */
                    _nx(
                        '%1$s Reply to %2$s',
                        '%1$s Replies to %2$s',
                        $comments_number,
                        'comments title',
                        'kutak'
                    ),
                    number_format_i18n( $comments_number ),
                    get_the_title()
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
                wp_list_comments(
                    array(
                        'avatar_size' => 100,
                        'style'       => 'ol',
                        'short_ping'  => true,
                        'reply_text'  => esc_html__( 'Reply', 'kutak' ),
                    )
                );
            ?>
        </ol>

        <?php
        // are there comments to navigate through 
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav class="navigation paging-navigation comments-navigation pagination-type-numbers">
                <?php paginate_comments_links( apply_filters( 'apalodi_comments_pagination_args', array(
                        'prev_text'    => '&laquo;',
                        'next_text'    => '&raquo;',
                        'type'         => 'list',
                        'end_size'     => 1,
                        'mid_size'     => 2,
                        //'add_fragment' => '' // #comment
                    ) ) ); 
                ?>
            </nav><!-- .navigation.paging-navigation -->
        <?php endif; // check for comment navigation

    endif; // Check for have_comments().

    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>

        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'kutak' ); ?></p>
    <?php
    endif;

    comment_form( array(
        'title_reply_before' => '<h3 id="reply-title" class="section-title comment-reply-title">'
    ) );
    ?>

</div><!-- #comments -->

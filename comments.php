<?php
global $post;

if ( !post_type_supports(get_post_type($post->id),'comments') ) return;
    
if ( post_password_required() ){
	return;
}
?>
<?php 
if ( have_comments() ) { ?>
    <div class="comments">

        <a name="comments"></a>

        <div class="comments-title-container row">

            <h2 class="comments-title">

                <?php 
                $comment_count = count( $wp_query->comments_by_type['comment'] );
                echo $comment_count . ' ' . _n( 'Comment', 'Comments', $comment_count, 'gordo' ); ?>

            </h2>

            <?php if ( comments_open() ) { ?>

                <h2 class="add-comment-title alignright"><a href="#respond" class="action-button"><?php _e( 'Add yours', 'gordo' ) . ' &rarr;'; ?></a></h2>

            <?php } ?>

            

        </div><!-- .comments-title-container -->

        <ol class="commentlist">
            <?php wp_list_comments( array( 'type' => 'comment', 'callback' => 'gordo_comment' ) ); ?>
        </ol>

        <?php if ( ! empty( $comments_by_type['pings'] ) ) { ?>

            <div class="pingbacks">

                <div class="pingbacks-inner">

                    <h3 class="pingbacks-title">

                        <?php 
                        $pingback_count = count( $wp_query->comments_by_type['pings'] );
                        echo $pingback_count . ' ' . _n( 'Pingback', 'Pingbacks', $pingback_count, 'gordo' ); ?>

                    </h3>

                    <ol class="pingbacklist">
                        <?php wp_list_comments( array( 'type' => 'pings', 'callback' => 'gordo_comment' ) ); ?>
                    </ol>

                </div>

            </div>

        <?php } ?>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

            <div class="comment-nav-below" role="navigation">

                <div class="post-nav-older fleft"><?php previous_comments_link( '&laquo; ' . __( 'Older Comments', 'gordo' ) ); ?></div>

                <div class="post-nav-newer fright"><?php next_comments_link( __( 'Newer Comments', 'gordo' ) . ' &raquo;' ); ?></div>

                

            </div><!-- .comment-nav-below -->

        <?php } ?>

    </div><!-- .comments -->
<?php 
}

if ( !comments_open() ) {
    if ( !is_page() ){
        ?>
        <p class="nocomments"><?php _e( 'Comments are closed.', 'gordo' ); ?></p>
        <?php
    }
    return;
}
?>
<div class="comment-ribbon ribbon">
    <span></span><!--ribbon content-->
    <span></span><!--ribbon end-->
</div>
<?php

//author
$author_icon = sprintf('<span class="input-group-addon">%s</span>','<i class="fa fa-user-o" aria-hidden="true"></i>');
$author_input = sprintf('<div class="input-group"><input id="author" class="form-control" name="author" type="text" placeholder="%s" value="%s" size="30" />%s</div>',__( 'Name', 'gordo' ),esc_attr( $commenter['comment_author'] ),$author_icon);
$author_label = sprintf('<label for="author">%s</label>', __( 'Author', 'gordo' ));
$author_req = $req ? '<span class="required">*</span>' : null;
$author_block = sprintf('<p class="comment-form-author">%s</p>%s %s',$author_input,$author_label,$author_req);

//email
$email_icon = sprintf('<span class="input-group-addon">%s</span>','<i class="fa fa-envelope-o" aria-hidden="true"></i>');
$email_input = sprintf('<div class="input-group"><input id="email" class="form-control" name="email" type="email" placeholder="%s" value="%s" size="30" />%s</div>',__( 'Email', 'gordo' ),esc_attr( $commenter['comment_author_email'] ),$email_icon);
$email_label = sprintf('<label for="email">%s</label>', __( 'Email', 'gordo' ));
$email_req = $req ? '<span class="required">*</span>' : null;
$email_block = sprintf('<p class="comment-form-email">%s</p>%s %s',$email_input,$email_label,$email_req);

//url
$url_icon = sprintf('<span class="input-group-addon">%s</span>','<i class="fa fa-home" aria-hidden="true"></i>');
$url_input = sprintf('<div class="input-group"><input id="url" class="form-control" name="url" type="text" placeholder="%s" value="%s" size="30" />%s</div>',__( 'Website', 'gordo' ),esc_attr( $commenter['comment_author_url'] ),$url_icon);
$url_label = sprintf('<label for="email">%s</label>', __( 'Website', 'gordo' ));
$url_block = sprintf('<p class="comment-form-url">%s</p>%s',$url_input,$url_label);

$comments_args = array(

    'comment_notes_before' =>   sprintf( '<p class="comment-notes">%s</p>',__('Your email address will not be published.','gordo') ),
    'comment_field' =>          '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
    'fields' => apply_filters( 'comment_form_default_fields', array(
        'author' => $author_block,
        'email' => $email_block,
        'url' => $url_block
    )),
);

comment_form($comments_args);

?>
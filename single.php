<?php get_header(); ?>

    <div class="container-shadow container-1000">
        <div class="polygon">
            <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'blog-image-main' ); ?>">
            <div class="container-inner">
                <?php esc_html( the_title( '<h2>', '</h2>' ) ); ?>
                <div class="flex-container">
                    <small class="label">published by</small>
                    <small><b>
                        <?php echo get_the_author_meta('display_name', get_post_field( 'post_author', get_the_ID() ) ); ?>
                    </b></small>
                    <small class="label">on</small>
                    <small><b><?php echo get_the_date(); ?></b></small>
                </div>
                <br>
                <?php esc_html( the_content( '<p>', '</p>' ) );  ?>
                <br>
                <?php
                $blog_tags = get_the_terms( get_the_ID(), 'post_tag' );
                if ( !is_wp_error( $blog_tags ) || $blog_tags !== false ) {
                    ?>
                    <hr>
                    <div class="flex-container">
                        <small class="label">tags</small>
                        <?php
                        foreach ( $blog_tags as $tag ) {
                            get_template_part( 'template-parts/part', 'tag-item', $tag );
                        }
                        ?>
                    </div>
                    <hr>
                    <?php
                }
                if ( is_user_logged_in() && $post->comment_status === 'open' ) {
                    ?>
                    <h5>comments ( <?php echo count( $get_comments ) ?> )</h5>
                    <br>
                    <?php
                    require wp_make_link_relative( get_template_directory() . '/forms/form-add-comment.php' );
                }
                ?>
            </div>
        </div>
    </div>
    <div class="container-1000">
        <div id="comments-results-loader" class="container-1000 text-center hide">
            <img class="sumbit-loader"
            src="<?php echo get_template_directory_uri() . '/img/ajax-loader.gif';  ?>">
            <small class="blue-text">loading comments</small>
        </div>
        <div id="comments-results-error" class="container-1000 text-center hide">
            <small class="error">unable to load comments.</small>
        </div>
        <div id="single-post-comments">
            <?php
            if ( is_user_logged_in() ) {
                echo retrieve_comments( true , null );
            }
            ?>
        </div>
    </div>
<?php get_footer(); ?>

<?php get_header();
$query = parse_str( $_SERVER['QUERY_STRING'] );
if ( is_user_logged_in() && strval( $current_user->ID ) === $post->post_author
    && current_user_can( 'edit_post', $post->ID ) && $action === 'edit' ) {
        require wp_make_link_relative( get_template_directory() . '/forms/form-add-blender-post.php' );
} elseif ( $post->post_status === 'publish' ) {
    $feature_image        = get_the_post_thumbnail_url();
    $author_data          = get_userdata( $post->post_author );
    $categories           = wp_get_object_terms( $post->ID, 'blender_categories' );
    $tags                 = wp_get_object_terms( $post->ID, 'post_tag' );
    $blender_meta         = get_post_meta( $post->ID, 'blender_meta', true );
    foreach ( $blender_meta as $meta_field ) {
        if ( !empty( $meta_field ) ) {
            $has_blender_meta = true; } }
    $blender_folder = str_replace( '-', '_', $post->post_name ) . '/';
    $downloadable   = get_post_meta( $post->ID, 'allow_download', true  );
    $license        = get_post_meta( $post->ID, 'license', true );
    ?>
    <div class="container-shadow container-1000">
        <div class="polygon">
            <?php echo do_shortcode( '[spin360 canvas_name="s1"
                                       imgs_folder="'. $blender_folder. '"
                                       img_type=png aspect_ratio=1.33333
                                       speed=1.0
                                       loop=false ]' ); ?>
            <div class="container-inner">
                <h2><?php echo esc_html( $post->post_title ) ?></h2>
                <!-- POST HEADER STARTS HERE -->
                <div class="flex-container">
                    <small class="label">created by</small>
                    <?php
                    if ( $author_data->caps['administrator'] == 1 ) {
                        echo '<small><b>' . $author_data->display_name . '</b></small>';
                    } else {
                        ?><a class="no-padding flex-container" href="<?php echo get_home_url() . '/profile/' . $author_data->display_name ?>">
                            <small><b><?php echo $author_data->display_name ?></b></small>
                        </a><?php
                    }?>
                    <small class="label">on</small>
                    <small><b><?php echo get_the_date( 'F j, Y', $post->ID ) ?></b></small>
                </div>
                <br>
                <br>
                <div class="toggle-menu">
                    <?php
                    require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
                    echo toggleMenuItem ( 'blender-post-menu toggle-menu-item', 'blender-details-menu',
                                    "toggleOption('blender-details', 'blender-post');",
                                    'fas fa-info-circle fa-2x', 'details' );
                    if ( $has_blender_meta === true ) {
                        echo toggleMenuItem ( 'blender-post-menu toggle-menu-item', 'blender-meta-menu',
                                        "toggleOption('blender-meta', 'blender-post');",
                                        "fas fa-tag fa-2x", 'meta' ); }
                    if ( $post->comment_status === 'open' ) {
                        echo toggleMenuItem ( 'blender-post-menu toggle-menu-item', 'blender-discuss-menu',
                                        "toggleOption('blender-discuss', 'blender-post');",
                                        "fas fa-comments fa-2x", 'discuss' ); }
                    if ( $downloadable == true && !empty( $license ) ) {
                        echo toggleMenuItem ( 'blender-post-menu toggle-menu-item', 'blender-options-menu',
                                        "toggleOption('blender-artist', 'blender-post');",
                                        "fas fa-user fa-2x", 'artist' ); }
                    if ( $author_data->caps['administrator'] != 1  ) {
                        echo toggleMenuItem ( 'blender-post-menu toggle-menu-item', "blender-artist-menu",
                                        "toggleOption('blender-options', 'blender-post');",
                                        "fas fa-download fa-2x", 'options' ); }
                    ?>
                </div>
                <hr>
                <!-- POST DETAILS SECTION STARTS HERE -->
                <div class="blender-post-option" id="blender-details-option">
                    <h5 class="blue-text">details</h5>
                    <?php
                    if ( !empty( $post->post_content )) {
                        echo '<p>' . esc_html( $post->post_content ) . '</p>'; }?>
                    <br>
                    <?php
                    if ( !empty( $categories ) && !empty($tags) ) {
                        ?>
                        <hr>
                        <div class="post-detail-item-multiple">
                        <?php
                        if ( !empty( $categories ) ) {
                            ?>
                            <div class="flex-container">
                                <small class="label">category</small>
                                <?php
                                foreach ( $categories as $category ) {
                                get_template_part( 'template-parts/part', 'category-item', $category ); }
                             ?>
                            </div>
                            <?php
                        }
                        if ( !empty($tags) ) {
                            ?>
                            <br>
                            <div>
                                <div class="flex-container">
                                    <small class="label">tags</small>
                                    <?php
                                    foreach ($tags as $tag ) {
                                        get_template_part( 'template-parts/part', 'tag-item', $tag ); }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        </div>
                        <hr>
                        <?php
                        }
                        ?>
                </div>
                <!-- META SECTION STARTS HERE -->
                <div class="blender-post-option hide" id="blender-meta-option">
                    <h5 class="blue-text">meta</h5>
                    <div class="post-detail-item-multiple">
                        <?php
                        foreach ( $blender_meta as $key => $meta_value ) {
                            if ( !empty( $meta_value ) ) {
                                ?><div class="post-detail-item-single">
                                    <small class="label"><?php echo $key ?></small>
                                    <p><?php echo $meta_value ?></p>
                                </div><?php }
                        }?>
                    </div>
                </div>
                <!-- DISCUSSION SECTION STARTS HERE -->
                <div class="blender-post-option hide" id="blender-discuss-option">
                    <h5 class="blue-text">discuss</h5>
                    <?php require wp_make_link_relative( get_template_directory() . '/forms/form-add-comment.php' ); ?>
                    <br>
                    <h5>comments ( <?php echo count( $get_comments ) ?> )</h5>
                </div>
                <!-- OPTIONS SECTION STARTS HERE -->
                <div class="blender-post-option hide" id="blender-options-option">
                    <h5 class="blue-text">options</h5>
                </div>
                <!-- ARTIST SECTION STARTS HERE -->
                <div class="blender-post-option hide" id="blender-artist-option">
                    <?php
                    global $wpdb;
                    $author_profile_link  = get_user_meta( $post->post_author, 'profile_link', true );
                    $author_profile_ID    = get_user_meta( $post->post_author, 'profile_ID', true );
                    $author_portrait      = get_user_meta( $post->post_author, 'portrait_link', true );
                    $author_bio           = $wpdb->get_var( "SELECT post_content FROM wp_posts WHERE ID= $author_profile_ID");
                    ?>
                    <h5 class="blue-text">artist</h5>
                    <div>
                        <a class="no-padding" href="<?php esc_attr_e( $author_profile_link ) ?>">
                            <img src="<?php echo esc_attr( $author_portrait) ?>" alt="" class="single-post-author-img">
                            <b><?php echo $author_data->display_name ?></b>
                            <p><?php echo esc_html( $author_bio ) ?></p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
get_footer(); ?>

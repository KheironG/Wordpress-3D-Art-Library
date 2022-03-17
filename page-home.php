<?php get_header();
    $previews = get_option('home_previews');
    switch ( $previews['home_previews_blender_show'] ) {
        case 'category':
        $blender_previews = new WP_Query( array(
            'post_type' => 'blender', 'post_status' => 'publish', 'category_name' => 'home',
            'posts_per_page' => $previews['home_previews_blender_amount'] ) );
            break;
        case 'latest':
        $blender_previews = new WP_Query( array( 'post_type' => 'blender', 'post_status' => 'publish', 'order' => 'DESC',
            'posts_per_page' => $previews['home_previews_blender_amount'] ) );
            break;
        case 'none':
            $blender_previews = 'none';
            break;
        default:
            $blender_previews = 'none';
            break;
    }
    if ( $blender_previews !== 'none' && $blender_previews->have_posts() ) {
        ?>
        <h4 class="text-center no-bottom-margin">featured 3D objects</h4>
        <div class="blender-preview-container container-shadow">
        <?php
        while ( $blender_previews->have_posts() ) {
            $blender_previews->the_post();
            ?>
            <a class="blender-preview-item" href="<?php echo esc_html_e( the_permalink() ) ?>">
                <img class="blender-preview-item-img" src="<?php esc_attr_e( get_the_post_thumbnail_url( get_the_ID(), array( 200, 200 ) ) ) ?>" alt="">
                <div class="blender-preview-item-overlay">
                    <div class="blender-preview-item-overlay-content">
                        <p><b><?php echo esc_html_e( the_title() ) ?></b></p>
                        <small class="tiny"><em>by</em><b> <?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></b></small>
                    </div>
                </div>
            </a>
            <?php
        }
    }
    wp_reset_postdata();
    ?>
    </div>
    <br>
    <!-- INFO ITEMS SECTION STARTS HERE -->
    <?php
    $get_info_items = get_option( 'info_items' );
    $item_numbers = [ 'one', 'two' , 'three', 'four' ];
    $info_items = array();
    foreach ( $item_numbers as $item_number ) {
        $compiled = array(
            'title'       => $get_info_items['info_item_'. $item_number .'_title'],
            'description' => $get_info_items['info_item_'. $item_number .'_desc'],
            'icon'        => $get_info_items['info_item_'. $item_number .'_icon'],
            'link'        => $get_info_items['info_item_'. $item_number .'_link']
        );
        if( count( array_filter( $compiled ) ) == 0 ) {
            continue; }
        else {
            $info_items[$item_number] = $compiled; }
    }
    if ( !empty( $info_items ) ) {
        if ( !empty( $get_info_items['info_item_section_title'] ) ) {
            ?><h4 class="text-center no-bottom-margin"><?php echo $get_info_items['info_item_section_title']  ?></h4><?php }
        ?>
        <div id="home-info-item-container">
            <?php
            foreach ( $info_items as $info_item ) {
                if ( !empty( $info_item['link'] ) ) {
                    $class="linked-home-info-item"; }
                else {
                    $class="home-info-item"; }
                ?>
                <div class="<?php echo $class ?>">
                    <div class="polygon">
                        <div class="home-info-item-padding">
                            <?php if ( !empty( $info_item['title'] ) ) {
                                ?><h4><?php echo $info_item['title'] ?></h4><?php }
                            if ( !empty( $info_item['icon'] ) ) {
                                ?><span class="<?php echo $info_item['icon'] ?>"></span> <?php }
                            if ( !empty( $info_item['description'] ) ) {
                                ?><p><?php echo $info_item['description'] ?></p><?php }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
        echo set_home_info_items();
    ?>
    <br>
    <!-- FEATURED ARTISTS SECTION STARTS HERE -->
    <?php
    switch ( $previews['home_previews_profile_show'] ) {
        case 'category':
        $artist_previews = new WP_Query( array( 'post_type' => 'profile', 'category_name' => 'home',
            'posts_per_page' => $previews['home_previews_profile_amount'] ) );
            break;
        case 'latest':
        $artist_previews = new WP_Query( array( 'post_type' => 'profile', 'order' => 'DESC',
            'posts_per_page' => $previews['home_previews_profile_amount'] ) );
            break;
        case 'none':
            $artist_previews = 'none';
              break;
        default:
            $artist_previews = 'none';
            break;
    }
    if ( $artist_previews !== 'none' && $artist_previews->have_posts() ) {
        ?>
        <div class="container-1000">
            <h4 class="text-center">featured artists</h4>
            <div id="home-profiles-preview">
                <span class="swipe-backward hide" onclick="swipeElement(this)"><</span>
                <span class="swipe-forward hide" onclick="swipeElement(this)">></span>
                <div id="profile-previews-container" onmouseover="setSwipeControls( this, true )">
                <?php
                while ( $artist_previews->have_posts() ) {
                    $artist_previews->the_post();
                    ?>
                    <a href="<?php echo get_home_url() . '/profile/' . $post->post_title ?>" class="no-paddding">
                        <div class="profile-item">
                            <img class="profile-item-preview" src="<?php esc_attr_e( get_user_meta( $post->post_author, 'portrait_link', true ) ) ?>">
                            <div class="profile-item-details">
                                <b><?php echo esc_html_e( $post->post_title ); ?></b>
                                <div class="flex-container">
                                    <span class="fas fa-cube"></span>
                                    <b><?php echo count_user_posts( $post->post_author, 'blender', true ); ?></b>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
                </div>
            </div>
        </div>
        <?php
    }
    wp_reset_postdata();
get_footer();?>

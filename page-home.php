<?php get_header(); ?>

    <h4 class="text-center no-bottom-margin">featured 3D objects</h4>
    <div class="blender-preview-container container-shadow">
        <?php
        $get_blender_previews = new WP_Query( array(
            'post_type' => 'blender',
            'category_name' => 'home',
            'posts_per_page' => 20
        ) );
        if ( $get_blender_previews->have_posts() ) {
            while ( $get_blender_previews->have_posts() ) {
                $get_blender_previews->the_post();
                ?>
                <a class="blender-preview-item" href="<?php echo esc_html_e( the_permalink() ) ?>">
                    <img class="blender-preview-item-img" src="<?php esc_attr_e( get_the_post_thumbnail_url( get_the_ID(), array( 200, 200 ) ) ) ?>" alt="">
                    <div class="blender-preview-item-overlay">
                        <div class="blender-preview-item-overlay-content">
                            <p><b><?php echo esc_html_e( the_title() ) ?></b></p>
                            <small class="tiny"><em>by </em> <b><?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></b></small>
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
    $info_items = array(
        'title' =>           $get_info_items['info_item_section_title'],
        'one'   => array(
            'title'       => $get_info_items['info_item_one_title'],
            'description' => $get_info_items['info_item_one_desc'],
            'icon'        => $get_info_items['info_item_one_icon'],
            'link'        => $get_info_items['info_item_one_link']
        ),
        'two'   => array(
            'title'       => $get_info_items['info_item_two_title'],
            'description' => $get_info_items['info_item_two_desc'],
            'icon'        => $get_info_items['info_item_two_icon'],
            'link'        => $get_info_items['info_item_two_link']
        ),
        'three'  => array(
            'title'       => $get_info_items['info_item_three_title'],
            'description' => $get_info_items['info_item_three_desc'],
            'icon'        => $get_info_items['info_item_three_icon'],
            'link'        => $get_info_items['info_item_three_link']
        ),
        'four'  => array(
            'title'       => $get_info_items['info_item_four_title'],
            'description' => $get_info_items['info_item_four_desc'],
            'icon'        => $get_info_items['info_item_four_icon'],
            'link'        => $get_info_items['info_item_four_link']
        )
    );
    $active_items = array();
    foreach ( $info_items as $key => $item ) {
        $check_item = array_filter( $item );
        if ( count( $check_item ) == 0 ){
            null;
        } else {
            $active_items[$key] = $item;
        }
    }
    if ( !empty( $active_items ) ) {
        if ( !empty( $info_items['title'] ) ) {
            ?><h4 class="text-center no-bottom-margin"><?php echo $info_items['title'] ?></h4><?php
        }
        ?>
        <div id="home-info-item-container">
            <?php
            foreach ( $active_items as $active_item ) {
                if ( !empty( $active_item['link'] ) ) {
                    $class="linked-home-info-item";
                } else {
                    $class="home-info-item";
                }
                ?>
                <div class="<?php echo $class ?>">
                    <div class="polygon">
                        <div class="home-info-item-padding">
                            <?php if ( !empty( $active_item['title'] ) ) {
                                ?><h4><?php echo $active_item['title'] ?></h4><?php
                            }
                            if ( !empty( $active_item['icon'] ) ) {
                                ?><span class="<?php echo $active_item['icon'] ?>"></span> <?php
                            }
                            if ( !empty( $active_item['description'] ) ) {
                                ?><p><?php echo $active_item['description'] ?></p><?php
                            }
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
    $get_artist_preview_args = array(
        'post_type' => 'profile',
        'category_name' => 'home',
        'posts_per_page' => 25
    );
    $get_artist_previews = get_posts( $get_artist_preview_args );
    if ( count($get_artist_previews ) > 0 ) {
        ?>
        <div class="container-1000">
            <h4 class="text-center">featured artists</h4>
            <div id="home-profiles-preview">
                <span class="swipe-backward hide" onclick="swipeElement(this)"><</span>
                <span class="swipe-forward hide" onclick="swipeElement(this)">></span>
                <div id="profile-previews-container" onmouseover="setSwipeControls( this, true )">
                <?php
                foreach ( $get_artist_previews as $key => $artist_preview ) {
                    ?>
                    <a href="<?php echo get_home_url() . '/profile/' . $artist_preview->post_title ?>" class="no-paddding">
                        <div class="profile-item">
                            <img class="profile-item-preview" src="<?php esc_attr_e( get_post_meta( $artist_preview->ID, 'portrait_link', true ) ) ?>" alt="">
                            <div class="profile-item-details">
                                <b><?php echo esc_html( $artist_preview->post_title ) ?></b>
                                <div class="flex-container">
                                    <span class="fas fa-cube"></span>
                                    <b><?php echo count_user_posts( $artist_preview->post_author, 'blender', true ); ?></b>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
                </div>
            </div>
            <?php
            }
        ?>
        </div>
        <?php
get_footer();?>

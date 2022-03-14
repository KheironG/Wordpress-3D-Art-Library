<?php
function headerMenu ( ) {
    $object = get_option( 'header_primary_nav' );
    $items = [ 'one', 'two', 'three', 'four', 'five', 'six', 'seven' ];
    foreach ( $items as $item) {
        // If text+icon style menu
        if ( $object['menu_item_style'] === 'icon' ) {
            if ( !empty( $object['menu_item_'. $item.'_link'] )
                    || !empty( $object['menu_item_'. $item.'_icon'] )
                        || !empty( $object['menu_item_'. $item.'_text']) ) {
                            ?>
                            <a class="header-icon" href="<?php echo $object['menu_item_'. $item.'_link'] ?>">
                                <span class="<?php echo $object['menu_item_'. $item.'_icon'] ?>"></span>
                                <small><?php echo $object['menu_item_'. $item.'_text'] ?></small>
                            </a>
                            <?php } }
        // If text only menu
        if ( $object['menu_item_style'] === 'text' ) {
            if ( !empty( $object['menu_item_'. $item.'_link'] )
                    || empty( $object['menu_item_'. $item.'_icon'] )
                        || !empty( $object['menu_item_'. $item.'_text']) ) {
                            ?>
                            <a class="header-menu-link" href="<?php echo $object['menu_item_'. $item.'_link'] ?>">
                                <?php echo $object['menu_item_'. $item.'_text'] ?>
                            </a>
                            <?php }}
    }
}


function privateMenu( $user ) {
    if ( is_user_logged_in() && $user->ID != 0 ) {
    ?>
        <a class="private-menu-icon" href="<?php echo $user->profile_link; ?>">
            <span class="fas fa-user fa-2x"></span>
            <small>profile</small>
        </a>

        <a class="private-menu-icon" href="<?php echo esc_url( '/creators-settings' ) ?>">
            <span class="fas fa-cog fa-2x"></span>
            <small>settings</small>
        </a>

        <a class="private-menu-icon" href="<?php echo esc_url( '/creators-upload' ) ?>">
            <span class="fas fa-plus-square fa-2x"></span>
            <small>create</small>
        </a>

        <a class="private-menu-icon" href="<?php echo esc_url( '/creators-gallery' ) ?>">
            <span class="fas fa-images fa-2x"></span>
            <small>gallery</small>
        </a>
    <?php
    }
}

function signInOut() {
    if ( !is_user_logged_in() && $current_user->ID == 0 ) {
        ?>
        <a class="header-icon-sign-in" href="<?php echo esc_url( '/creators-space' ) ?>">
            <span class="fas fa-sign-in-alt"></span>
            <small>artists</small>
        </a>
        <?php }
    else {
        ?>
        <a class="header-icon-sign-out" href="<?php echo wp_logout_url( get_home_url() . '/creators-space' ); ?>">
            <span class="fas fa-sign-out-alt fa-3x"></span>
            <small>signout</small>
        </a>
        <?php }
}

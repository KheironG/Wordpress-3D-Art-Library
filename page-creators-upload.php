<?php  get_header();

if ( $current_user->ID != 0 || is_user_logged_in() ) {
        require wp_make_link_relative( get_template_directory() . '/forms/form-add-blender-post.php' );
} else {
    wp_redirect( get_home_url() . '/creators-space' );
    exit;
}

get_footer(); ?>

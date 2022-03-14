<?php
if ( defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
}

if ( ! current_user_can( 'edit_post', $post_id ) ) {
        print_r( 'Sorry, you do not have access to edit post.' );
        exit;
}

$post = get_post( $post_id );

if ( $post === null ) {
    print_r( 'Unable to update user.' );
    exit;
}

if ( ! isset( $_POST['profile-metabox-nonce'] )
    || ! wp_verify_nonce( $_POST['profile-metabox-nonce'], 'profile_metabox_nonce_action' ) ) {
    return null;
}

$profile_details = array(
    'country'       => sanitize_text_field( $_POST[ 'admin-profile-country' ] ),
    'city'          => sanitize_text_field( $_POST[ 'admin-profile-city' ] )
);
update_post_meta( $post_id, 'profile_details', $profile_details  );
update_user_meta( $post->post_author, 'profile_details', $profile_details );

$profile_social = array(
    'website'   => esc_url_raw( $_POST['admin-profile-website'] ),
    'facebook'  => esc_url_raw( $_POST['admin-profile-facebook'] ),
    'instagram' => esc_url_raw( $_POST['admin-profile-instagram'] ),
    'twitter'   => esc_url_raw( $_POST['admin-profile-twitter'] ),
    'youtube'   => esc_url_raw( $_POST['admin-profile-youtube'] ),
    'linkedin'  => esc_url_raw( $_POST['admin-profile-linkedin'] )
);
update_post_meta( $post_id, 'profile_social', $profile_social  );
update_user_meta( $post->post_author, 'profile_social', $profile_social );

?>

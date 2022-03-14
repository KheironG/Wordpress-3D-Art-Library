<?php
    if ( !wp_verify_nonce( $_POST['nonce'], 'frontend-ajax-nonce') ) {
            $error['error'] = 'insecure upload attempt.';
            echo json_encode( $error );
            exit;
    }

    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    //Upload image
    $upload_image = wp_handle_upload( $_FILES['file'], array( 'test_form' => false )  );
    if ( array_key_exists("upload_error_handler", $upload_image )
        || array_key_exists("error", $upload_image ) ) {
            $error['error'] = 'unable to upload image.';
            echo json_encode( $error );
            exit;
    }

    $current_user = wp_get_current_user();

    if ( $_POST['image-type'] === 'profile-settings-cover' ) {
        $image_title = $current_user->display_name . ' profile cover';
    } else if ( $_POST['image-type'] === 'profile-settings-portrait' ) {
        $image_title = $current_user->display_name . ' profile portrait';
    }

    $attached_image_args = array(
        'post_mime_type' => $upload_image['type'],
        'post_name'     => $image_title,
    );
    $attached_image = wp_insert_attachment(
        $attached_image_args,
        $upload_image['file'],
        $current_user->profile_ID,
        true
    );
    if ( is_wp_error( $attached_image ) ) {
        wp_delete_file( $upload_image['file'] );
        $error['error'] = 'unable to attach image to profile.';
        echo json_encode( $error );
        exit; }

    $image_meta_ID          = $_POST['image-meta-ID'] . '_ID';
    $add_image_ID_to_user   = update_user_meta( $current_user->ID, $image_meta_ID, $attached_image );
    if ( $add_image_ID_to_user === false ) {
        wp_delete_attachment( $attached_image, true );
        $error['error'] = 'unable to update user.';
        echo json_encode( $error );
        exit; }

    if ( $_POST['image-type'] === 'profile-settings-cover' ) {
        $image_src = wp_get_attachment_image_src( $attached_image, 'profile-cover', false ); }
    else if ( $_POST['image-type'] === 'profile-settings-portrait' ) {
        $image_src = wp_get_attachment_image_src( $attached_image, 'profile-portrait', false ); }

    $image_meta_link            = $_POST['image-meta-ID'] . '_link';
    $add_image_link_to_user     = update_user_meta( $current_user->ID, $image_meta_link, $image_src[0] );
    if ( $add_image_link_to_user === false ) {
        wp_delete_attachment( $attached_image, true );
        $error['error'] = 'unable to update user.';
        echo json_encode( $error );
        exit; }

    $current_image = $_POST['current-image'];
    if ( isset( $current_image ) && $current_image !== 'default' ) {
        wp_delete_attachment( $current_image, true ); }

    $response = array(
        'image' => $image_src[0],
        'id' => $attached_image
    );
    echo json_encode( $response );
    exit;
 ?>

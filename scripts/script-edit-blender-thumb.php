<?php

    $current_user = wp_get_current_user();

    $errors = array();

    if ( !wp_verify_nonce($_POST['nonce'], 'frontend-ajax-nonce')) {
            $errors['thumb'] = 'insecure form submission.';
            echo json_encode( $errors );
            exit; }

    //Upload and set post thumbnail
    $thumb = $_FILES['thumb'];

    if ( empty( $thumb ) ) {
        $response = array( 'thumb_edited' => 'unchanged' );
        echo json_encode( $response );
        exit; }

    $current_thumb = get_attached_media( 'image', $post_id );

    $current_thumb_ID = array_column( $current_thumb, 'ID' );
    wp_delete_attachment( $current_thumb_ID[0], true );

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $upload_thumb = wp_handle_upload( $_FILES['thumb'], array( 'test_form' => false )  );
    if ( array_key_exists("upload_error_handler", $upload_thumb )
        || array_key_exists("error", $upload_thumb ) ) {
        $errors['thumb'] = 'unable to upload feature image.';
        echo json_encode( $errors );
        exit; }

    $insert_thumb = wp_insert_attachment(
        array( 'post_mime_type' => $upload_thumb['type'] ),
        $upload_thumb['file'],
        $post_id,
        true );

    if ( is_wp_error( $insert_thumb ) ) {
        $errors['thumb'] = 'unable to attach feature image.';
        echo json_encode( $errors );
        exit; }
    $set_thumb = set_post_thumbnail( $post_id, $insert_thumb );

    if ( $set_thumb === false ) {
        $errors['thumb'] = 'unable to set feature image.';
        echo json_encode( $errors );
        exit; }

    $get_thumb_url = get_the_post_thumbnail_url( $post_id );

    $response = array( 'thumb_edited' => $get_thumb_url );
    echo json_encode( $response );
    exit;

 ?>

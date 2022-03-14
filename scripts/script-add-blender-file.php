<?php
    $current_user = wp_get_current_user();

    if ( !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'frontend-ajax-nonce')) {
            $errors['file'] = 'insecure form submission.';
            echo json_encode( $errors );
            exit; }

    $new_post_args = array(
        'post_type'      => 'blender',
        'post_author'    => $current_user->ID,
        'post_status'    => 'draft',
        'comment_status' => 'closed'
    );

    //Create blender post
    $new_post = wp_insert_post( $new_post_args, true );
    if ( is_wp_error( $new_post ) ) {
        $errors['file'] = 'unable to create post.';
        echo json_encode( $errors );
        exit; }

    //Upload .blend file
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    $upload_file = wp_handle_upload( $_FILES['file'], array( 'test_form' => false ) );

    if ( array_key_exists("upload_error_handler", $upload_file )
        || array_key_exists("error", $upload_file ) ) {
            wp_delete_post( $new_post, true );
            $errors['file'] = 'unable to upload file.';
            echo json_encode( $errors );
            exit; }

    //Rename .blend file
    $file_name        = $current_user->display_name . '-' . $new_post . '.blend';
    $upload_directory = wp_upload_dir();
    $rename           = rename( $upload_file['file'], $upload_directory['path']  . '/' . $file_name );
    if ( $rename === false ) {
        wp_delete_file( $upload_directory['path']  . '/' . $file_name );
        wp_delete_post( $new_post, true );
        $errors['file'] = 'unable to rename file.';
        echo json_encode( $errors );
        exit; }

    //Insert file as attachment and add blender_file post meta
    $file             = $upload_directory['path']  . '/' . $file_name;
    $file_object_args = array(
        'guid'           => $upload_directory['url'] . '/' . $file_name,
        'post_mime_type' => $_FILES['file']['type'],
        'post_name'      => $current_user->display_name . '-' . $new_post,
        'post_status'    => 'inherit',
        'comment_status' => 'closed'
    );
    $file_id = wp_insert_attachment( $file_object_args, $file, $new_post, true );

    if ( is_wp_error( $file_id ) || $update_blender_post_meta === false ) {
        wp_delete_file( $upload_directory['path']  . '/' . $file_name );
        wp_delete_post( $new_post, true );
        wp_delete_attachment( $file_id, true );
        $errors['file'] = 'unable to attach file to post.';
        echo json_encode( $errors );
        exit; }

    //Sets post_name
    global $wpdb;
    $wpdb->update( 'wp_posts',
             array( 'post_name' => $current_user->display_name . '-' . $new_post ),
             array( 'ID' => $new_post ) );


    //Upload and set post thumbnail
    $thumb = $_FILES['thumb'];

    $current_thumb = get_attached_media( 'image', $new_post );
    if ( count($current_thumb) === 0 && $thumb === null ) {
        $errors['thumb'] = 'feature image is required.';
        echo json_encode( $errors );
        exit; }

    if ( $thumb !== null ) {
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
            $new_post,
            true );
        if ( is_wp_error( $insert_thumb ) ) {
            $errors['thumb'] = 'unable to attach feature image.';
            echo json_encode( $errors );
            exit; }
        $set_thumb = set_post_thumbnail( $new_post, $insert_thumb );
        if ( $set_thumb === false ) {
            $errors['thumb'] = 'unable to set feature image.';
            echo json_encode( $errors );
            exit; }

    }

    $response = array( 'success' => $new_post );
    echo json_encode( $response );
    exit;

 ?>

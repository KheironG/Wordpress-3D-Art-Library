<?php
    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
            $error['error'] = 'insecure form submission.';
            echo json_encode( $error );
            exit;
    }

    $allow_comments_args = array(
        'ID'             => $data->post_id,
        'comment_status' => sanitize_text_field( $data->comments ) );
    $allow_comments = wp_update_post( $allow_comments_args, true );
    if ( is_wp_error( $allow_comments ) ) {
        $error['error'] = 'unable to set options.';
        echo json_encode( $error );
        exit; }
    $allow_download = update_post_meta( $data->post_id, 'allow_download', sanitize_text_field( $data->download ) );
    $add_license = update_post_meta( $data->post_id, 'license', sanitize_text_field( $data->license ) );

    $get_post = get_post( $data->post_id );

    $response = array( 'success' => wp_make_link_relative( get_home_url() . '/creators-gallery?case=' . $get_post->post_status ) );
    echo json_encode( $response );
    exit;
 ?>

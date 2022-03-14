<?php

    global $wpdb;

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        $error['error'] = 'insecure form submission.';
        echo json_encode( $error );
        exit; }

    $current_user = wp_get_current_user();

    $client_key = sanitize_text_field( $data->key );

    if ( $client_key === '' || empty( $client_key ) ) {
        $error['error'] = 'verification key required.';
        echo json_encode( $error );
        exit; }

    $server_key = get_user_meta( $current_user->ID, 'verify_email_key', true );

    if ( $server_key === 'false' ) {
        $error['error'] = 'unable to verify key.';
        echo json_encode( $error );
        exit; }

    if ( strcmp( $client_key, $server_key ) !== 0  ) {
        $error['error'] = 'please check your verification key.';
        echo json_encode( $error );
        exit; }

    $new_email = get_user_meta( $current_user->ID, 'new_email', true );

    if ( $new_email === 'false' ) {
        $error['error'] = 'unable to process request.';
        echo json_encode( $error );
        exit; }

    $update_email_args = array(
        'ID' => $current_user->ID,
        'user_email' => $new_email
    );

    $update_email = wp_update_user( $update_email_args );

    if ( is_wp_error( $update_email ) ) {
        $error['error'] = 'unable to change email.';
        echo json_encode( $error );
        exit; }

    delete_user_meta( $current_user->ID, 'new_email' );
    delete_user_meta( $current_user->ID, 'verify_email_key' );

    $current_user = wp_get_current_user();

    $response = array(
        'success' => $current_user->user_email
    );
    echo json_encode( $response );
    exit;

 ?>

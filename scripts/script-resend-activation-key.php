<?php

    global $wpdb;

    $error = array();

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        $error['error'] = 'insecure attempt.';
        echo json_encode( $error );
        exit; }

    $email = $wpdb->prepare( $data->email );

    if ( !is_email( $email ) ) {
        $error['error'] = 'valid email is required.';
        echo json_encode( $error );
        exit; }

    $user = get_user_by( 'email', $email );
    if ( $user === false ) {
        $error['error'] = 'unable to resend key.';
        echo json_encode( $error );
        exit; }

    $activation_key = get_user_meta( $user->ID, 'account_activation_key', true );

    if ( $activation_key === false || $activation_key === "" || empty( $activation_key ) ) {
        $error['error'] = 'unable to resend key.';
        echo json_encode( $error );
        exit; }

    $site            = get_bloginfo( 'name' );
    $url             = get_bloginfo( 'url' ) . '/activate-account';
    $email_args = array( 'key' => $activation_key , 'site' => $site, 'url' => $url, 'user' => $user->display_name );
    //sign_up_email defined in functions.php
    $body = sign_up_email( $email_args );
    $send_email = wp_mail( $email, 'Activate account.', $body );
    if ( !$send_email ) {
        $error['error'] = 'unable to send email. please contact the site adminstrator.';
        echo json_encode( $error );
        exit; }

    echo json_encode( 'success' );
    exit;
?>

<?php

    global $wpdb;

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        $error['error'] = 'insecure attempt.';
        echo json_encode( $error );
        exit; }

    $email = $wpdb->prepare( $data->email );

    if ( !ctype_space( $email ) && !is_email( $email ) ) {
        $error['error'] = 'valid email is required.';
        echo json_encode( $error );
        exit; }

    if ( !email_exists( $email ) ) {
        $error['error'] = 'unable to reset password for this email.';
        echo json_encode( $error );
        exit; }

    $user = get_user_by( 'email', sanitize_email( $email ) );
    $new_password = bin2hex( random_bytes( 3 ) );

    $update_password_data = array(
        'ID' => $user->ID,
        'user_pass' => $new_password,
    );
    $update_password = wp_update_user( $update_password_data );
    if ( is_wp_error( $update_password ) ) {
        $error['error'] = 'unable to reset password. please try again.';
        echo json_encode( $error );
        exit;
    }

    $site            = get_bloginfo( 'name' );
    $url             = get_bloginfo( 'url' ) . '/creators-space';
    $email_args = array(
        'password' => $new_password,
        'site' => $site,
        'url' => $url,
        'user' => $user->display_name
    );
    $body = reset_password_email( $email_args );

    $email_sent = wp_mail( $email, 'Password reset.', $body );

    if ( $email_sent === false ) {
        $error['error'] = 'unable to reset password. please try again.';
        echo json_encode( $error );
        exit; }

    echo json_encode( 'success' );
    exit;

?>

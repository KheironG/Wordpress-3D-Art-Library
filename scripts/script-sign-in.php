<?php

    global $wpdb;

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce' ) ) {
        $error['error'] = 'insecure sign in attempt.';
        echo json_encode( $error );
        exit;
    }

    $email     = sanitize_email( $data->email );
    $password  = esc_attr( $data->pass );

    $authenticate = wp_authenticate( $email, $password );
    if ( is_wp_error( $authenticate ) ) {
        $error['error'] = 'invalid credentials.';
        echo json_encode( $error );
        exit; }

    $activate = get_user_meta( $authenticate->ID, 'account_activation_key', true );

    if ( $activate === "" || $activate === false ) {
        $sign_in_creds = array(
            'user_login'    => $email,
            'user_password' => $password );

        $sign_in = wp_signon( $sign_in_creds, true );

        if ( is_wp_error( $sign_in ) ) {
            $error['error'] = 'unable to sign you in.';
            echo json_encode( $error );
            exit;
        }

        $current_user = wp_set_current_user( $sign_in->ID );

        if ( in_array( 'author', (array) $current_user->roles ) ) {
            $sign_in_link = get_home_url() . '/profile/' . $current_user->display_name; }
        else {
            $sign_in_link = get_home_url() . '/admin'; }

        if ( empty( $sign_in_link ) ) {
            $error['error'] = 'unable to sign you in.';
            echo json_encode( $error );
            exit; }

        $response = array( 'sign_in' => $sign_in_link );
        echo json_encode( $response );
        exit; }

    $activate = array( 'activate' => get_home_url() . '/activate-account' );
    echo json_encode( $activate );
    exit;

?>

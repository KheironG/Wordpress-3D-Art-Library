<?php

    global $wpdb;

    $error = array(
        'errors' => array(),
        'new' => array(),
        'confirm' => array()
    );

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        array_push( $error['errors'], 'insecure form submission.' );
        echo json_encode( $error );
        exit;
    }

    $user = wp_get_current_user();

    $new_email     = $wpdb->prepare( $data->new_email );
    $confirm_email = $wpdb->prepare( $data->confirm_email );

    if ( empty( $new_email ) ) {
        array_push( $error['new'], 'new email is required.' ); }
        
    if ( !empty( $new_email ) && !is_email( $new_email ) ) {
        array_push( $error['new'], 'valid email required.' ); }

    if ( !empty( $new_email ) && email_exists( $new_email ) ) {
        array_push( $error['new'], 'email not available.' ); }

    if ( !empty( $new_email ) && is_email( $new_email ) && !email_exists( $new_email ) && empty( $confirm_email ) ) {
        array_push( $error['confirm'], 'please confirm new email.' ); }

    if ( !empty( $new_email ) && is_email( $new_email ) && !email_exists( $new_email ) &&
        !empty( $confirm_email ) && strcmp( $new_email, $confirm_email ) !== 0 ) {
        array_push( $error['confirm'], 'emails do not match.' ); }

    if ( !empty( $error['errors'] )
            || !empty( $error['new'] )
                || !empty( $error['confirm'] ) ) {
        echo json_encode( $error );
        exit; }

    $verify_key = bin2hex(random_bytes( 3 ) );

    $store_new_email        = update_user_meta( $user->ID, 'new_email', sanitize_email( $new_email ) );
    $store_verification_key = update_user_meta( $user->ID, 'verify_email_key', $verify_key );

    if ( $store_new_email !== false && $store_verification_key !== false  ) {

        $change_email_args = array( 'user_name' => $current_user->display_name, 'verify_key' => $verify_key );
        //function change_email_email() defined in functions.php
        $message = change_email_email( $change_email_args );

        $email_sent = wp_mail( $new_email, 'Change email request.', $message );

        if ( !$email_sent ) {
            delete_user_meta( $current_user->ID, 'new_email' ) ;
            delete_user_meta( $current_user->ID, 'verify_email_key' );
            array_push( $error['errors'], 'unable to send verification email.' );
            echo json_encode( $error );
            exit;
        }

        echo json_encode( 'success' );
        exit;

    } else {
        delete_user_meta( $current_user->ID, 'new_email' );
        delete_user_meta( $current_user->ID, 'verify_email_key' );
        array_push( $error['errors'], 'unable to process your request.' );
        echo json_encode( $error );
        exit;
    }
?>

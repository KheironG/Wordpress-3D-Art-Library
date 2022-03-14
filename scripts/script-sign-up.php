<?php

    global $wpdb;

    $errors = array(
        'username' => array(),
        'email' => array(),
        'password' => array(),
        'confirm' => array(),
        'errors' => array(),
    );

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce' ) ) {
        array_push( $errors['errors'], 'insecure sign up attempt.');
        echo json_encode( $errors );
        exit;
    }

    $username = $wpdb->prepare( $data->username );
    $alphabet = '/^[A-Za-z]+$/';
    if ( empty( $username ) ) {
        array_push( $errors['username'], 'required.' ); }
    if ( !empty( $username ) && preg_match( $alphabet, $username[0] ) === 0 ) {
        array_push( $errors['username'], 'must begin with a-z.' ); }
    if ( username_exists( $username ) ) {
        array_push( $errors['username'], 'not available.' ); }
    if ( !empty( $username) && !validate_username( $username ) ) {
        array_push( $errors['username'], 'invalid.' ); }
    if ( !empty( $username) && stripos( $username, ' ' ) ) {
        array_push( $errors['username'], 'cannot contain spaces.' ); }
    if ( !empty( $username) && strlen( $username ) < 4 || strlen( $username ) > 12 ) {
        array_push( $errors['username'], 'must be between 4 and 12 characters.' ); }


    $email = $wpdb->prepare( $data->email );
    if ( empty( $email ) ) {
        array_push( $errors['email'], 'required.' ); }
    if ( !empty( $email ) && !is_email( $email ) ) {
        array_push( $errors['email'], 'invalid email.' ); }
    if ( !empty( $email ) && email_exists( $email ) ) {
        array_push( $errors['email'], 'invalid email.' ); }


    $password = $wpdb->prepare( $data->pass );
    if ( empty( $password ) ) {
        array_push( $errors['password'], 'required.' ); }
    if ( !empty( $password) && stripos( $password, ' ' ) ) {
        array_push( $errors['password'], 'password cannot contain spaces.' ); }
    if ( !empty( $password) && strlen( $password ) < 6 ) {
        array_push( $errors['password'], 'password must be 6 characters or more.' ); }

    $confirm_password = $wpdb->prepare( $data->confirm_pass );
    if ( empty( $confirm_password ) ) {
        array_push( $errors['confirm'], 'required.' ); }
    if ( !empty( $confirm_password ) && strcmp( $password, $confirm_password ) !== 0 ) {
        array_push( $errors['confirm'], 'passwords do not match.' ); }

    foreach ($errors as $error ) {
        if ( !empty( $error ) ) {
            $has_errors = true;
        } }
    if ( $has_errors === true ) {
        echo json_encode( $errors );
        exit; }

    $sign_up_creds = array(
        'user_pass' => esc_attr( $password ),
        'user_login' => sanitize_user( $username ),
        'user_email' => sanitize_email( $email ),
        'display_name' => sanitize_user( $username ),
        'use_ssl' => 'true',
        'user_registered' => current_time( 'mysql', true ),
        'show_admin_bar_front' => 'false',
        'role' => 'author'
    );

    $insert_user = wp_insert_user( $sign_up_creds );
    if ( is_wp_error( $insert_user ) ) {
        array_push( $errors['errors'], 'unable to create account. please try again.' );
        echo json_encode( $errors );
        exit; }

    //Generates and adds activation key to database
    $activation_key = bin2hex(random_bytes(3));
    $add_activation_key = update_user_meta( $insert_user, 'account_activation_key', $activation_key );
    if ( !$add_activation_key ) {
        array_push( $errors['errors'], 'sign up failed. please try again' );
        wp_delete_user( $insert_user );
        echo json_encode( $errors );
        exit; }

    //Sends sign up confirmation/ account activation email to user
    $site            = get_bloginfo( 'name' );
    $url             = get_bloginfo( 'url' ) . '/activate-account';
    $email_args = array( 'key' => $activation_key , 'site' => $site, 'url' => $url, 'user' => $username );
    //sign_up_email defined in functions.php
    $body = sign_up_email( $email_args );

    $send_email = wp_mail( $email, 'Activate account.', $body );
    if ( !$send_email ) {
        array_push( $errors['errors'], 'unable to send activation email. please contact the site adminstrator.' );
        wp_delete_user( $insert_user );
        echo json_encode( $errors );
        exit; }

    echo json_encode( 'success' );
    exit;
?>

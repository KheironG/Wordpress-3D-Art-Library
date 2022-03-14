<?php

    global $wpdb;

    $error = array(
        'errors' => array(),
        'current' => array(),
        'new' => array(),
        'confirm' => array()
    );

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        array_push( $error['errors'], 'insecure form submission.' );
        echo json_encode( $error );
        exit;
    }

    $current_user = wp_get_current_user();

    $current_password  = $wpdb->prepare( $data->current );
    $new_password      = $wpdb->prepare( $data->new );
    $confirm_password  = $wpdb->prepare( $data->confirm );

    if ( ctype_space( $current_password ) )  {
        array_push( $error['current'], 'current password is required.' );
    }

    $validate_current_password = wp_check_password(
        $current_password,
        $current_user->user_pass,
        $current_user->ID
    );

    if ( ctype_space( $current_password ) === false && !$validate_current_password ) {
        array_push( $error['current'], 'invalid credential.' );
    }

    if ( ctype_space( $new_password ) === true ) {
        array_push( $error['new'], 'new password is required.' );
    }
    if ( ctype_space( $new_password ) === false && stripos( $new_password , ' ' ) ) {
        array_push( $error['new'], 'cannot contain spaces.' );
    }
    if ( ctype_space( $new_password ) === false && strlen( $new_password ) < 6 ) {
        array_push( $error['new'], 'must be 6 characters or more.' );
    }

    if ( ctype_space( $new_password ) === false && empty( $confirm_password ) ) {
        array_push( $error['confirm'], 'please confirm new password.' );
    }
    if ( ctype_space( $confirm_password ) === false && strcmp( $new_password, $confirm_password ) !== 0 ) {
        array_push( $error['confirm'], 'new passwords do not match.' );
    }

    if ( !empty( $error['errors'] )
            || !empty( $error['current'] )
                || !empty( $error['new'] )
                    || !empty( $error['confirm'] ) ) {
        echo json_encode( $error );
        exit;
    }

    $update_password_creds = array(
        'ID' => $current_user->ID,
        'user_pass' => esc_attr( $new_password )
    );

    $update_password = wp_update_user( $update_password_creds );

    if ( is_wp_error( $update_password ) ) {
        $error['errors'] = 'unable to update password';
        echo json_encode( $error );
        exit;
    }

    $response = array( 'success' => wp_logout_url( get_home_url() . '/creators-space' ) );
    echo json_encode( $response );
    exit;

?>

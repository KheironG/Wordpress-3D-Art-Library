<?php

    global $wpdb;

    $error = array(
        'error' => array()
    );

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        $error['error'] = 'insecure activation attempt.';
        echo json_encode( $error );
        exit; }

    $email    = $wpdb->prepare( $data->email );
    $password = $wpdb->prepare( $data->password );
    $key      = $data->key;

    //checks login credentials
    $is_authenticated = wp_authenticate( $email, $password );
    if ( is_wp_error( $is_authenticated ) ) {
        $error['error'] = 'invalid credentials.';
        echo json_encode( $error );
        exit; }

    //validates activation key
    $key_to_compare = get_user_meta( $is_authenticated->ID, 'account_activation_key', true );
    if ( $key_to_compare === false || $key_to_compare === "" || empty( $key_to_compare ) ) {
        $error['error'] = 'unable to activate account.';
        echo json_encode( $error );
        exit; }
    if ( $key !== $key_to_compare ) {
        $error['error'] = 'please check your activation key.';
        echo json_encode( $error );
        exit; }

    $sign_in_creds = array(
        'user_login' => sanitize_email( $email ),
        'user_password' => esc_attr( $password )
    );
    $sign_in = wp_signon( $sign_in_creds, true );

    wp_set_current_user( $sign_in->ID );

    if ( is_wp_error( $sign_in ) ) {
        $response = array( 'sign_in_failed' => get_home_url() . '/creators-space' );
        echo json_encode( $response );
        exit; }

    $has_profile = get_posts( array( 'author' => $sign_in->ID , 'post_type' => 'profile' ) );
    if ( count( $has_profile ) === 0  ) {
        $profile_args = array(
            'post_type' => 'profile',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'author' => $sign_in->ID,
            'post_title' => $sign_in->display_name,
            'post_name' => $sign_in->display_name,
        );
        $create_profile = wp_insert_post( $profile_args, true, true );
        if ( is_wp_error( $create_profile ) ) {
            $error['error'] = 'unable to create profile. please try again.';
            echo json_encode( $error );
            exit;
        }
    }

    $profile = get_posts( array( 'post_author' => $sign_in->ID , 'post_type' => 'profile' ) );
    if ( count( $profile ) === 0  ) {
        $error['error'] = 'unable to retrieve user profile.'; }

    $add_profile_ID_to_user = update_user_meta( $sign_in->ID, 'profile_ID', $profile[0]->ID );
    if ( $add_profile_ID_to_user === false ) {
        $error['error'] = 'unable to add profile to user.';
    }

    $profile_link = get_post_permalink( $profile[0]->ID );

    $add_cover_ID_to_user = update_user_meta( $sign_in->ID, 'cover_ID', 'default' );
    if ( $add_cover_ID_to_user === false ) {
        $error['error'] = 'unable to add cover to user.';
    }

    $add_cover_link_to_user = update_user_meta(
                                    $sign_in->ID,
                                    'cover_link',
                                    get_template_directory_uri() . '/img/default-cover.png'
                                );
    if ( $add_cover_link_to_user === false ) {
        $error['error'] = 'unable to add cover link to user.';
    }

    $add_portrait_ID_to_user = update_user_meta( $sign_in->ID, 'portrait_ID', 'default' );
    if ( $add_portrait_ID_to_user === false ) {
        $error['error'] = 'unable to add portrait to user.';
    }

    $add_portrait_link_to_user = update_user_meta(
                                    $sign_in->ID,
                                    'portrait_link',
                                    get_template_directory_uri() . '/img/default-portrait.png'
                                );
    if ( $add_portrait_link_to_user === false ) {
        $error['error'] = 'unable to add portrait link to user.';
    }

    $set_category = wp_set_object_terms( $profile[0]->ID, strtolower( $sign_in->display_name[0] ), 'profile_initials' );
    if ( is_wp_error( $set_category ) ) {
        $error['error'] = 'unable to set profile category.';
    }

    if ( !empty( $error['error'] ) ) {
        delete_user_meta( $sign_in->ID, 'profile_ID' );
        delete_user_meta( $sign_in->ID, 'cover_ID' );
        delete_user_meta( $sign_in->ID, 'cover_link' );
        delete_user_meta( $sign_in->ID, 'portrait_ID' );
        delete_user_meta( $sign_in->ID, 'portrait_link' );
        wp_delete_post( $profile[0]->ID, true );
        echo json_encode( $error );
        wp_destroy_current_session();
        wp_clear_auth_cookie();
        wp_set_current_user( 0 );
        exit; }

    $activate_account = delete_user_meta( $sign_in->ID, 'account_activation_key' );
    if ( $activate_account === false ) {
        $error['error'] = 'unable to activate account. please contact the site administrator.';
        echo json_encode( $error );
        exit; }

    $response = array( 'success' => $profile_link );
    echo json_encode( $response );
    exit;

?>

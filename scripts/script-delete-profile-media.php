<?php

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce' ) ) {
            $error['error'] = 'insecure request.';
            echo json_encode( $error );
            exit;
    }

    $current_user = wp_get_current_user();

    if ( $data->image_type === 'profile-settings-cover' ) {

        $image_src              = get_template_directory_uri() . '/img/default-cover.png';
        $update_ID_in_user      = update_user_meta( $current_user->ID, 'cover_ID', 'default' );
        $update_link_in_user    = update_user_meta( $current_user->ID, 'cover_link', $image_src );
        $update_link_in_profile = update_post_meta( $current_user->profile_ID, 'cover_link', $image_src  );

        if ( $update_ID_in_user === false || $update_link_in_user === false || $update_link_in_profile === false  ) {
            $error['error'] = 'unable to delete process request.';
            echo json_encode( $error );
            exit;
        }

    }

    if ( $data->image_type === 'profile-settings-portrait' ) {

        $image_src              = get_template_directory_uri() . '/img/default-portrait.png';
        $update_ID_in_user      = update_user_meta( $current_user->ID, 'portrait_ID', 'default' );
        $update_link_in_user    = update_user_meta( $current_user->ID, 'portrait_link', $image_src );
        $update_link_in_profile = update_post_meta( $current_user->profile_ID, 'portrait_link', $image_src  );

        if ( $update_ID_in_user === false || $update_link_in_user === false || $update_link_in_profile === false  ) {
            $error['error'] = 'unable to delete process request.';
            echo json_encode( $error );
            exit;
        }
    }

    $current_image_id = $data->current_image_type;

    if ( $current_image_id !== 'default' ) {
        wp_delete_attachment( $current_image_id, true );
    }

    $response = array(
        'image' => $image_src,
        'id' => 'default'
    );
    echo json_encode( $response );
    exit;

<?php

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce' ) ) {
        $error['error'] = 'insecure form submission.';
        echo json_encode( $error );
        exit;
    }

    $user_ID = get_current_user_id();

    update_user_meta( $user_ID, 'first_name', sanitize_textarea_field( $data->first_name ) );
    update_user_meta( $user_ID, 'last_name', sanitize_text_field( $data->last_name ) );

    $first_name = get_user_meta( $user_ID, 'first_name', true );
    $last_name  = get_user_meta( $user_ID, 'last_name', true );

    $response = array(
        'success' => array(
            'first_name' => $first_name,
            'last_name' => $last_name,
        )
    );

    echo json_encode( $response );
    exit;

?>

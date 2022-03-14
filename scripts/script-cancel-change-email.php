<?php

global $wpdb;

if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
    $error['error'] = 'insecure form submission.';
    echo json_encode( $error );
    exit;
}

$user = wp_get_current_user();

$delete_verification_key = delete_user_meta( $user->ID, 'verify_email_key' );
if ( $delete_verification_key === true ) {
    $delete_new_email = delete_user_meta( $user->ID, 'new_email' );
} else {
    $error['error'] = 'unable to cancel request.';
    echo json_encode( $error );
    exit;
}

echo json_encode( 'success' );
exit;

 ?>

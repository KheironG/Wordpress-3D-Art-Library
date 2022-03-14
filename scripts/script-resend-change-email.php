<?php

if ( !wp_verify_nonce($data->nonce, 'frontend-ajax-nonce') ) {
    $error['error'] = 'insecure form submission.';
    echo json_encode( $error );
    exit;
}

$current_user         = wp_get_current_user();
$get_verification_key = get_user_meta( $current_user->ID, 'verify_email_key', true );
$get_new_email        = get_user_meta( $current_user->ID, 'new_email', true );

if ( empty( $get_verification_key ) && empty( $get_new_email ) ) {
    $error['errors'] = 'unable to process request.';
    echo json_encode( $error );
    exit;
}

$change_email_args = array( 'user_name' => $current_user->display_name, 'verify_key' => $get_verification_key );

// function change_email_email() defined in functions.php
$message = change_email_email( $change_email_args );

$email_sent = wp_mail( $get_new_email, 'Change email request.', $message );

if ( !$email_sent ) {
    $error['errors'] = 'unable to send email. please try again.';
    echo json_encode( $error );
    exit;
}

echo json_encode( 'success' );
exit;

?>

<?php
    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce') ) {
        $error['error'] = 'insecure form submisson.';
        echo json_encode( $error );
        exit; }


    if ( $data->option === 'profile-as-user' ) {
        $sender = wp_get_current_user();
        $sender_name = $sender->user_login;
        $sender_email = $sender->user_email;

        $receiver = get_user_by( 'login', sanitize_text_field( $data->receiver ) );
        $receiver_email = $receiver->user_email; }

    if ( $data->option === 'profile-as-public' ) {
        $sender_name = sanitize_text_field( $data->name );
        $sender_email = sanitize_email( $data->email );

        $receiver = get_user_by( 'login', sanitize_text_field( $data->receiver  ) );
        $receiver_email = $receiver->user_email; }

    if ( $data->option === 'admin-as-user' ) {
        $sender = wp_get_current_user();
        $sender_name = $sender->user_login;
        $sender_email = $sender->user_email;

        $receiver_email = get_bloginfo('admin_email'); }

    if ( $data->option === 'admin-as-public' ) {
        $sender_name = sanitize_text_field( $data->name );
        $sender_email = sanitize_email( $data->email );

        $receiver_email = get_bloginfo('admin_email'); }

    $subject = sanitize_text_field( $data->subject );
    $message = sanitize_textarea_field( $data->message );

    if ( empty( $sender_email ) || !is_email( $sender_email ) ) {
        $error['email'] = 'valid email required.';
        echo json_encode( $error );
        exit; }

    if ( empty( $message ) || ctype_space( $message ) ) {
        $error['message'] = 'message required.';
        echo json_encode( $error );
        exit; }

    $body = '<p>'. $message .'</p>';
    $headers = array('From: '. $sender_name .' <'.$sender_email.'>');

    $send_email = wp_mail( $receiver_email, $subject, $body, $headers );

    if ( $send_email === false ) {
        $error['error'] = 'unable to send email.';
        echo json_encode( $error );
        exit; }

    echo json_encode( 'success' );
    exit;

 ?>

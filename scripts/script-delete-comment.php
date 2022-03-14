<?php

    $error = array(
        'errors' => array()
    );

    if ( !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'comment-nonce')) {
        $error['errors'] = 'insecure attempt.';
        echo json_encode( $error );
        exit;
    }

    $delete_comment = wp_delete_comment( $comment_id, true );

    if ( $delete_comment === false ) {
        $error['errors'] = 'unable to delete comment.';
        echo json_encode( $error );
        exit;
    }

    if ( $delete_comment === true ) {
        echo json_encode( 'success' );
        exit;
    }

?>

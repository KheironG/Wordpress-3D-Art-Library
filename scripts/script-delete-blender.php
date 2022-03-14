<?php

    if ( current_user_can( 'delete_post', $data->post_id ) ) {

        if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce')) {
                $error['error'] = 'insecure form submission.';
                echo json_encode( $error );
                exit;
        }

        $delete_post = wp_delete_post( $data->post_id , true );

        if ( $delete_post === null || $delete_post === false ) {
            $error['error'] = 'unable to delete post. Please try again';
            echo json_encode( $error );
            exit;
        }

        $response = array( 'success' => $delete_post );
        echo json_encode( $response );
        exit;

    } else {
        $error['error'] = 'permission denied.';
        echo json_encode( $error );
        exit;
    }
 ?>

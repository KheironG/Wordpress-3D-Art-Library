<?php

    $strip = str_replace('\\', '', $_POST["data"]);
    $data = json_decode( $strip, false );

    if ( wp_verify_nonce( $data->nonce, 'comment-nonce')) {
        $error['errors'] = 'unable to verify nonce.';
        echo json_encode( $error );
        exit;
    }


    $user = get_userdata( get_current_user_id() );


    if ( !is_user_logged_in() && $user->caps['author'] !== true ) {
        $error['errors'] = 'insufficient permissions to manage comments.';
        echo json_encode( $error );
        exit;
    }


    if ( isset( $data->content ) ) {
        $content = sanitize_textarea_field( $data->content );
        if ( strlen( $content ) > 1000 ) {
            $error['errors'] = '1000 character maximum.';
            echo json_encode( $error );
            exit;
        }
    }


    global $wpdb;
    $comment_ID       = sanitize_key( $data->comment_ID );
    $post_ID          = sanitize_key( $data->post_ID );
    $check_comment_ID = $wpdb->get_var( "SELECT user_id FROM wp_comments WHERE comment_ID = $comment_ID" );
    $is_users         = ( intval( $user->ID ) === intval( $check_comment_ID ) ) ? ( true ) : ( false );


    //ADD
    if ( $task === 'add-comment' ) {

        $comment_data = array(
                'comment_type'       => 'comment',
                'comment_author'     => $user->display_name,
                'comment_post_ID'    => $post_ID,
                'comment_content'    => $content,
                'comment_author_url' => get_home_url() . '/profile' . '/' . $user->display_name,
                'user_id'            => $user->ID,
            );

        $db_query = wp_insert_comment( $comment_data );

        $response_ID = $db_query;

    }


    //REPLY
    if ( $task === 'reply-comment' ) {

        $comment_data = array(
                'comment_type'       => 'comment',
                'comment_parent'     => $comment_ID,
                'comment_post_ID'    => $post_ID,
                'comment_author'     => $user->display_name,
                'comment_content'    => $content,
                'comment_author_url' => get_home_url() . '/profile' . '/' . $user->display_name,
                'user_id'            => $user->ID,
            );

        $db_query = wp_insert_comment( $comment_data );

        $response_ID = $db_query;

    }


    //EDIT
    if ( $task === 'edit-comment' ) {

        if ( $is_users === false ) {
            $error['errors'] = 'insufficient permissions to edit comment.';
            echo json_encode( $error );
            exit;
        }

        $comment_data = array(
                'comment_ID'         => $comment_ID,
                'comment_content'    => $content,
            );

        $db_query = wp_update_comment( $comment_data );

        $response_ID = $data->comment_ID;

    }


    //DELETE
    if ( $task === 'delete-comment' ) {

        if ( $is_users === false ) {
            $error['errors'] = 'insufficient permissions to delete comment.';
            echo json_encode( $error );
            exit;
        }

        $db_query = $wpdb->query( "DELETE FROM wp_comments WHERE comment_ID = $comment_ID OR comment_parent = $comment_ID " );

    }


    if ( $db_query === false ) {
        $error['errors'] = 'unable to complete request.';
        echo json_encode( $error );
        exit;
    }


    //Responses
    if ( $task !== 'delete-comment' ) {

        $get_comment = $wpdb->get_results( "SELECT comment_ID, comment_parent, comment_content,
                                                   comment_author_url, comment_author, comment_date
                                            FROM wp_comments
                                            WHERE comment_ID = $response_ID " );

        require get_template_directory() . '/scripts/class-custom-classes.php' ;

        $response             = new ResponseObject();
        $response->object_ID  = $get_comment[0]->comment_ID;
        $response->parent     = $get_comment[0]->comment_parent;
        $response->content    = $get_comment[0]->comment_content;
        $response->author     = $get_comment[0]->comment_author;
        $response->author_url = $get_comment[0]->comment_author_url;
        $response->date       = $get_comment[0]->comment_date;
        $response->is_users   = $is_users;

        echo json_encode( array( 'success' => $response ) );
        exit;

    }

    if ( $task === 'delete-comment' ) {
        echo json_encode( 'deleted');
        exit;
    }

?>

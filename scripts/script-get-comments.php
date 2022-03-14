<?php

    global $wpdb;
    $ID = sanitize_key( $data->ID );

    // If getting parent comments
    if ( $data->is_parent == 1 ) {
        $get_comments = $wpdb->get_results( "SELECT comment_ID, comment_parent, comment_content, user_id,
                                                    comment_author_url, comment_author, comment_date
                                            FROM wp_comments
                                            WHERE comment_post_ID = $ID
                                            ORDER BY comment_date DESC
                                            LIMIT 50" );
    //If gettings child comments
    } elseif ( $data->is_parent == 0 ) {
        $get_comments = $wpdb->get_results( "SELECT comment_ID, comment_parent, comment_content, user_id,
                                                    comment_author_url, comment_author, comment_date
                                            FROM wp_comments
                                            WHERE comment_parent = $ID
                                            ORDER BY comment_date DESC
                                            LIMIT 50" );
    }

    require get_template_directory() . '/scripts/class-custom-classes.php' ;

    $response = array( 'parents' => array(), 'children' => array() );
    $user = get_current_user_id();

    foreach ( $get_comments as $get_comment ) {

        $comment             = new ResponseObject();
        $is_users         =  ( intval( $user ) === intval( $get_comment->user_id ) ) ? ( true ) : ( false );

        $comment->object_ID  = $get_comment->comment_ID;
        $comment->parent     = $get_comment->comment_parent;
        $comment->content    = $get_comment->comment_content;
        $comment->author     = $get_comment->comment_author;
        $comment->author_url = $get_comment->comment_author_url;
        $comment->date       = $get_comment->comment_date;
        $comment->is_users   = $is_users;

        if ( $get_comment->comment_parent == 0 ) {
            array_push( $response['parents'], $comment );
        } elseif ( $get_comment->comment_parent != 0 ) {
            array_push( $response['children'], $comment );
        }

    }

    echo json_encode( array( 'success' => $response ) );
    exit;

?>

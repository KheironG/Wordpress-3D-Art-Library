<?php

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce' ) ) {
            $error['title'] = 'insecure form submission.';
            echo json_encode( $errors );
            exit; }

    if ( ctype_space($data->title) || empty($data->title)  ) {
        $error['title'] = 'title is required.';
        echo json_encode( $error );
        exit; }
    else {
        $get_post = get_post( $data->post_id );
        if ( $get_post->post_status === 'publish') {
            $status = 'publish'; }
        else {
            $status = 'draft'; }

        $update_post_args = array(
            'ID'          => $data->post_id,
            'post_title'  => $data->title,
            'post_content'=> $data->story,
            'post_status'  => $status
        );
        $update_post = wp_update_post( $update_post_args, true );
        if ( is_wp_error( $update_post ) ) {
            $error['title'] = 'unable to set title.';
            echo json_encode( $error );
            exit; } }


    if ( empty($data->parent_category ) ) {
        $error['category'] = 'category is required.';
        echo json_encode( $error );
        exit; }
    else {
        $categories = array( intval( $data->parent_category ) );
        if ( !empty( $data->child_category ) ) {
            array_push( $categories, intval( $data->child_category ) ); }
        $set_category = wp_set_object_terms( $data->post_id, $categories, 'blender_categories', false );
        if ( is_wp_error( $set_category ) ) {
            $error['category'] = 'unable to set category.';
            echo json_encode( $error );
            exit; } }


    $sanitized_tags = array();
    foreach ($data->tags as $tag ) {
        array_push( $sanitized_tags, sanitize_text_field( $tag ) );
    }
    $set_tags = wp_set_object_terms( $data->post_id, $sanitized_tags, 'post_tag', true );
    if ( is_wp_error( $set_tags ) ) {
        $error['tags'] = 'unable to set tags.';
        echo json_encode( $error );
        exit;
    }


    echo json_encode( 'success' );
    exit;

 ?>

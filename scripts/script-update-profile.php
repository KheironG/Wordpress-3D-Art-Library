<?php
    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce' ) ) {
        $error['error'] = 'insecure form submission.';
        echo json_encode( $error );
        exit;
    }

    $current_user = wp_get_current_user();

    if ( $task === 'profile-details' ) {

        $update_bio_args = array(
            'ID'          => $current_user->profile_ID,
            'post_content'=> sanitize_textarea_field( $data->bio ),
        );
        $update_bio = wp_update_post( $update_bio_args, true );
        if ( is_wp_error( $update_bio ) ) {
            $error['error'] = 'unable to set bio.';
            echo json_encode( $error );
            exit; }

        $profile_details = array(
            'country'       => sanitize_text_field( $data->country ),
            'city'          => sanitize_text_field( $data->city ),
        );
        update_user_meta( $current_user->ID, 'profile_details', $profile_details );

        $profile_styling = array(
            'background'    => sanitize_text_field( $data->background ),
            'colour'        => sanitize_text_field( $data->colour )
        );
        update_user_meta( $current_user->ID, 'profile_styling', $profile_styling );

        //Set tags
        $tags = explode( "," , $data->tags );
        if ( count( $tags ) > 10 ) {
            $error['error'] = 'tag limit is 10.';
            echo json_encode( $error );
            exit;}
        $sanitized_tags = array();
        foreach ($tags as $tag ) {
            array_push( $sanitized_tags, sanitize_text_field( $tag ) ); }

        $set_tags = wp_set_post_tags( $current_user->profile_ID, $sanitized_tags, false );
        if ( is_wp_error( $set_tags ) ) {
            $error['error'] = 'unable to set tags.';
            echo json_encode( $error );
            exit; }

        $set_profession = wp_set_object_terms( $current_user->profile_ID, sanitize_text_field( $data->profession ), 'profile_categories', false );
        if ( is_wp_error( $set_profession ) ) {
            $error['error'] = 'unable to set profession.';
            echo json_encode( $error );
            exit; }

        $set_blender_category = wp_set_object_terms( $current_user->profile_ID, sanitize_text_field( $data->main_category ), 'blender_categories', false );
        if ( is_wp_error( $set_blender_category ) ) {
            $error['error'] = 'unable to set category.';
            echo json_encode( $error );
            exit; }

        $get_profile_details    = get_user_meta( $current_user->ID, 'profile_details', true );
        $get_profile_styling    = get_user_meta( $current_user->ID, 'profile_styling', true );
        $response = array(
            'profile_details'    => $get_profile_details,
            'profile_styling'    => $get_profile_styling,
            'bio'                => get_post_field( 'post_content', $current_user->profile_ID )
        );

    }

    if ( $task === 'profile-social' ) {
        $profile_social = array(
            'website'   => esc_url_raw( $data->website ),
            'facebook'  => esc_url_raw( $data->facebook ),
            'instagram' => esc_url_raw( $data->instagram ),
            'twitter'   => esc_url_raw( $data->twitter ),
            'youtube'   => esc_url_raw( $data->youtube ),
            'linkedin'  => esc_url_raw( $data->linkedin )
        );

        update_user_meta( $current_user->ID, 'profile_social', $profile_social );
        $get_profile_social = get_user_meta( $current_user->ID, 'profile_social', true );

        $response = array(
            'profile_social' => $get_profile_social
        );

    }

    if ( $task === 'profile-contact' ) {
        update_user_meta( $current_user->ID, 'contact_me', intval( $data->contact ) );
        $get_contact_me = get_user_meta( $current_user->ID, 'contact_me', true );
        $response = array(
            'profile_contact' => $get_contact_me
        );
    }

    echo json_encode( $response );
    exit;
?>

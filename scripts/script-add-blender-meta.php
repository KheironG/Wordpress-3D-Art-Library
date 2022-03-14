<?php

    $current_user = wp_get_current_user();

    if ( !wp_verify_nonce( $data->nonce, 'frontend-ajax-nonce')) {
            $error['error'] = 'insecure form submission.';
            echo json_encode( $error );
            exit; }

    $blender_meta = array(
        'triangles'      => sanitize_text_field( $data->triangles ) ,
        'quads'          => sanitize_text_field( $data->quads ),
        'polygons'       => sanitize_text_field( $data->polygons ),
        'vertices'       => sanitize_text_field( $data->vertices ),
        'pbr'            => sanitize_text_field( $data->pbr ),
        'textures'       => sanitize_text_field( $data->textures ),
        'materials'      => sanitize_text_field( $data->materials ),
        'uv_layers'      => sanitize_text_field( $data->uv_layers ),
        'vertex_colours' => sanitize_text_field( $data->vertex_colours ),
        'animations'     => sanitize_text_field( $data->animations ),
        'morph_geo'      => sanitize_text_field( $data->morph_geo ),
        'rigged_geo'     => sanitize_text_field( $data->rigged_geo ),
        'scales'         => sanitize_text_field( $data->scales )
    );

    $add_blender_meta = update_post_meta( $data->post_id, 'blender_meta', $blender_meta );

    echo json_encode( 'success' );
    exit;
 ?>

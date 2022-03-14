<?php

global $wpdb;

$tax_name = sanitize_text_field( $_GET['tax-name'] );
$tax_ID   = sanitize_key( $_GET['tax-ID'] );
$type     = $tax_ID === 'null' && $tax_name !== 'null' ? 'post_name' : 'term_id';

//Get matches by taxonomy ID or CPT profile title.
if ( $type === 'term_id' ) {
    $autofill_object_matches = $wpdb->get_results( "SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id = $tax_ID LIMIT 3000" );
} elseif ( $type === 'post_name' ) {
    $autofill_object_matches = $wpdb->get_results( "SELECT ID FROM wp_posts WHERE post_name='$tax_name' AND post_type='profile'" );
}

if ( $autofill_object_matches === null ) {
    $errors['error'] = 'unable to match query.';
    echo json_encode( $errors );
    exit;
}

$posts_cache= check_set_posts_cache();
if ( empty( $posts_cache ) ) {
    $errors['error'] = 'unable to check posts cache.';
    echo json_encode( $errors );
    exit;
}


//Gets post matches from posts cache object
$object_matches = array();
foreach ( $autofill_object_matches as $match ) {
    if ( $type === 'term_id' ) {
        array_push( $object_matches, $posts_cache[$match->object_id] );
    } elseif ( $type === 'post_name' ) {
        array_push( $object_matches, $posts_cache[$match->ID] );
    }
}


//Compiles matches for output
$compiled_matches = array();
require get_template_directory() . '/scripts/class-custom-classes.php' ;

if ( $type === 'term_id' ) {
    $queried_tax_name = $tax_name;
} elseif ( $type === 'post_name' ) {
    $queried_tax_name = 'artist';
}

foreach ( $object_matches as $object_match ) {
    $compiled = new ResponseObject();

    if ( $object_match->post_type === 'profile' ) {
        $compiled->link         = get_home_url() . '/profile/' . $object_match->post_title;
        $compiled->type         = 'artist';
        $compiled->queried_tax  = $queried_tax_name;
        $compiled->title        = $object_match->post_title;
        $compiled->content      = $object_match->post_content;
        $compiled->object_count = count_user_posts( $object_match->post_author, 'blender', true );
        $compiled->thumbnail    = get_user_meta( $object_match->post_author, 'portrait_link', true );
        array_push( $compiled_matches, $compiled );
    }

    if ( $object_match->post_type === 'blender' && $object_match->post_status === 'publish' ) {
        $compiled->link        = get_home_url() . '/blender/' . $object_match->post_name;
        $compiled->type        = '3d object';
        $compiled->title       = $object_match->post_title;
        $compiled->content     = $object_match->post_content;
        $compiled->queried_tax = $queried_tax_name;
        $compiled->author      = get_the_author_meta( 'display_name', $object_match->post_author );
        $compiled->thumbnail   = get_the_post_thumbnail_url( $object_match->ID );
        array_push( $compiled_matches, $compiled );
    }

    if ( $object_match->post_type === 'blender' && $object_match->post_status === 'draft' ) {
        continue;
    }

    // Checks for match with post_type post and category 'blog'
    if ( $object_match->post_type === 'post' && $object_match->post_status === 'publish' ) {
        if ( has_category( 'blog', $object_match->ID ) ) {
            $compiled->link        = get_home_url() . '/' . $object_match->post_name;
            $compiled->type        = 'blog';
            $compiled->title       = $object_match->post_title;
            $compiled->content     = $object_match->post_excerpt;
            $compiled->queried_tax = $queried_tax_name;
            $compiled->author      = get_the_author_meta( 'display_name', $object_match->post_author );
            $compiled->thumbnail   = get_the_post_thumbnail_url( $object_match->ID );
            array_push( $compiled_matches, $compiled );
        } else {
            continue;
        }
    }

    // If page
    if ( $object_match->post_type === 'page' && $object_match->post_status === 'publish' ) {
        $compiled->link        = get_home_url() . '/' . $object_match->post_name;
        $compiled->type        = 'page';
        $compiled->title       = $object_match->post_title;
        $compiled->content     = $object_match->post_excerpt;
        $compiled->queried_tax = $queried_tax_name;
        $compiled->author      = get_the_author_meta( 'display_name', $object_match->post_author );
        $compiled->thumbnail   = get_template_directory_uri() . '/img/header-low-poly.png';
        array_push( $compiled_matches, $compiled );
    }

}

$response = array( 'success' => $compiled_matches );
echo json_encode( $response );
exit;

?>

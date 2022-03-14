<?php

global $wpdb;

$get_terms = check_set_terms_cache();
$get_posts = check_set_posts_cache();

if ( empty( $get_terms ) || empty( $get_posts ) ) {
    $error['error'] = 'unable to load cache.';
    echo json_encode( $error );
    exit;
}

$search_query_string = sanitize_text_field( $_GET['search-query'] );
$pattern = '/\b' . $search_query_string . '\b/';

//checks for matches in taxnomoy names
$terms_matches = array();
foreach ($get_terms['autofill_cache'] as $term ) {
    if ( $term['query_type'] === 'category' || $term['query_type'] === 'tag' ) {
        if (  preg_match( $pattern, strtolower( $term['value'] ) ) ) {
            array_push( $terms_matches, $term['term_id'] );
        }
    }
}

//compiles post IDs from matching taxonomy IDs
$term_posts = array();
if ( !empty( $terms_matches ) ) {
    foreach ($terms_matches as $term_match ) {
        $term_post_IDs = $wpdb->get_results( "SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id = $term_match" );
        foreach ($term_post_IDs as $term_post_ID ) {
            array_push( $term_posts, $term_post_ID->object_id);
        }
    }
}


//checks for matches in post content and post titles and complies post IDs
$content_posts = array();
foreach ( $get_posts as $content_post ) {
 if (  preg_match( $pattern, strtolower( $content_post->post_content ) ) || preg_match( $pattern, strtolower( $content_post->post_title ) ) ) {
     array_push( $content_posts, $content_post->ID );
 }
}

//merges all posts IDs, removes duplicate IDs, and complies all match from posts cache
$post_match_IDs = array_unique( array_merge( $term_posts, $content_posts ) );
$post_matches = array();
foreach ( $post_match_IDs as $post_match_ID ) {
    array_push( $post_matches, $get_posts[$post_match_ID]);
}

$compiled_matches = array();
require get_template_directory() . '/scripts/class-custom-classes.php' ;
foreach ( $post_matches as $post_match ) {
    $compiled = new ResponseObject();

    // If profile object
    if ( $post_match->post_type === 'profile' ) {
        $compiled->link          = get_home_url() . '/profile/' . $object_match->post_name;
        $compiled->type          = 'artist';
        $compiled->queried_tax   = $search_query_string;
        $compiled->title         = $post_match->post_title;
        $compiled->content       = $post_match->post_content;
        $compiled->object_count  = count_user_posts( $post_match->post_author, 'blender', true );
        $compiled->thumbnail     = get_user_meta( $post_match->post_author, 'portrait_link', true );
        array_push( $compiled_matches, $compiled );
    }

    // If blender object
    if ( $post_match->post_type === 'blender' && $post_match->post_status === 'publish' ) {
        $compiled->link          = get_home_url() . '/blender/' . $object_match->post_name;
        $compiled->type          = '3d object';
        $compiledcompiled->title = $post_match->post_title;
        $compiled->content       = $post_match->post_content;
        $compiled->queried_tax   = $search_query_string;
        $compiled->author        = get_the_author_meta( 'display_name', $post_match->post_author );
        $compiled->thumbnail     = get_the_post_thumbnail_url( $post_match->ID );
        array_push( $compiled_matches, $compiled );
    }

    // Checks for match with post_type post and category 'blog'
    if ( $post_match->post_type === 'post' && $post_match->post_status === 'publish' ) {
        if ( has_category( 'blog', $post_match->ID ) ) {
            $compiled->link          = get_home_url() . '/' . $object_match->post_name;
            $compiled->type          = 'blog';
            $compiledcompiled->title = $post_match->post_title;
            $compiled->content       = $post_match->post_excerpt;
            $compiled->queried_tax   = $search_query_string;
            $compiled->author        = get_the_author_meta( 'display_name', $post_match->post_author );
            $compiled->thumbnail     = get_the_post_thumbnail_url( $post_match->ID );
            array_push( $compiled_matches, $compiled );
        } else {
            continue;
        }
    }

    // If page
    if ( $post_match->post_type === 'page' && $post_match->post_status === 'publish' ) {
        $compiled->link          = get_home_url() . '/' . $object_match->post_name;
        $compiled->type          = 'page';
        $compiledcompiled->title = $post_match->post_title;
        $compiled->content       = $post_match->post_excerpt;
        $compiled->queried_tax   = $search_query_string;
        $compiled->author        = get_the_author_meta( 'display_name', $post_match->post_author );
        $compiled->thumbnail     = get_template_directory_uri() . '/img/header-low-poly.png';
        array_push( $compiled_matches, $compiled );
    }
}

$response = array( 'success' => $compiled_matches );
echo json_encode( $response );
exit;

?>

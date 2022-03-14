<?php
global $wpdb;
$origin      = $data->origin;
$output_type = $data->output_type;

if ( $origin === 'profile-gallery' ) {
    $profile_ID = sanitize_key( $data->object_ID );
    $object_ID  = $wpdb->get_var( "SELECT post_author FROM wp_posts WHERE ID =$profile_ID" ); }
elseif ( $origin === 'blog' ) {
    $object_ID  = $wpdb->get_var( "SELECT term_id FROM wp_terms WHERE slug ='blog'" );
} else {
    $object_ID  = sanitize_key( $data->object_ID ); }

//Check for matches
if ( $origin === 'profile-gallery' ) {
    $get_matches = $wpdb->get_results( "SELECT ID FROM wp_posts
                                        WHERE post_author=$object_ID
                                        AND post_type='blender'
                                        AND post_status='publish'
                                        LIMIT 3000" ); }
if ( $origin === 'taxonomy' || $origin === 'discover' ) {
    $get_matches = $wpdb->get_results( "SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id = $object_ID LIMIT 3000" );
    $tax_name    = $wpdb->get_var( "SELECT name, term_id FROM wp_terms WHERE term_id = $object_ID" ); }
if ( $origin === 'blog' ) {
    $get_matches = $wpdb->get_results( "SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id = $object_ID LIMIT 300" ); }

if ( $get_matches === null ) {
    $error = 'unable to complete request';
    echo json_encode( $error );
    exit; }

//Get posts cache
$posts_cache= check_set_posts_cache();
if ( empty( $posts_cache ) ) {
    $error = 'unable to check posts';
    echo json_encode( $error );
    exit; }

//Gets post matches from posts cache
$object_matches = array();
foreach ( $get_matches as $match ) {
    if ( $origin === 'profile-gallery' && $posts_cache[$match->ID] !== null ) {
        array_push( $object_matches, $posts_cache[$match->ID]); }
    if ( ( $origin === 'taxonomy' || $origin === 'discover' || $origin === 'blog' ) && $posts_cache[$match->object_id] !== null ) {
        array_push( $object_matches, $posts_cache[$match->object_id]); } }

//Compiles previews
$compiled_matches = array();
require get_template_directory() . '/scripts/class-custom-classes.php' ;
foreach ( $object_matches as $object_match ) {
    $compiled = new ResponseObject();

    // If 3d object for display on discover.php
    if ( $origin === 'discover' && $output_type === 'blender' && $object_match->post_type === 'blender' ) {
        if ( $object_match->post_status === 'publish' ) {
            $compiled->link        = get_home_url() . '/blender/' . $object_match->post_name;
            $compiled->type        = '3d object';
            $compiled->title       = $object_match->post_title;
            $compiled->content     = $object_match->post_content;
            $compiled->queried_tax = $tax_name;
            $compiled->author      = get_the_author_meta( 'display_name', $object_match->post_author );
            $compiled->thumbnail   = get_the_post_thumbnail_url( $object_match->ID );
            $compiled->status      = 'publish'; }
        if ( $object_match->post_status === 'draft' ) {
            $compiled->status      = 'draft';
            $compiled->queried_tax = $tax_name; } }

    // If profile for display on discover.php
    if ( $origin === 'discover' && $output_type === 'profile' && $object_match->post_type === 'profile' ) {
        $compiled->link         = get_home_url() . '/profile/' . $object_match->post_name;
        $compiled->type         = 'artist';
        $compiled->queried_tax  = $tax_name;
        $compiled->title        = $object_match->post_title;
        $compiled->content      = $object_match->post_content;
        $compiled->object_count = count_user_posts( $object_match->post_author, 'blender', true );
        $compiled->thumbnail    = get_user_meta( $object_match->post_author, 'portrait_link', true ); }

    //If blender object for display on profile
    if ( $origin === 'profile-gallery' && $object_match->post_type === 'blender' ) {
        $compiled->link       = get_home_url() . '/blender/' . $object_match->post_name;
        $compiled->title      = $object_match->post_title;
        $compiled->thumbnail  = get_the_post_thumbnail_url( $object_match->ID ); }

    //If search result for display on taxonomy page.
    if ( $origin === 'taxonomy' ) {
        // If profile object
        if ( $object_match->post_type === 'profile' ) {
            $compiled->link          = get_home_url() . '/profile/' . $object_match->post_name;
            $compiled->type          = 'artist';
            $compiled->queried_tax   = $tax_name;
            $compiled->title         = $object_match->post_title;
            $compiled->content       = $object_match->post_content;
            $compiled->object_count = count_user_posts( $object_match->post_author, 'blender', true );
            $compiled->thumbnail     = get_user_meta( $object_match->post_author, 'portrait_link', true ); }
        // If blender object
        if ( $object_match->post_type === 'blender' && $object_match->post_status === 'publish' ) {
            $compiled->link         = get_home_url() . '/blender/' . $object_match->post_name;
            $compiled->type         = '3d object';
            $compiled->title        = $object_match->post_title;
            $compiled->content      = $object_match->post_content;
            $compiled->queried_tax  = $tax_name;
            $compiled->author       = get_the_author_meta( 'display_name', $object_match->post_author );
            $compiled->thumbnail    = get_the_post_thumbnail_url( $object_match->ID ); }
        // Checks for match with post_type post and category 'blog'
        if ( $object_match->post_type === 'post' && $object_match->post_status === 'publish' ) {
            if ( has_category( 'blog', $object_match->ID ) ) {
                $compiled->link        = get_home_url() . '/' . $object_match->post_name;
                $compiled->type        = 'blog';
                $compiled->title       = $object_match->post_title;
                $compiled->content     = $object_match->post_excerpt;
                $compiled->queried_tax = $tax_name;
                $compiled->author      = get_the_author_meta( 'display_name', $object_match->post_author );
                $compiled->thumbnail   = get_the_post_thumbnail_url( $object_match->ID ); }
            else {
                continue; } }
        // If page
        if ( $object_match->post_type === 'page' && $object_match->post_status === 'publish' ) {
            $compiled->link        = get_home_url() . '/' . $object_match->post_name;
            $compiled->type        = 'page';
            $compiled->title       = $object_match->post_title;
            $compiled->content     = $object_match->post_excerpt;
            $compiled->queried_tax = $tax_name;
            $compiled->author      = get_the_author_meta( 'display_name', $object_match->post_author );
            $compiled->thumbnail   = get_template_directory_uri() . '/img/header-low-poly.png'; }
    }

    // If post for blog page
    if ( $origin === 'blog' ) {
        $compiled->link       = get_home_url() . '/blog/' . $object_match->post_name;
        $compiled->title      = $object_match->post_title;
        $compiled->excerpt    = $object_match->post_excerpt;
        $compiled->thumbnail  = get_the_post_thumbnail_url( $object_match->ID );
    }

    if ( !empty( $compiled ) ) {
        array_push( $compiled_matches, $compiled ); }
}

if ( $origin === 'profile-gallery' ) {
    $success = 'profile_gallery'; }
else {
    $success = $origin; }
$response = array( $success => $compiled_matches );
echo json_encode( $response );
exit;
?>

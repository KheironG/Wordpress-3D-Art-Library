<?php
    if ( !isset($_POST['nonce']) ||
    !wp_verify_nonce($_POST['nonce'], 'admin-customizer-nonce') ) {
        $error['error'] = 'insecure form submission.';
        echo json_encode( $error );
        exit; }

    $string  = substr( $_POST['data'], 1, -1);
    $arrays  = preg_split( "/],/" , $string );

    $options = array();

    foreach ( $arrays as $array ) {
        $remove_characters = str_replace( array( '[', ']', '"','\\' ), '', $array );
        $option = preg_split( "/,/", $remove_characters, 2 );
        array_push( $options, $option ); }

    $header_primary_nav  = array();
    $info_items          = array();
    $admin_connect_links = array();
    $footer_primary_nav  = array();
    $footer_copyright    = array();
    $paginators          = array();
    foreach ( $options as $option_instance ) {
        if ( preg_match( '/menu_item/', $option_instance[0] ) ) {
            $header_primary_nav[$option_instance[0]] = $option_instance[1]; }
        if ( preg_match( '/info_item/', $option_instance[0] ) ) {
            $info_items[$option_instance[0]] = $option_instance[1]; }
        if ( preg_match( '/admin_connect/', $option_instance[0] ) ) {
            $admin_connect_links[$option_instance[0]] = $option_instance[1]; }
        if ( preg_match( '/link_item/', $option_instance[0] ) ) {
            $footer_primary_nav[$option_instance[0]] = $option_instance[1]; }
        if ( preg_match( '/footer_copyright/', $option_instance[0] ) ) {
            $footer_copyright[$option_instance[0]] = $option_instance[1]; }
        if ( preg_match( '/paginator/', $option_instance[0] ) ) {
            $paginators[$option_instance[0]] = $option_instance[1]; }
    }

    $compiled_options = array(
        'header_primary_nav'  => $header_primary_nav,
        'info_items'          => $info_items,
        'admin_connect_links' => $admin_connect_links,
        'footer_primary_nav'  => $footer_primary_nav,
        'footer_copyright'    => $footer_copyright,
        'paginators'          => $paginators
    );

    global $wpdb;
    $table = $wpdb->prefix . 'options';

    $response = array();
    foreach ( $compiled_options as $key => $instance ) {
        $meta_key   = $key;
        $meta_value = $instance;
        $option_ID  = $wpdb->get_var( "SELECT option_id FROM $table WHERE option_name ='$meta_key'" );
        $index = array(
            'option_name'  => $meta_key,
            'option_value' => $meta_value
        );
        if ( $option_ID === null ) {
            add_option( $meta_key, $meta_value ); }
        else {
            update_option( $meta_key, $meta_value ); }
        $response[$key] = $index;
    }
    echo json_encode( 'success' );
    exit;
?>

<?php
    $get_terms_cache = check_set_terms_cache();

    $client_query = $_GET['client-query'];
    $autofill_matches = array();
    foreach ( $get_terms_cache['autofill_cache'] as $autofill_object ) {
        if ( strpos( strtolower( $autofill_object['value'] ), strtolower( $client_query ) ) !== false ) {
            array_push( $autofill_matches, $autofill_object );
        }
    }

    if ( count( $autofill_matches ) > 6 ) {
        $response = array_slice( $autofill_matches, 0, 6 );
    } else {
        $response = $autofill_matches;
    }

    if ( !empty( $response ) ) {
        echo json_encode( array( 'success' => $response ) );
        exit;
    } else {
        echo json_encode( array( 'failed' ) );
        exit;
    }


?>

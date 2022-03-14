<?php get_header();
$query = parse_str( $_SERVER['QUERY_STRING'] );
if ( empty( $tax_name ) && empty( $tax_ID ) ) {
    get_footer();
    return;}
?>
<div class="container-1000 flex-container">
    <h1>taxonomy: </h1>
    <h1><?php echo $tax_name; ?></h1>
</div>
<div class="container-1000">
    <?php
    require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
    echo resultsSection( 'taxonomy' );
    $paginator = get_option('paginators');
    $pag_amount = ( !empty( $paginator['paginator_taxonomy'] ) ) ? ( $paginator['paginator_taxonomy'] ) : ( 15 );
    get_post_objects( $tax_ID, 'taxonomy', 'search', intval( $pag_amount ) );
    ?>
</div>
<?php get_footer(); ?>

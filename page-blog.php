<?php get_header(); ?>
<div class="container-1000">
    <h1><?php echo the_title(); ?></h1>
    <h1><?php echo the_excerpt(); ?></h1>
</div>
<div class="container-1000">
    <?php
    require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
    echo resultsSection( 'blog' );
    $paginator = get_option('paginators');
    $pag_amount = ( !empty( $paginator['paginator_blog'] ) ) ? ( $paginator['paginator_blog'] ) : ( 10 );
    get_post_objects( null, 'blog', 'blog', intval( $pag_amount ) );
    ?>
</div>
<?php get_footer(); ?>

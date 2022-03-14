<?php
get_header();
?>
<div class="container-shadow container-450">
    <div class="polygon">
        <div class="polygon-header-low-poly">
            <div class="container-inner">
                <?php
                esc_html( the_title( '<h1 class="white-text">', '</h1>') );
                esc_html( the_excerpt( '<p>', '</p>') );
                 ?>
            </div>
        </div>
        <div class="container-inner">
            <h5>email us</h5>
            <?php
            require wp_make_link_relative( get_template_directory() . '/forms/form-contact.php' );
             ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>

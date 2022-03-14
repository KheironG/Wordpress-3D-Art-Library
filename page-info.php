<?php
/*
Template Name: Info
*/
get_header();
?>
<div class="container-shadow container-1000">
    <div class="polygon">
        <div class="polygon-header-low-poly">
            <div class="container-inner">
                <?php
                esc_html( the_title( '<h1 class="white-text">', '</h1>') );
                 ?>
            </div>
        </div>
        <div class="container-inner">
            <?php
            esc_html( the_content( '<p>', '</p>') );
             ?>
        </div>
    </div>
</div>
<?php get_footer() ?>

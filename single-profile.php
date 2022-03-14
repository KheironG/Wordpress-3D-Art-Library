<?php get_header( 'private' );
$cover_link        = get_user_meta( $post->post_author, 'cover_link', true );
$portrait_link     = get_user_meta( $post->post_author, 'portrait_link', true );
$profile_details   = get_user_meta( $post->post_author, 'profile_details', true );
$profile_styling   = get_user_meta( $post->post_author, 'profile_styling', true );
$background_colour = ( empty( $profile_styling['background'] ) ) ? ( null ) : ( 'style="background-color:' . $profile_styling['background'] . '"' );
$contrast_colour   = ( empty( $profile_styling['colour'] ) ) ? ( null ) : ( 'style="color:' . $profile_styling['colour'] . '"' );
$profession        = wp_get_object_terms( $post->ID, 'profile_categories' );
$tags              = wp_get_post_tags( $post->ID );
$contact_me        = get_user_meta( $post->post_author, 'contact_me', true );
$profile_social    = get_user_meta( $post->post_author, 'profile_social', true );
if ( !empty( $profile_social ) ) {
    foreach ( $profile_social as $social_media_field ) {
        if ( !empty( $social_media_field ) ) {
            $has_social_media_fields = true;
        }
    }
}
if ( !empty( $profile_details['city'] ) && !empty( $profile_details['country'] ) ) {
    $location = ' ' . $profile_details['city'] . ', ' . $profile_details['country'];
} else {
    $location = $profile_details['city'] . $profile_details['country']; }
?>
<main >
    <!-- PROFILE HEADER STARTS HERE -->
    <div id="single-profile-<?php echo $post->ID ?>" class="container-shadow container-800">
        <div class="polygon">
            <div class="profile-top">
                <div class="profile-cover">
                    <img class="profile-cover-img" src="<?php echo esc_html( $cover_link ) ?>" data-profile-cover="default">
                </div>
                <div class="profile-portrait">
                    <img class="profile-portrait-img" src="<?php echo esc_html( $portrait_link ) ?>" data-profile-portrait="default">
                </div>
            </div>
            <div class="container-inner" style="margin-top:-10px;">
                <h2 class="text-center"><?php echo esc_html( the_title() ); ?></h2>
                <div class="profile-info">
                    <br>
                    <div style="margin-top:-15px;" class="flex-container-center">
                        <?php
                        if ( !empty( $profession ) ) {
                            ?>
                            <p class="no-margin"><b><?php echo esc_html( $profession[0]->name ); ?></b></p>
                            <?php }
                        if ( !empty( $location ) ) {
                            ?>
                            <small class="label">in</small>
                            <p class="no-margin"><b><?php echo esc_html( $location ); ?></b></p>
                            <?php }
                        ?>
                    </div>
                    <br>
                    <?php if ( !empty( $post->post_content ) ) {
                        ?>
                        <div style="margin-top:-15px;" class="text-center">
                            <p><?php echo esc_html( $post->post_content ); ?></p>
                        </div>
                        <?php
                    }
                    if ( $has_social_media_fields === true || $contact_me == 'true' ) {
                        ?>
                        <div class="flex-container-center">
                            <?php
                            if ( $has_social_media_fields === true ) {
                                foreach ($profile_social as $social_media_field => $value ) {
                                    if ( !empty( $value ) ) {
                                        ?>
                                        <a class="no-padding" href="<?php echo esc_attr( $value ) ?>" target="_blank">
                                            <span class="<?php echo $social_media_field . '-icon-inactive' ?>"
                                                onmouseover="iconHover(this)" onmouseout="iconHover(this)">
                                            </span>
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            if ( $contact_me == 1 && strval( $current_user->ID ) !== $post->post_author ) {
                                ?>
                                <button class="button green-gradient" type="button"
                                name="profile-contact-me-button" onclick="toggleProfileContact('<?php echo $post->ID ?>');" >
                                    contact me
                                </button>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTACT FORM STARTS HERE -->
    <div id="single-profile-contact-<?php echo $post->ID ?>" class="container-shadow container-800 hide">
        <div class="polygon">
            <div class="container-inner">
                <div class="text-right">
                    <span class="trigger" onclick="toggleProfileContact('<?php echo $post->ID ?>')">&#10006;</span>
                </div>
                <div class="rectangle container-450">
                    <h4>email <em><?php echo esc_html( the_title() ); ?></em></h4>
                    <?php
                    require wp_make_link_relative( get_template_directory() . '/forms/form-contact.php' );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- PROFILE GALLERY STARTS HERE -->
    <div class="container-800">
        <?php
        require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
        echo resultsSection( 'profile-gallery' );
        $paginator = get_option('paginators');
        $pag_amount = ( !empty( $paginator['paginator_profile'] ) ) ? ( $paginator['paginator_profile'] ) : ( 8 );
        get_post_objects( $post->ID, 'profile-gallery', 'profile-blender', intval( $pag_amount ) );
        ?>
    </div>
    <!-- PROFILE TAGS STARTS HERE -->
    <?php
    if ( !empty($tags) && !is_wp_error( $tags ) ) {
        ?>
        <div>
            <h4 <?php echo $contrast_colour; ?> class="text-center">my tags</h4>
            <br>
            <div class="flex-container-center gap-15">
                <?php
                    foreach ($tags as $tag ) { get_template_part( 'template-parts/part', 'tag-item', $tag ); }
                ?>
            </div>
        </div>
        <?php
    }
     ?>
    </div>
<?php get_footer(); ?>

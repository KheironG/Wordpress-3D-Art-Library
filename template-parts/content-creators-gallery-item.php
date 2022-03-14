<?php
$view = get_home_url() . '/blender/' . $args->post_name;
$edit = get_home_url() . '/blender/' . $args->post_name . '?action=edit';
?>

<div class="blender-item" data-gallery-item-id="<?php echo $args->ID; ?>">

    <img class="blender-item-preview"
    src="<?php echo get_the_post_thumbnail_url( $args->ID, 'blender-preview' ); ?>" alt="">

    <div class="blender-item-overlay">

    <?php
        if ( $args->post_status === 'pending' ) {
            ?>
            <div class="blender-item-overlay-content-rejected">
                <small><?php esc_html_e( $args->post_excerpt ) ?></small>
                <br>
            <?php
        } elseif ( $args->post_status === 'draft' ) {
            ?>
            <div class="blender-item-overlay-content">
                <p><em>render in progress</em></p>
            <?php
        } elseif ( $args->post_status === 'publish' ) {
            ?>
            <div class="blender-item-overlay-content">
                <h5 class="white-text"><?php esc_html_e( $args->post_title ) ?></h5>
            <?php
        }
        ?>

        <div class="flex-container-center">
            <?php
            if ( $args->post_status === 'publish' || $args->post_status === 'draft' ) {
                ?><a class="private-gallery-icon" href="<?php echo $edit ?>">
                    <span class="edit-white-25"></span>
                    <small class="white-text">edit</small>
                </a><?php
            }
            if ( $args->post_status === 'publish') {
                ?><a class="private-gallery-icon" href="<?php echo $view ?>">
                    <span class="view-white-25"></span>
                    <small class="white-text">view</small>
                </a><?php
            }
            ?>
            <div style="padding:8px;" class="private-gallery-icon trigger"
            onclick="confirmDeleteBlenderPost('<?php echo $args->ID; ?>');">
                <span class="delete-white-25"></span>
                <small class="white-text">delete</small>
            </div>
        </div>
    </div>
</div>

    <div class="hide blender-item-delete">
        <div class="blender-item-delete-content">
            <p class="white-text"><b>permanently delete object?</b></p>
            <div class="button-container">
                <button type="button" class="button-accept button-half"
                onclick="deleteBlenderPost('<?php echo $args->ID; ?>');">yes, delete it</button>

                <button type="button" class="button-decline button-half"
                onclick="confirmDeleteBlenderPost('<?php echo $args->ID; ?>');">no, save it</button>
            </div>
        </div>
    </div>

</div>

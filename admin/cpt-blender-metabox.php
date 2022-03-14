<?php
$post_id          = get_the_ID();
$blender_meta     = get_post_meta( $post_id, 'blender_meta', true );
$license          = get_post_meta( $post_id, 'license', true  );
$allow_download   = get_post_meta( $post_id, 'allow_download', true  );
$get_blender_file = get_attached_media( 'application/octet-stream', $post_id );
if ( !empty( $get_blender_file) ) {
    $file_ID      = array_key_first( $get_blender_file );
    $file_name    = $get_blender_file[$file_ID]->post_name . '.blend';
}

$categories         = wp_get_object_terms( $post->ID, 'blender_categories' );

wp_nonce_field( 'blender_metabox_nonce_action', 'blender-metabox-nonce' );
?>

<h3>blender file</h3>
<div>
    <label for="admin-blender-file">story</label>
    <textarea name="admin-blender-story" rows="4" ><?php echo esc_html( $post->post_content ); ?></textarea>
</div>

<br>

<h3>blender file</h3>
<div class="admin-flex-container">
    <small class="label">file</label>
        <?php
        if ( !empty( $get_blender_file ) ) {
            ?>
            <div class="success-container"><?php echo $file_name; ?></div>
            <?php
        } else {
            ?>
            <div id="admin-blender-required" class="error-container">blender file is required</div>
            <?php
        }
        ?>
</div>

<br>
<br>

<h3>object meta</h3>

<div class="admin-flex-container">

    <div>
        <label for="admin-blender-triangles">triangles</label>
        <input type="text" name="admin-blender-triangles" value="<?php esc_html_e( $blender_meta['triangles'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-quads">quads</label>
        <input type="text" name="admin-blender-quads" value="<?php esc_html_e( $blender_meta['quads'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-polygons">polygons</label>
        <input type="text" name="admin-blender-polygons" value="<?php esc_html_e( $blender_meta['polygons'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-vertices">vertices</label>
        <input type="text" name="admin-blender-vertices" value="<?php esc_html_e( $blender_meta['vertices'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-pbr">pbr</label>
        <input type="text" name="admin-blender-pbr" value="<?php esc_html_e( $blender_meta['pbr'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-textures">textures</label>
        <input type="text" name="admin-blender-textures" value="<?php esc_html_e( $blender_meta['textures'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-materials">materials</label>
        <input type="text" name="admin-blender-materials" value="<?php esc_html_e( $blender_meta['materials'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-uv-layers">uv layers</label>
        <input type="text" name="admin-blender-uv-layers" value="<?php esc_html_e( $blender_meta['uv_layers'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-vertex-colours">vertex colours</label>
        <input type="text" name="admin-blender-vertex-colours" value="<?php esc_html_e( $blender_meta['vertex_colours'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-animations">animations</label>
        <input type="text" name="admin-blender-animations" value="<?php esc_html_e( $blender_meta['animations'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-morph-geo">morph geometries</label>
        <input type="text" name="admin-blender-morph-geo" value="<?php esc_html_e( $blender_meta['morph_geo'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-rigged-geo">rigged geometries</label>
        <input type="text" name="admin-blender-rigged-geo" value="<?php esc_html_e( $blender_meta['rigged_geo'] ); ?>">
    </div>

    <div>
        <label for="admin-blender-scales">scales</label>
        <input type="text" name="admin-blender-scales" value="<?php esc_html_e( $blender_meta['scales'] ); ?>">
    </div>

</div>

<br>
<br>

<h3>options</h3>

<div class="admin-flex-container">
    <div>
        <label for="admin-blender-license">license</label>
        <input type="text" name="admin-blender-license" value="<?php esc_html_e( $license ); ?>">
    </div>

    <div>
        <label for="admin-allow-download">allow download</label>
        <select id="admin-allow-download" name="admin-allow-download">
            <option value="no" selected>no</option>
            <option value="yes">yes</option>
        </select>
    </div>
    <?php
    if ( !empty( $allow_download ) ) {
        echo set_select_inputs( 'admin-allow-download', $allow_download );
    }
    ?>
</div>

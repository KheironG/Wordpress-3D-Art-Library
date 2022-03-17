<?php
$blender_meta     = get_post_meta( $post->ID, 'blender_meta', true );
$license          = get_post_meta( $post->ID, 'license', true  );
$allow_download   = get_post_meta( $post->ID, 'allow_download', true  );
$get_blender_file = get_attached_media( 'application/octet-stream', $post->ID );
if ( !empty( $get_blender_file) ) {
    $file_ID      = array_key_first( $get_blender_file );
    $file_name    = $get_blender_file[$file_ID]->post_name . '.blend';
}
$categories       = wp_get_object_terms( $post->ID, 'blender_categories' );
wp_nonce_field( 'blender_metabox_nonce_action', 'blender-metabox-nonce' );
require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php')
?>
<div>
    <label for="admin-blender-story">story</label>
    <textarea name="admin-blender-story" rows="4" ><?php echo esc_html( $post->post_content ); ?></textarea>
</div>
<br>
<br>
<h3>blender file</h3>
<?php
    if ( !empty( $get_blender_file ) ) {
        ?>
        <div class="admin-flex-container">
            <div class="success-container"><?php echo $file_name; ?></div>
        </div>
        <?php }
    else {
        ?>
        <div class="admin-flex-container">
            <div id="admin-blender-required" class="error-container">blender file is required</div>
        </div>
        <br>
        <button type="button" class="button button-primary" name="button" onclick="uploadBlenderFile();">select file</button>
        <small id="admin-upload-blender"></small>
    <?php } ?>
<br>
<br>
<h3>object meta</h3>
<div class="admin-flex-container">
    <?php
    echo adminTextInput( 'admin-blender-triangles', $blender_meta['triangles'], 'triangles' );
    echo adminTextInput( 'admin-blender-quads', $blender_meta['quads'], 'quads' );
    echo adminTextInput( 'admin-blender-polygons', $blender_meta['polygons'], 'polygons' );
    echo adminTextInput( 'admin-blender-vertices', $blender_meta['vertices'], 'vertices' );
    echo adminTextInput( 'admin-blender-pbr', $blender_meta['pbr'], 'pbr' );
    echo adminTextInput( 'admin-blender-textures', $blender_meta['textures'], 'textures' );
    echo adminTextInput( 'admin-blender-materials', $blender_meta['materials'], 'materials' );
    echo adminTextInput( 'admin-blender-uv-layers', $blender_meta['uv_layers'], 'uv layers' );
    echo adminTextInput( 'admin-blender-vertex-colours', $blender_meta['vertex_colours'], 'vertex colours' );
    echo adminTextInput( 'admin-blender-animations', $blender_meta['animations'], 'animations' );
    echo adminTextInput( 'admin-blender-morph-geo', $blender_meta['morph_geo'], 'morph geometries' );
    echo adminTextInput( 'admin-blender-rigged-geo', $blender_meta['rigged_geo'], 'rigged geometries' );
    echo adminTextInput( 'admin-blender-scales', $blender_meta['scales'], 'scales' );
    ?>
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

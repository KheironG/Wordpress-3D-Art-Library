<?php
$add_or_edit_args = array(
    'file-title'            => ( $action !== 'edit' && !is_single() ) ? ( '1. upload files' ) : ( '1. edit feature image' ),
    'file-success'          => ( $action !== 'edit' && !is_single() ) ? ( 'file uploaded' ) : ( 'feature image' ),
    'desc-title'            => $action === 'edit' && is_single() ? '2. edit description' : '2. add description',
    'desc-success'          => $action === 'edit' && is_single() ? 'description updated' : 'description added',
    'meta-title'            => $action === 'edit' && is_single() ? '3. edit meta' : '3. add meta',
    'meta-success'          => $action === 'edit' && is_single() ? 'meta updated' : 'meta added',
    'options-title'         => $action === 'edit' && is_single() ? '4. edit options' : '4. set options',
    'options-success'       => $action === 'edit' && is_single() ? '3D post updated' : '3D post created',
    'post-ID'               => $action === 'edit' && is_single() ? $post->ID : ' ',
    'post-title'            => $action === 'edit' && is_single() ? $post->post_title : ' ',
    'story'                 => $action === 'edit' && is_single() ? $post->post_content : ' ',
    'blender_meta'          => $action === 'edit' && is_single() ? get_post_meta( $post->ID, 'blender_meta', true ) : ' ',
    'license'               => $action === 'edit' && is_single() ? get_post_meta( $post->ID, 'license', true ) : ' ',
    'allow_download'        => $action === 'edit' && is_single() ? get_post_meta( $post->ID, 'allow_download', true ) : ' '
);

$feature_image      = get_the_post_thumbnail_url( $post->ID, array( 600, 450 ) );
$tags               = wp_get_post_terms( $post->ID, 'post_tag' );
$author_profile     = get_user_meta( $current_user->ID, 'profile_ID', true );
$my_tags            = wp_get_post_terms( $author_profile, 'post_tag' );
$blender_categories = get_terms( array( 'taxonomy' => 'blender_categories', 'hide_empty' => false ) );

$categories         = wp_get_object_terms( $post->ID, 'blender_categories' );

$current_parent_category = array();
foreach ( $categories as $category ) {
    if ( $category->parent === 0 ) {
        array_push( $current_parent_category, $category );
    }
}
$current_child_category  = array();
foreach ( $categories as $category ) {
    if ( $category->parent !== 0 ) {
        array_push( $current_child_category, $category );
    }
}

require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
?>

<form method="post" id="add-blender-post">

    <?php
    $header_class = ( $action === 'edit' ) ? ( 'container-shadow' ) : ( "" );
    $header_title = ( $action === 'edit' ) ? ( esc_html( $add_or_edit_args['post-title']) ) : ( 'create 3D post' );
    ?>
    <div class="<?php echo $header_class; ?>">
       <div class="container-600">
           <h1><?php echo $header_title ?></h1>
           <?php if ( $action === 'edit' ) {
               ?> <br> <img id="edit-blender-post-preview" src="<?php echo $feature_image ?>" alt=""> <?php }
           else {  echo the_content( ); } ?>
       </div>
   </div>
   <br>
    <!-- UPLOAD FILE SECTION (DISPLAYS DIFFERENTLY DEPENDING ON ADD OR EDIT MODE )-->
    <div class="container-shadow">
        <div class="rectangle container-600">
            <div class="container-inner" id="add-blender-post-file">
                <?php echo blenderSectionHeader( 'file', $add_or_edit_args['file-title'], $add_or_edit_args['file-success']); ?>
                <div class="hide text-center" id="add-blender-post-file-status" >
                    <div class="loader-elipse"><div></div><div></div><div></div><div></div></div>
                    <h4 class="white-text no-margin">uploading file</h4>
                </div>
                <div id="add-blender-post-file-inputs">
                    <?php
                    if ( $action !== 'edit' && !is_single() ) {
                        echo blenderFileInput ( 'file', 'required', 'blender file *', '.blend format' );
                        echo blenderFileInput ( 'thumb', 'required', 'feature image *', '.png or .jpg - displays at up to 1000*750px' );
                    } else {
                        echo blenderFileInput ( 'thumb', '', 'feature image', '.png or .jpg - displays at up to 1000*750px' );
                    } ?>
                    <br>
                    <div class="text-right">
                        <?php $mode = $action !== 'edit' && !is_single() ? 'add' : 'edit'; ?>
                        <button class="button-next" type="button" onclick="addBlenderFile('<?php echo $mode; ?>');">description </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD DESCRIPTION SECTION -->
    <div class="container-shadow">
        <div class="rectangle container-600">
            <div class="container-inner-reduced-padding" id="add-blender-post-description">
                <?php echo blenderSectionHeader( 'description', $add_or_edit_args['desc-title'], $add_or_edit_args['desc-success']); ?>
                <div class="hide" id="add-blender-post-description-inputs">
                    <?php blenderTextInput( 'title', 'title', $add_or_edit_args['post-title'], true ); ?>
                    <div id="add-blender-story">
                        <textarea class="custom-textarea" name="blender-story" rows="3" maxlength="250"><?php echo esc_html( $add_or_edit_args['story'] ); ?></textarea>
                        <label for="blender-story">story</label>
                    </div>
                    <h5>category</h5>
                    <div id="blender-category">
                        <!-- Displays parent categories -->
                        <select name="blender-parent-category" id="blender-parent-category">
                            <option value="">Select</option>
                            <?php
                            $blender_category_children = array();
                            foreach ($blender_categories as $blender_category ) {
                                if ( $blender_category->parent === 0 ) {
                                    ?>
                                    <option value="<?php echo $blender_category->term_id ?>"
                                        onclick="showChildCategories('blender-child-category-<?php echo $blender_category->term_id ?>')">
                                        <?php echo $blender_category->name ?>
                                    </option>
                                    <?php
                                }
                            } ?>
                        </select>
                        <label for="blender-parent-category">category *</label>
                        <?php echo set_select_inputs( 'blender-parent-category', $current_parent_category[0]->term_id );  ?>
                    </div>
                    <?php
                    $blender_category_children = array();
                    foreach ( $blender_categories as $blender_category ) {
                        if ( $blender_category->parent !== 0 ) {
                            $blender_category_children[$blender_category->parent] = array();
                        }
                    }
                    foreach ( $blender_category_children as $key => $child ) {
                        foreach ( $blender_categories as $blender_category ) {
                            if ( $blender_category->parent === $key ) {
                                array_push( $blender_category_children[$key], $blender_category );
                            }
                        }
                    }
                    // Reserves space for subcategories in DOM
                    if ( !empty( $blender_category_children ) ) {
                        foreach ( $blender_category_children as $key => $child_category ) {
                            ?>
                            <div class="blender-child-categories hide" id="blender-child-category-<?php echo $key ?>">
                                <select name="blender-child-category">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ( $child_category as $child ) {
                                        ?>
                                        <option value="<?php echo $child->term_id ?>">
                                            <?php echo $child->name ?>
                                        </option>
                                        <?php
                                    } ?>
                                </select>
                                <label for="blender-child-category">sub category </label>
                            </div>
                            <?php
                        }
                    }
                    //If in edit mode and post has subcategory
                    foreach ( $categories as $category ) {
                        if ( $category->parent !== 0 ) {
                            ?>
                            <div class="blender-child-categories">
                                <select id="blender-child-category-current" name="blender-child-category-current">
                                    <?php
                                    foreach ( $blender_categories as $blender_category ) {
                                        if ( $blender_category->parent === $category->parent  ) {
                                            ?>
                                            <option value="<?php echo $blender_category->term_id ?>">
                                                <?php echo $blender_category->name ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="blender-child-category-current">subcategory *</label>
                            </div>
                            <?php
                            //Sets the current subcategory value. set_select_inputs defined in functions.php
                            echo set_select_inputs( 'blender-child-category-current', $current_child_category[0]->term_id );
                        }
                    }
                    ?>
                    <h5>tags</h5>
                    <?php
                    if ( !empty( $my_tags ) ) {
                        ?>
                        <div class="my-tags-container">
                            <small class="my-tags-label">your profile tags</small>
                            <div class="my-tags-content flex-container">
                                <?php
                                foreach ( $my_tags as $my_tag ) {
                                    ?>
                                    <small onclick="addPrivateTag(this)" class="trigger tag-item"><?php echo $my_tag->name ?></small>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <small class="text-right no-top-margin">click on tag to add</small>
                        <br>
                        <?php
                    }?>
                     <div id="add-blender-tags">
                         <input type="text" name="blender-post-tags"
                         onkeypress="addTag( event, 'blender' )" maxlength="25">
                         <label for="blender-post-tags">add new tag</label>
                         <small class="text-right">type and hit enter to add.limit is 10</small>
                     </div>
                    <div class="flex-container" id="update-blender-tag-container" style="margin-top:-20px;">
                        <?php if ( !empty( $tags ) ) {
                            foreach ( $tags as $tag ) {
                                $args = array( 'tag' => $tag->slug , 'post_type' => 'blender' );
                                get_template_part( 'template-parts/part', 'add-tag-item', $args );
                            }
                        } ?>
                    </div>
                    <br>
                    <?php echo blenderSubmit( $action, 'description', 'file', 'meta', 'addBlenderDescription()' ); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD META SECTION -->
    <div class="container-shadow">
        <div class="rectangle container-600">
            <div class="container-inner-reduced-padding" id="add-blender-post-meta">
                <?php echo blenderSectionHeader( 'meta', $add_or_edit_args['meta-title'], $add_or_edit_args['meta-success']); ?>
                <div class="hide" id="add-blender-post-meta-inputs">
                    <div id="add-blender-post-meta-text-inputs">
                        <?php $meta_inputs = array( 'triangles', 'quads', 'polygons', 'vertices',
                                                    'pbr', 'textures', 'materials',
                                                    'uv layers', 'vertex colours', 'animations',
                                                    'morph geo', 'rigged geo', 'scales');
                        foreach ( $meta_inputs as $meta_input ) {
                            $name_string = str_replace( " ", "-", $meta_input );
                            $meta_key    = str_replace( " ", "_", $meta_input );
                            echo blenderTextInput( $meta_input, $name_string, $add_or_edit_args['blender_meta'][$meta_key], false );}?>
                    </div>
                    <br>
                    <?php echo blenderSubmit( $action, 'meta', 'description', 'options', 'addBlenderMeta()' ); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- OPTIONS SECTION -->
    <div class="container-shadow">
        <div class="rectangle container-600">
            <div class="container-inner-reduced-padding" id="add-blender-post-options">
                <?php echo blenderSectionHeader( 'options', $add_or_edit_args['options-title'], $add_or_edit_args['options-success']); ?>
                <div class="hide" id="add-blender-post-options-inputs">
                    <?php echo blenderTextInput( 'license', 'license', $add_or_edit_args['license'], false ); ?>
                    <div class="blender-grid">
                        <div id="blender-comments-option">
                            <small class="label">allow comments</small>
                            <div class="flex-container">
                                <?php
                                echo blenderRadio( $action, 'comments', 'yes', 'open', false );
                                echo blenderRadio( $action, 'comments', 'no', 'closed', true );
                                if ( $action === 'edit' && is_single() ) { echo set_radio_inputs( $post->comment_status, 'blender-comments' ); }
                                ?>
                            </div>
                        </div>
                        <div id="blender-download-option">
                            <small class="label">make available for download</small>
                            <div class="flex-container">
                                <?php
                                echo blenderRadio( $action, 'download', 'yes', 'yes', false );
                                echo blenderRadio( $action, 'download', 'no', 'no', true );
                                if ( $action === 'edit' && is_single() ) { echo set_radio_inputs( $add_or_edit_args['allow_download'] , 'blender-download' ); }
                                ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php echo blenderSubmit( $action, 'options', 'meta', 'save', 'addBlenderOptions()' ); ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="add-blender-post-id" id="add-blender-post-id" value="<?php echo $add_or_edit_args['post-ID']; ?>">
</form>

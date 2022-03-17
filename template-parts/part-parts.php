<?php
//POST CREATOR SPECIFIC
function blenderSectionHeader( $section, $title, $success ) {
    ?>
    <h4 id="add-blender-post-<?php echo $section ?>-title">
        <?php echo $title; ?>
    </h4>

    <div class="flex-container hide" id="add-blender-post-<?php echo $section; ?>-success">
        <span class="blender-success"></span>
        <h4 class="white-text"><?php echo $success; ?></h4>
    </div>
    <?php
}

function blenderTextInput ( $label, $name, $value, $required ) {
    $asterix = ( $required === true ) ? ( '*' ) : ( '' );
    ?>
    <div id="add-blender-<?php echo $name; ?>">
        <input type="text" name="blender-<?php echo $name; ?>"
        value="<?php echo esc_html( $value ); ?>">
        <label for="blender-<?php echo $name; ?>"><?php echo $label ?> <?php echo $asterix ?></label>
    </div>
    <?php
}

function blenderRadio ( $action, $field, $option, $value, $checked ) {
    $id = ( $action === 'edit' ) ? ( 'id="blender-' .  $field . '-' . $option . '"' ) : ( "" ) ;
    ?>
    <div class="custom-radio-container">
        <input type="radio" class="custom-radio-<?php echo $option ?>" <?php echo $id; ?>
        name="blender-<?php echo $field; ?>[]" value="<?php echo $value ?>" checked="<?php echo $checked; ?>">
        <span class="custom-radio-checkmark-<?php echo $option ?>"></span>
    </div>
    <?php
}

function blenderFileInput ( $field, $required, $label, $info ) {
    ?>
    <div id="add-blender-<?php echo $field ?>">
        <input type="file" name="add-blender-<?php echo $field ?>" <?php echo $required ?>>
        <label for="add-blender-file"><?php echo $label ?></label>
        <small class="text-right margin-bottom"><?php echo $info ?></small>
    </div>
    <?php
}

function blenderSubmit( $action, $current, $previous, $next, $ajax ) {
    $loader_url = get_template_directory_uri() . '/img/ajax-loader.gif';
    ?>
    <div class="text-right" id="submit-blender-<?php echo $current;  ?>">
        <img class="sumbit-loader hide" src="<?php echo $loader_url ?>">
        <div>
            <?php
            if ( ( $current !== 'file' && $action === 'edit' ) ) {
                ?>
                <button class="button-prev"
                    onclick="postCreatorPrevious( '<?php echo $current;  ?>', '<?php echo $previous;  ?>' );">
                    feature image
                </button>
                <?php
            } elseif ( ( $current !== 'file' && $current !== 'description' ) && ( $action !== 'edit' ) ) {
                ?>
                <button class="button-prev"
                    onclick="postCreatorPrevious( '<?php echo $current;  ?>', '<?php echo $previous;  ?>' );">
                    <?php echo $previous;  ?>
                </button>
                <?php
            } ?>
            <button class="button-next"
            onclick="<?php echo $ajax;  ?>;">
                <?php echo $next;  ?>
            </button>
        </div>
    </div>
    <?php
}


//GENERAL
function socialInput( $instance, $value ) {
    ?>
    <div>
        <input type="url" id="update-profile-<?php echo $instance ?>" name="profile-<?php echo $instance ?>"
        value="<?php echo $value ?>">
        <label for="profile-<?php echo $instance ?>"><?php echo $instance ?> url</label>
    </div>
    <?php
}

function emailInput( $id, $class, $name, $value, $label, $div ) {
    if ( $div === true ) {
        ?>
        <div class="text-right">
            <input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
            <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        </div>
        <?php
    } else {
        ?>
        <input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <?php
    }
}

function passwordInput ( $id, $name, $class, $label, $autocomplete ) {
    ?>
    <div class="text-right">
        <input type="password" id="<?php echo $id ?>" name="<?php echo $name; ?>"
        autocomplete="<?php echo $autocomplete ?>" class="<?php echo $class ?>" >
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
    </div>
    <?php

}


function textInput( $id, $class, $name, $value, $label, $div ) {
    if ( $div === true ) {
        ?>
        <div>
            <input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
            <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        </div>
        <?php
    } else {
        ?>
        <input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <?php
    }
}


function submitButton ( $class, $function, $text, $name ) {
    ?>
    <div class="text-right">
        <button class="<?php echo $class ?>" onclick="<?php echo $function; ?>"
            name="<?php echo $name ?>">
            <?php echo $text; ?>
        </button>
    </div>
    <?php
}

function generateButton ( $class, $type, $name, $id, $event, $label ) {
    ?>
    <button class="<?php echo $class ?>" type="<?php echo $type; ?>"
        name="<?php echo $name; ?>" id="<?php echo $id; ?>" onclick="<?php echo $event; ?>">
        <?php echo $label; ?>
    </button>
    <?php
}


function toggleMenuItem ( $class, $id, $event, $icon, $label ) {
    ?>
    <a class="<?php echo $class; ?> toggle-menu-item" id="<?php echo $id; ?>-menu"
        onclick="<?php echo $event; ?>">
        <span class="<?php echo $icon; ?>"></span>
        <p><?php echo $label; ?></p>
    </a>
    <?php
}


//CUSTOMIZER
function menuItem ( $number, $pages, $object ) {
    ?>
    <div class="admin-info-item">
        <div class="menu-item-icon">
            <label for="menu-item-<?php echo $number; ?>-icon">item <?php echo $number; ?> icon</label>
            <input type="text" name="menu-item-<?php echo $number; ?>-icon"
            value="<?php echo $object['menu_item_' . $number. '_icon'];  ?>">
        </div>
        <div>
            <label for="menu-item-<?php echo $number; ?>-text">item <?php echo $number; ?> text</label>
            <input type="text" name="menu-item-<?php echo $number; ?>-text"
            value="<?php echo $object['menu_item_' . $number . '_text']; ?>">
        </div>
        <div>
            <label for="menu-item-<?php echo $number; ?>-link">item <?php echo $number; ?> link</label>
            <select name="menu-item-<?php echo $number; ?>-link" id="menu-item-<?php echo $number; ?>-link">
                <option value="">Select</option>
                <?php
                foreach ( $pages as  $page ) {
                    ?>
                    <option value="<?php echo $page->guid ?>"><?php echo $page->post_title ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    if ( !empty( $object['menu_item_' . $number . '_link'] )) {
        echo set_select_inputs( 'menu-item-' . $number . '-link', $object['menu_item_' . $number . '_link'] ); }
}


function customizerItem ( $type, $number, $pages, $object ) {
    $name = $type . '-' . $number;
    $key = str_replace( "-", "_", $name );
    ?>
    <div class="admin-info-item">
        <div>
            <label for="<?php echo $name; ?>-title">Title</label>
            <input type="text" name="<?php echo $name; ?>-title"
            value="<?php echo $object[ $key. '_title']; ?>">
        </div>
        <div>
            <label for="<?php echo $name; ?>-icon">Icon</label>
            <input type="text" name="<?php echo $name; ?>-icon"
            value="<?php echo $object[ $key .'_icon']; ?>">
            <div class="text-right">
                <small>FontAwesome CSS class.</small>
            </div>
        </div>
        <div>
            <label for="<?php echo $name; ?>-desc">Description</label>
            <textarea name="<?php echo $name; ?>-desc" rows="5"
                maxlength="250"><?php echo $object[$key.'_desc']; ?></textarea>
        </div>
        <div>
            <label for="<?php echo $name; ?>-link">Link To</label>
            <select name="<?php echo $name; ?>-link" id="<?php echo $name; ?>-link">
                <option value="">Select</option>
                <?php
                foreach ( $pages as  $page ) {
                    ?>
                    <option value="<?php echo $page->guid ?>"><?php echo $page->post_title ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <?php
        if ( !empty( $object[$key.'_link'] )) {
            echo set_select_inputs( $name.'-link', $object[$key.'_link'] );
        }
        ?>
    </div>
    <?php
}

function connectLink( $instance, $object ) {
    ?>
    <div>
        <label for="admin-connect-<?php echo $instance; ?>"><?php echo $instance; ?> url</label>
        <input type="url" name="admin-connect-<?php echo $instance; ?>"
        value="<?php echo $object; ?>">
    </div>
    <?php
}

function adminTextInput( $name, $value, $label ) {
    ?>
    <div>
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <input type="text" name="<?php echo $name; ?>"
        value="<?php echo $value; ?>">
    </div>
    <?php
}

function adminHomePreviewsSelect( $option, $object ) {
    ?>
    <div>
        <label for="<?php echo $option; ?>">show</label>
        <select id="<?php echo $option; ?>" name="<?php echo $option; ?>">
            <option value="" disabled selected>select</option>
            <option value="category">with category</option>
            <option value="latest">latest</option>
            <option value="none">none</option>
        </select>
    </div>
    <?php
    if ( !empty( $object ) ) {
        echo set_select_inputs( $option, $object ); }
    ?>
    <?php
}

//SECTIONS
function resultsSection( $origin ) {
    ?>
    <div id="<?php echo $origin; ?>-results-loader" class="text-center hide">
        <img class="sumbit-loader"
        src="<?php echo get_template_directory_uri() . '/img/ajax-loader.gif';  ?>">
        <small class="blue-text">loading content</small>
    </div>
    <div id="<?php echo $origin; ?>-results-error" class="text-center hide">
        <small class="error">unable to load content.</small>
    </div>
    <div id="<?php echo $origin; ?>-results-content" class="post-results-container"></div>
    <br>
    <div id="<?php echo $origin; ?>-results-paginator" class="flex-container-center margin-top"></div>
    <?php
}

?>

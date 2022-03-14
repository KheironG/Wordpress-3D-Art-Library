<?php get_header();
require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
$get_terms_cache = check_set_terms_cache();
$paginator = get_option('paginators');
$pag_amount = ( !empty( $paginator['paginator_discover'] ) ) ? ( $paginator['paginator_discover'] ) : ( 15 );
?>
<div class="container-shadow container-1000">
    <div class="polygon">
        <div class="polygon-header-low-poly">
            <div class="container-inner">
                <div class="flex-container">
                    <span class="fas fa-cube fa-3x"></span>
                    <h1 class="white-text">library</h1>
                </div>
            </div>
        </div>
        <div class="container-inner">
            <div class="toggle-menu">
                <?php echo toggleMenuItem ( 'discover-menu', 'discover-objects',
                                                "discoverUiHandler('objects');", 'fas fa-cube fa-2x', '3D objects' ); ?>
                <?php echo toggleMenuItem ( 'discover-menu', 'discover-artists',
                                                "discoverUiHandler('artists');", 'fas fa-user fa-2x', 'artists' ); ?>
                <?php echo toggleMenuItem ( 'discover-menu', 'discover-search',
                                                "discoverUiHandler('search');", 'fas fa-search fa-2x', 'search' ); ?>
            </div>
            <hr>
            <!-- BROWSE 3D OBJECTS BY TAXONOMY 'blender_categories' STARTS HERE -->
            <div class="discover-option hide" id="discover-objects-option">
                <h5 class="blue-text">3D objects by category</h5>
                <div class="flex-container">
                    <?php
                    foreach ( $get_terms_cache['terms_cache']['blender_cat'] as $blender_cat ) {
                        if ( $blender_cat->count != 0 ) {
                            $has_objects = 'active';
                        } else {
                            $has_objects = 'inactive';
                        }
                        $term_children = get_term_children( intval( $blender_cat->term_id ), 'blender_categories' );
                        //Check if has active category children
                        $child_status = array();
                        foreach ($term_children as $term_child ) {
                            foreach ( $get_terms_cache['terms_cache']['blender_cat'] as $cached_child ) {
                                if ( $cached_child->term_id == $term_child && $cached_child->count != 0 ) {
                                    $child_status[$cached_child->parent] = 'true';
                                } else if ( $cached_child->term_id == $term_child && $cached_child->count == 0 ) {
                                    $child_status[$cached_child->parent] = 'false';
                                }
                            }
                        }
                        if ( $blender_cat->parent == 0 ) {
                            ?>
                            <div class="discover-parent-<?php echo $has_objects ?>"
                                <?php
                                if ( $has_objects === 'active' && !empty( $term_children ) && $child_status[$blender_cat->term_id] == 'true' ) {
                                    ?>
                                    onclick="discoverUiHandler( null, '<?php echo $blender_cat->term_id ?>', '<?php echo $blender_cat->name ?>',
                                                                'blender', '<?php echo $pag_amount; ?>', this, true, false )"
                                    <?php
                                } elseif ( $has_objects === 'active' && empty( $term_children ) ) {
                                    ?>
                                    onclick="discoverUiHandler( null, '<?php echo $blender_cat->term_id ?>', '<?php echo $blender_cat->name ?>',
                                                                'blender', '<?php echo $pag_amount; ?>', this, false, false );"
                                    <?php
                                } elseif ( $has_objects === 'active' && !empty( $term_children ) && $child_status[$blender_cat->term_id] == 'false' ) {
                                    ?>
                                    onclick="discoverUiHandler( null, '<?php echo $blender_cat->term_id ?>', null,
                                                                'blender', '<?php echo $pag_amount; ?>', this, false, false );"
                                    <?php
                                }
                                ?>>
                                <?php echo $blender_cat->name; ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <br>
                <div id="browse-by-category-children" class="flex-container hide discover-section">
                    <?php
                    foreach ( $get_terms_cache['terms_cache']['blender_cat'] as $blender_cat_child ) {
                        if ( $blender_cat_child->parent != 0 && $blender_cat_child->count != 0 ) {
                            ?>
                            <div class="discover-child-active category-<?php echo $blender_cat_child->parent ?> hide"
                                onclick="discoverUiHandler( null, '<?php echo $blender_cat_child->term_id ?>', null,
                                                            'blender', '<?php echo $pag_amount; ?>', this, false, true );">
                                <?php echo $blender_cat_child->name; ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- BROWSE ARTISTS BY TAXONOMIES 'profile_cat' and 'profile_init' STARTS HERE -->
            <div class="discover-option hide" id="discover-artists-option">
                <h5 class="blue-text">artists by</h5>
                <div class="flex-container">
                    <div class="discover-parent-active" onclick="discoverUiHandler( 'artists-by-category-section', null, null, null, null, this, false, false );">
                        categories
                    </div>
                    <div class="discover-parent-active" onclick="discoverUiHandler( 'artists-by-name-section', null, null, null, null, this, false, false );">
                        names
                    </div>
                </div>
                <br>
                <div class="discover-section hide" id="artists-by-category-section">
                    <div class="flex-container">
                        <?php
                        foreach ( $get_terms_cache['terms_cache']['profile_cat'] as $profile_cat ) {
                            if ( $profile_cat->count != 0 ) {
                                $profile_cat_status = 'active';
                            } else {
                                $profile_cat_status = 'inactive';
                            }
                            if ( $profile_cat->parent == 0 ) {
                                ?>
                                <div class="discover-child-<?php echo $profile_cat_status ?>"
                                    <?php
                                    if ( $profile_cat_status === 'active' ) {
                                        ?>
                                        onclick="discoverUiHandler( null, '<?php echo $profile_cat->term_id ?>', null,
                                                                    'profile', '<?php echo $pag_amount; ?>', this, false, true );"
                                        <?php
                                    }
                                    ?>>
                                <?php
                                if ( $profile_cat->count == 0 ) {
                                    echo $profile_cat->name;
                                } else if ( $profile_cat->count != 0 ) {
                                    echo $profile_cat->name . '  ( ' . $profile_cat->count . ' )';
                                }
                                ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="discover-section hide" id="artists-by-name-section">
                    <h5 class="blue-text no-top-margin">initials</h5>
                    <?php
                    $alphabet = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
                                    'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' ];
                    $inactive_initials = array();
                    foreach ( $alphabet as $letter ) {
                        $inactive_initials[$letter] = array( 'state' => 'inactive' );
                    }
                    $active_initials = array();
                    foreach ( $alphabet as $letter ) {
                        foreach ( $get_terms_cache['terms_cache']['profile_init'] as $profile_initial ) {
                            if ( $profile_initial->slug === $letter ) {
                                $active_initials[$letter] = array( 'state' => 'active', 'tax_ID' => $profile_initial->term_id );
                            }
                        }
                    }
                    $initials = array_merge( $inactive_initials, $active_initials );
                    ?>
                    <div class="flex-container">
                        <?php
                        foreach ( $initials as $initial => $state ) {
                            ?>
                            <div class="initial-<?php echo $state['state'] ?> discover-child-active"
                                <?php
                                if ( $state['state'] === 'active' ) {
                                    ?>
                                    onclick="discoverUiHandler( null, '<?php echo $state['tax_ID'] ?>', null,
                                                                'profile', '<?php echo $pag_amount; ?>', this, false, true )"
                                    <?php
                                }  else {
                                    null;
                                }
                                ?>>
                                <?php echo $initial  ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- SEARCH OPTION STARTS HERE -->
            <div class="discover-option hide" id="discover-search-option">
                <h5 class="blue-text">search</h5>
                <div class="search-container">
                    <form id="page-search-form" autocomplete="off">
                        <input id="page-search-input" type="text" name="page-search-input"
                        autocomplete="off" onkeyup="searchAutofill('page');" >
                        <label class="search-trigger" onclick="activateSearch( 'keyword', null, null, null, 'page' );" for="page-search-input"><span class="header-search"></span></label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-1000">
<?php
echo resultsSection( 'discover' );
?>
</div>
<?php
// Activates search
$url_query_string = parse_str( $_SERVER['QUERY_STRING'] );
if ( $search === 'true' ) {
    echo activate_search( $search_type, $tax_name, $tax_ID, $query );
}
get_footer(); ?>

<?php
require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
?>
<div class="admin-main-container">
    <h1>Theme Customizer</h1>
    <form id="admin-customizer-form" method="post">
        <?php $linkable_pages = get_pages( array( 'post_status' => array( 'publish' ) ) ); ?>
        <div class="fieldset">
            <?php $header_primary_nav = get_option( 'header_primary_nav' ) ?>
            <div>
                <h4 class="no-margin">header primary navigation</h4>
                <p>the public main menu, icon+text or text only style, located in header</p>
                <br>
                <h5>menu style</h5>
                <div class="admin-flex-container gap-20">
                    <input class="menu-item-style" type="radio" name="menu-item-style" value="icon"
                        onclick="toggleAdminInputs( 'menu-item-icon', 'show' )">
                    <label for="menu-item-type-icon">icon+text</label>
                    <input class="menu-item-style" type="radio" name="menu-item-style" value="text"
                    onclick="toggleAdminInputs( 'menu-item-icon', 'hide' )">
                    <label for="menu-item-type-text">text only</label>
                </div>
            </div>
            <br>
            <h5>menu items</h5>
            <div class="admin-flex-container">
                <?php
                echo menuItem ( 'one', $linkable_pages, $header_primary_nav);
                echo menuItem ( 'two', $linkable_pages, $header_primary_nav);
                echo menuItem ( 'three', $linkable_pages, $header_primary_nav);
                echo menuItem ( 'four', $linkable_pages, $header_primary_nav);
                echo menuItem ( 'five', $linkable_pages, $header_primary_nav);
                echo menuItem ( 'six', $linkable_pages, $header_primary_nav);
                echo menuItem ( 'seven', $linkable_pages, $header_primary_nav);
                ?>
            </div>
            <?php echo set_radio_inputs( $header_primary_nav['menu_item_style'], 'menu-item-style' ); ?>
         </div>
        <h4>Home Page Options</h4>
        <div class="fieldset">
            <div>
                <h5 class="no-margin">info items</h5>
                <p>box style items that display on the front page,
                    for communication information and/or linking users to specific pages.
                </p>
            </div>
            <br>
            <?php
            $info_items = get_option( 'info_items' );
            echo adminTextInput( 'info-item-section-title',
                                                $info_items['info_item_section_title'], 'Section Title' );
            ?>
            <br>
            <br>
            <div class="admin-flex-container">
                <?php
                echo customizerItem ( 'info-item', 'one', $linkable_pages, $info_items );
                echo customizerItem ( 'info-item', 'two', $linkable_pages, $info_items );
                echo customizerItem ( 'info-item', 'three', $linkable_pages, $info_items );
                echo customizerItem ( 'info-item', 'four', $linkable_pages, $info_items );
                ?>
            </div>
        </div>
        <h4>Connect Page Options</h4>
        <div class="fieldset">
            <div>
                <h5 class="no-margin">connect links</h5>
                <p>displays icon style links on connect page.</p>
            </div>
            <?php $admin_connect_links = get_option( 'admin_connect_links' ); ?>
            <br>
            <div class="admin-flex-container">
                <?php
                echo connectLink( 'website', $admin_connect_links['admin_connect_website'] );
                echo connectLink( 'facebook', $admin_connect_links['admin_connect_facebook'] );
                echo connectLink( 'instagram', $admin_connect_links['admin_connect_instagram'] );
                echo connectLink( 'twitter', $admin_connect_links['admin_connect_twitter'] );
                echo connectLink( 'youtube', $admin_connect_links['admin_connect_youtube'] );
                echo connectLink( 'linkedin', $admin_connect_links['admin_connect_linkedin'] );
                ?>
            </div>
        </div>
        <h4>Footer Options</h4>
        <div class="fieldset">
            <div>
                <h5 class="no-margin">footer primary navigation</h5>
                <p>for communicating information about and linking users to specific pages.</p>
            </div>
            <?php $footer_primary_nav = get_option('footer_primary_nav'); ?>
            <br>
            <div class="admin-flex-container">
                <?php
                echo customizerItem ( 'link-item', 'one', $linkable_pages, $footer_primary_nav );
                echo customizerItem ( 'link-item', 'two', $linkable_pages, $footer_primary_nav );
                echo customizerItem ( 'link-item', 'three', $linkable_pages, $footer_primary_nav );
                echo customizerItem ( 'link-item', 'four', $linkable_pages, $footer_primary_nav );
                ?>
            </div>
        </div>
        <?php $footer_copyright = get_option( 'footer_copyright' ); ?>
        <div class="fieldset">
            <div class="admin-flex-container">
                <?php echo adminTextInput( 'footer-copyright',
                                                    $footer_copyright['footer_copyright'], 'copyright notice' ); ?>
            </div>
        </div>
        <h4>Custom settings</h4>
        <div class="fieldset">
            <div>
                <h5 class="no-margin">paginators</h5>
                <p>number of post to display per page (integer value).</p>
            </div>
            <?php
            $paginators= get_option('paginators');
            ?>
            <br>
            <div class="admin-flex-container">
                <?php
                echo adminTextInput( 'paginator-discover', $paginators['paginator_discover'], 'library page' );
                echo adminTextInput( 'paginator-search', $paginators['paginator_search'], 'search results' );
                echo adminTextInput( 'paginator-profile', $paginators['paginator_profile'], 'profile galleries' );
                echo adminTextInput( 'paginator-taxonomy', $paginators['paginator_taxonomy'], 'taxonomy page' );
                echo adminTextInput( 'paginator-blog', $paginators['paginator_blog'], 'blog page' );
                ?>
            </div>
        </div>
        <div class="text-right" id="admin-customizer-response"></div>
        <div class="text-right">
            <button type="button" class="button button-primary" name="save-customizer" onclick="adminCustomizer();">save</button>
        </div>
    </form>
</div>

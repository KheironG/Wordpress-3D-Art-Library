    </div>
</main>
<footer>
    <!-- FOOTER LINK ITEMS SECTION STARTS HERE -->
    <?php
    $footer_nav = get_option( 'footer_primary_nav' );
    $footer_primary_nav = array(
        'one' => array(
            'title'       => $footer_nav['link_item_one_title'],
            'description' => $footer_nav['link_item_one_desc'],
            'icon'        => $footer_nav['link_item_one_icon'],
            'link'        => $footer_nav['link_item_one_link']
        ),
        'two' => array(
            'title'       => $footer_nav['link_item_two_title'],
            'description' => $footer_nav['link_item_two_desc'],
            'icon'        => $footer_nav['link_item_two_icon'],
            'link'        => $footer_nav['link_item_one_link']
        ),
        'three' => array(
            'title'       => $footer_nav['link_item_three_title'],
            'description' => $footer_nav['link_item_three_desc'],
            'icon'        => $footer_nav['link_item_three_icon'],
            'link'        => $footer_nav['link_item_three_link'],
        ),
        'four' => array(
            'title'       => $footer_nav['link_item_four_title'],
            'description' => $footer_nav['link_item_four_desc'],
            'icon'        => $footer_nav['link_item_four_icon'],
            'link'        => $footer_nav['link_item_four_link'],
        )
    );
    $active_items = array();
    foreach ( $footer_primary_nav as $key => $link_item ) {
        $check_link_item = array_filter( $link_item );
        if ( count( $check_link_item ) == 0 ){
            null; }
        else {
            $active_items[$key] = $link_item; } }
    if ( !empty( $active_items ) ) {
        ?><div class="footer-primary"><?php
        foreach ( $active_items as $active_item ) {
            ?>
            <a href="<?php echo $active_item['link']; ?>" class="footer-primary-item">
                <p><span class="<?php echo $active_item['icon']; ?>"> </span><b>  <?php echo $active_item['title']; ?></b></p>
                <small><?php echo $active_item['description']; ?></small>
            </a>
            <?php }
        ?></div><?php }
    ?>
    <br>
    <!-- FOOTER SECONDARY NAVIGATION STARTS HERE -->
    <div class="flex-container-right">
        <?php echo wp_nav_menu( array( "theme_location" => "footer_secondary" ) ); ?>
    </div>
    <br>
    <br>
    <div class="footer-grid">
        <!-- FOOTER CONNECT LINKS START HERE -->
        <div class="flex-container footer-grid-left">
            <?php
            $connect_links = get_option( 'admin_connect_links' );
            foreach ( $connect_links as $connect_link => $value ) {
                if ( !empty( $connect_link ) ) {
                    ?>
                    <a style="padding:0px;" href="<?php echo esc_attr( $value ) ?>" target="_blank">
                        <span class="<?php echo preg_replace( "/admin_connect_/" , ' ', $connect_link ) . '-icon-white-inactive' ?>"
                            onmouseover="iconHover(this)" onmouseout="iconHover(this)">
                        </span>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <!-- FOOTER SEARCH START HERE -->
        <div class="footer-grid-right search-container">
            <form id="footer-search-form" method="post" autocomplete="off">
                    <input id="footer-search-input" type="text" name="footer-search-input"
                    onkeyup="searchAutofill('footer');" autocomplete="off" >
                    <label class="trigger" onclick="activateSearch( 'keyword', null, null, null, 'footer' );" for="footer-search-input"><span class="header-search"></span></label>
            </form>
        </div>
    </div>
    <br>
    <!-- FOOTER ATTRIBUTIONS START HERE -->
    <div class="flex-container-center">
        <small style="">webdev and design <b>Kheiron Gunnarsson</b></small>
        <?php $copyright_notice = get_option( 'footer_copyright' );
        if ( !empty( $copyright_notice ) ) {
            ?>
            <b> | </b>
            <p class="no-margin"><?php echo $copyright_notice['footer_copyright']; ?></p>
            <?php } ?>
    </div>
<?php wp_footer(); ?>
</footer>
</body>
</html>

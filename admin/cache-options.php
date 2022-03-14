<?php
if ( isset( $_POST['clear-cache'] ) ) {
    $clear_terms_cache = delete_transient( 'terms_cache_object' );
    $clear_posts_cache = delete_transient( 'posts_cache_object' );
    if ( $clear_terms_cache === true && $clear_posts_cache === true ) {
        $terms_cache_cleared = 'cache cleared.';
    } else {
        $terms_cache_error = 'failed. please try again.';
    }
}

if ( isset( $_POST['save-caching-options'] ) ) {

    $terms_posts_cache_status = get_option( 'terms_posts_cache_timeout' );
    if ( $terms_posts_cache_status !== false ) {
        $save_terms_posts_cache = update_option( 'terms_posts_cache_timeout', $_POST['terms-posts-cache-timeout'] );
    } else {
        $save_terms_posts_cache = add_option( 'terms_posts_cache_timeout', $_POST['terms-posts-cache-timeout'] );
    }

    if ( $save_terms_posts_cache !== false ) {
        $save_options_success = 'options saved.';
    } else {
        $save_options_error = 'failed .';
    }

}
?>

<div class="admin-main-container">

    <h1>Caching Options</h1>

    <form id="admin-caching-form" method="post">

        <h4>taxonomy and posts option</h4>
        <ul>
            <li>caches (1) taxonomies: blender_categories, profile_categories, profile_initials, post_tags,
                (2) profile post_names, (3) custom post types blender and profile.</li>
            <li>15 min by default.</li>
        </ul>
        <br>

        <div class="fieldset">

            <h5>cache option timeout</h5>

            <div>
                <label for="terms-posts-cache-timeout"></label>
                <select id="terms-posts-cache-timeout" name="terms-posts-cache-timeout">
                    <option value="900">15 minutes</option>
                    <option value="1800">30 minutes</option>
                    <option value="3600">60 minutes</option>
                    <option value="5400">90 minutes</option>
                    <option value="7200">2 hours</option>
                    <option value="10800">3 hours</option>
                    <option value="18000">5 hours</option>
                    <option value="43200">12 hours</option>
                    <option value="86400">1 day</option>
                </select>
            </div>
            <?php
            $terms_posts_cache_timeout = get_option( 'terms_posts_cache_timeout' );
            if ( !empty( $terms_posts_cache_timeout ) ) {
                echo set_select_inputs( 'terms-posts-cache-timeout', $terms_posts_cache_timeout );
            }
            ?>

            <br>
            <br>

            <div class="admin-flex-container">

                <div>
                    <small class="label">active terms cache object</small>
                    <?php
                    $check_terms_cache_object = get_transient( 'terms_cache_object' );

                    if ( !empty( $check_terms_cache_object ) ) {

                        $terms_cache_expires   = (int) get_option( '_transient_timeout_terms_cache_object', 0 );
                        $terms_cache_time_left = ( $terms_cache_expires - time() ) / 60;

                        ?><small>expires in  <b><?php echo round( $terms_cache_time_left ) ?></b> minutes.</small><?php
                    } else {
                        ?>
                        <small>no active cache object.</small>
                        <?php
                    }
                    ?>
                </div>

                <div>
                    <small class="label">active posts cache object</small>
                    <?php
                    $check_posts_cache_object = get_transient( 'posts_cache_object' );

                    if ( !empty( $check_posts_cache_object ) ) {

                        $posts_cache_expires   = (int) get_option( '_transient_timeout_posts_cache_object', 0 );
                        $posts_cache_time_left = ( $posts_cache_expires - time() ) / 60;

                        ?><small>expires in  <b><?php echo round( $posts_cache_time_left ) ?></b> minutes.</small><?php
                    } else {
                        ?>
                        <small>no active cache object.</small>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <br>

            <div>
                <input type="submit" name="clear-cache" class="button-primary button-large" value="clear cache">
                <br>
                <small class="success"><?php echo $cache_cleared; ?></small>
                <small class="error"><?php echo $cache_error; ?></small>
            </div>
        </div>

        <br>

        <div class="text-right">
            <?php
            if ( !empty( $save_options_error ) ) {
                ?>
                <small class="block error"><?php echo $save_options_error ?></small>
                <?php
            }
            if ( !empty( $save_options_success ) ) {
                ?>
                <small class="block success"><?php echo $save_options_success ?></small>
                <?php
            }
            ?>
            <input class="button-primary button-large" type="submit" name="save-caching-options" value="save">
        </div>

    </form>

</div>

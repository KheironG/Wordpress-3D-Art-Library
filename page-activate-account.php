<?php get_header();
if ( !is_user_logged_in() || $current_user->ID == 0 ) {
    require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
    ?>
    <div class="container-shadow">
        <div class="polygon container-450">
            <div class="container-inner">
                <div class="flex-container-center">
                    <img class="logo-cube" src="<?php echo get_template_directory_uri() . '/img/3D-cube.png'; ?>" alt="">
                    <h3 class="blue-text"><?php echo esc_html( the_title() ); ?></h3>
                </div>
                <br>
                <div class="hide success-container" id="activate-account-success">
                    <small class="white-text">
                        activation successful. signing in...
                    </small>
                </div>
                <div id="resend-key-success" class="success-container hide">
                    <small class="block white-label">
                        email sent.
                    </small>
                    <br>
                    <small class="block white-label">
                        We sent you an email with information on how to activate your account.
                    </small>
                </div>
                <form method="post" id="activate-account-form" autocomplete="off">
                    <div id="activate-account-errors"></div>
                    <div>
                        <?php echo emailInput( 'activate-account-email', null, null, null, 'email *', false ) ?>
                        <div class="text-right">
                            <small id="go-back-to-activate-option" class="trigger hide"
                            onclick="activateAccountOptions('activate');">
                                &#8592; go back to activate account</small>
                        </div>
                    </div>
                    <div id="activate-account-password">
                        <input type="password" name="activate-account-password" autocomplete="off" >
                        <label for="activate-account-password">password *</label>
                    </div>
                    <div id="activate-account-key">
                        <?php textInput( 'activate-account-key', null, null, null, 'activation key *', false ); ?>
                        <div class="text-right">
                            <small id="resend-activation-key-option" class="trigger"
                            onclick="activateAccountOptions('resend');">need a new key? click here.</small>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="button" id="activate-account-submit"
                        onclick="activateAccount();">activate account</button>
                        <button type="button" class="button hide" id="activate-account-resend-submit"
                        onclick="resendActivationKey();">resend key</button>
                    </div>
                </form>
                <br>
            </div>
        </div>
    </div>
    <?php
} else {
    wp_redirect( get_home_url() . '/creators-space' );
    exit;
}
get_footer(); ?>

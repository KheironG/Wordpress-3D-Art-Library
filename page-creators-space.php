<?php get_header();

if ( !is_user_logged_in() || $current_user->ID == 0 ) {
    require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
    ?>
    <div class="container-shadow container-450">
    <div class="polygon">
        <div class="container-inner">
            <div class="flex-container-center">
                <img class="logo-cube" src="<?php echo get_template_directory_uri() . '/img/3D-cube.png'; ?>">
                <h3>artist's space</h3>
            </div>
            <div class="text-center">
                <p><?php echo esc_html( the_excerpt() ); ?></p>
            </div>
            <br>
            <div class="toggle-menu-center">
                <?php
                echo toggleMenuItem ( 'creators-space-menu', 'sign-in',
                                              "toggleOption('sign-in', 'creators-space');",
                                              'fas fa-sign-in-alt fa-2x', 'sign in' );
                echo toggleMenuItem ( 'creators-space-menu', 'sign-up',
                                              "toggleOption('sign-up', 'creators-space');",
                                              'fas fa-user-plus fa-2x', 'sign up' );
                echo toggleMenuItem ( 'creators-space-menu', 'reset-password',
                                              "toggleOption('reset-password', 'creators-space');",
                                              'fas fa-unlock-alt fa-2x', 'forgot' );
                ?>
            </div>
            <br>
            <!-- SIGN IN OPTION STARTS HERE -->
            <div class="creators-space-option" id="sign-in-option">
                <form method="post" id="sign-in-form">
                    <h4 class="blue-text">sign in</h4>
                    <div id="sign-in-errors"></div>
                    <?php
                    emailInput( 'sign-in-email', null, 'sign-in-email', null, 'email', true );
                    passwordInput ( 'sign-in-pass', 'sign-in-pass', null, 'password', true );
                    submitButton ( 'button', 'signIn();', 'sign in', 'sign-in-submit' );
                    ?>
                </form>
            </div>
            <!-- SIGN UP OPTION STARTS HERE -->
            <div class="creators-space-option hide" id="sign-up-option">
                <h4 class="blue-text">sign up</h4>
                <div id="sign-up-success" class="success-container hide">
                    <small class="block white-label">
                        sign up successful.
                    </small>
                    <br>
                    <small class="block white-label">
                        We sent you an email with information on how to activate your account.
                    </small>
                </div>
                <form id="sign-up-form" method="post" autocomplete="off">
                    <div id="sign-up-errors"></div>
                    <div class="text-right">
                        <input type="text" id="sign-up-username" name="sign-up-username" autocomplete="off">
                        <label for="sign-up-username">artist's name*</label>
                        <small class="input-requirements">4 to 12 characters and begins with a-z</small>
                    </div>
                    <div class="text-right" style="margin-top:-26px;">
                        <input type="email" id="sign-up-email" name="sign-up-email" autocomplete="off">
                        <label for="sign-up-email">email*</label>
                    </div>
                    <div class="text-right">
                        <input type="password" id="sign-up-password" name="sign-up-password" autocomplete="off">
                        <label for="sign-up-password">password*</label>
                        <small class="input-requirements">6 characters or more</small>
                    </div>
                    <div class="text-right" style="margin-top:-26px;">
                        <input type="password" id="sign-up-confirm" name="sign-up-confirm" autocomplete="off">
                        <label for="sign-up-confirm-password">confirm password*</label>
                    </div>
                    <div class="flex-container hide" id="sign-up-toc-container">
                        <input type="checkbox" id="sign-up-toc" name="sign-up-toc"
                        onclick="declineToc()">
                        <small>I accept the Terms and Conditions</small>
                    </div>
                    <div class="text-right">
                        <?php
                        echo generateButton ( 'button', 'button', 'sign-up-continue', 'sign-up-continue', "signUpContinue()", 'continue' );
                        ?>
                        <button class="button hide" id="sign-up-submit"
                            onclick="signUp();">
                            sign up
                        </button>
                    </div>
                </form>
                <br>
            </div>
            <!-- TERMS AND CONDITIONS SECTION STARTS HERE -->
            <div class="hide" id="terms-and-conditions">
                <?php
                    $terms_args = array( 'category_name' => 'terms', 'post_status' => 'publish', 'numberposts' => 1 );
                    $terms_post = get_posts( $terms_args );
                ?>
                <div class="fieldset">
                    <h3 class="legend"><?php echo $terms_post[0]->post_title ?></h3>
                    <p><?php echo $terms_post[0]->post_content ?></p>
                    <div class="button-container">
                        <?php
                        echo generateButton ( 'button-decline button-half', 'button', 'toc-decline', null, "termsAndConditions('decline')", 'decline' );
                        echo generateButton ( 'button-accept button-half', 'button', 'toc-accept', null, "termsAndConditions('accept')", 'accept' );
                        ?>
                    </div>
                </div>
            </div>
            <!-- RESET PASSWORD SECTION STARTS HERE -->
            <div class="creators-space-option hide" id="reset-password-option">
                <h4 class="blue-text">reset password</h4>
                <div id="reset-password-success" class="success-container hide">
                    <small class="block white-label">
                        request succesful.
                    </small>
                    <br>
                    <small class="block white-label">
                        we sent you and email
                        with information on how to access your account.
                    </small>
                </div>
                <form id="reset-password-form" autocomplete="off">
                    <div id="reset-password-errors"></div>
                    <?php
                    emailInput( 'reset-password-email', null, 'reset-password-email', null, 'registered email*', true );
                    submitButton ( 'button', 'resetPassword();', 'reset', 'reset-password-submit' );
                    ?>
                </form>
                <br>
            </div>
        </div>
    </div>
</div>
    <?php
} else {
    wp_redirect( get_home_url() . '/creators-settings' );
    exit;
}
get_footer(); ?>

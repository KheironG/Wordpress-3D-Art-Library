<?php get_header( 'private' );
if ( $current_user->ID !== 0 || is_user_logged_in() ) {
    require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php' );
    ?>
    <main class="private-background">
        <div class="container-shadow container-800">
            <div class="polygon">
                <div id="profile-settings-media-container">
                    <div id="profile-settings-cover-container" class="profile-top">
                        <div class="profile-cover">
                            <img id="profile-settings-cover" src="<?php echo esc_attr( $current_user->cover_link ) ?>"
                                data-profile-settings-cover="<?php echo esc_attr( $current_user->cover_ID ) ?>">
                            <div class="hide" id="profile-settings-cover-loader">
                                <div class="loader-elipse"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <?php get_template_part('forms/form', 'upload-profile-media', 'profile-settings-cover' );  ?>
                        </div>
                    </div>
                    <div id="profile-settings-portrait-container">
                        <img id="profile-settings-portrait" src="<?php echo esc_attr( $current_user->portrait_link ) ?>"
                            data-profile-settings-portrait="<?php echo esc_attr( $current_user->portrait_ID ) ?>">
                        <div class="hide" id="profile-settings-portrait-loader">
                            <div class="loader-elipse"><div></div><div></div><div></div><div></div></div>
                        </div>
                        <?php get_template_part('forms/form', 'upload-profile-media', 'profile-settings-portrait' );  ?>
                    </div>
                </div>
                <div class="container-inner">
                    <div class="text-center">
                        <h2>dashboard</h2>
                        <div class="flex-container-center gap-45">
                            <div class="text-center">
                                <small class="label">artist's name</small>
                                <p><b><?php echo $current_user->display_name; ?></b></p>
                            </div>
                            <div class="text-center">
                                <small class="label">member since</small>
                                <p><b>
                                    <?php
                                    $date             = date_parse( $current_user->user_registered );
                                    $parsed_date_args = $date['year'] . '-' . $date['month'] . '-' . $date['day'];
                                    $parsed_date      = date_create("$parsed_date_args");
                                    echo date_format( $parsed_date, 'F j, Y' );
                                    ?>
                                </b></p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- TOGGLE MENU STARTS HERE -->
                    <div class="toggle-menu-center">
                        <?php echo toggleMenuItem ( 'creators-settings-menu', 'update-profile-details',
                                                            "toggleOption('update-profile-details', 'creators-settings');",
                                                            'fas fa-user fa-2x', 'profile' ); ?>
                        <?php echo toggleMenuItem ( 'creators-settings-menu', 'update-profile-connect',
                                                            "toggleOption('update-profile-connect', 'creators-settings');",
                                                            'fas fa-user-friends fa-2x', 'connect' ); ?>
                        <?php echo toggleMenuItem ( 'creators-settings-menu', 'update-user-details',
                                                            "toggleOption('update-user-details', 'creators-settings');",
                                                             'fas fa-user-cog fa-2x', 'user' ); ?>
                    </div>
                    <hr class="container-500">
                    <div class="rectangle container-500">
                        <!-- PROFILE DETAILS SECTION STARTS HERE -->
                        <form class="creators-settings-option" id="update-profile-details-option" method="post">
                            <?php
                            $my_profession     = wp_get_object_terms( $current_user->profile_ID, 'profile_categories' );
                            $main_category     = wp_get_object_terms( $current_user->profile_ID, 'blender_categories' );
                            $bio               = get_post_field( 'post_content', $current_user->profile_ID );
                            $tags              = wp_get_post_tags( $current_user->profile_ID );
                            $professions       = get_terms( array( 'taxonomy' => 'profile_categories', 'hide_empty' => false ) );
                            $categories        = get_terms( array( 'taxonomy' => 'blender_categories', 'hide_empty' => false ) );
                            $parent_categories = array();
                            foreach ( $categories as $category ) {
                                if ( $category->parent === 0 ) {
                                    array_push( $parent_categories, $category ); } }
                            ?>
                            <h3 class="blue-text no-margin">profile options</h3>
                            <br>
                            <h4>details</h4>
                            <div>
                                <textarea id="update-profile-bio" class="custom-textarea" name="profile-bio" rows="4"
                                    maxlength="250"><?php echo esc_html( $bio ); ?></textarea>
                                <label for="profile-bio">bio</label>
                            </div>
                                <?php
                                echo textInput( 'update-profile-city', null, 'profile-city', $current_user->profile_details['city'], 'city', true );
                                echo textInput( 'update-profile-country', null, 'profile-country', $current_user->profile_details['country'], 'country', true )
                                ?>
                            <br>
                            <hr>
                            <h4>styling</h4>
                            <div>
                                <?php $background = $current_user->profile_styling['background']; ?>
                                <input id="update-profile-background" type="color" name="profile-background"
                                    value="<?php echo !empty( $background ) ? $background : '#F7F7F7'; ?>">
                                <label for="profile-background">background colour</label>
                            </div>
                            <div>
                                <?php $colour = $current_user->profile_styling['colour']; ?>
                                <input id="update-profile-contrast-colour" type="color" name="profile-contrast-colour"
                                    value="<?php echo !empty( $colour ) ? $colour : '#333333'; ?>">
                                <label for="profile-contrast-colour">contrast colour</label>
                            </div>
                            <div class="text-right">
                                <?php echo generateButton ( 'button', 'button', null, null, 'resetProfileStyleInputs();', 'reset colours' ); ?>
                            </div>
                            <br>
                            <hr>
                            <div>
                                <h4 class="no-margin">description</h4>
                                <small>
                                    filling out the details in this section makes your profile easier to find.
                                </small>
                            </div>
                            <div>
                                <select name="profile-profession" id="update-profile-profession">
                                    <option value="">Select</option>
                                    <?php foreach ($professions as $profession ) {
                                        ?>
                                        <option value="<?php echo $profession->name ?>">
                                            <?php echo $profession->name ?>
                                        </option>
                                        <?php
                                    } ?>
                                </select>
                                <label for="profile-profession">I am a</label>
                                <?php echo set_select_inputs( 'update-profile-profession', $my_profession[0]->name ); ?>
                            </div>
                            <div>
                                <select name="profile-main-category" id="update-profile-main-category">
                                    <option value="">Select</option>
                                    <?php foreach ( $parent_categories as $parent_category ) {
                                        ?>
                                        <option value="<?php echo $parent_category->name ?>">
                                            <?php echo $parent_category->name ?>
                                        </option>
                                        <?php
                                    } ?>
                                </select>
                                <label for="profile-main-category">main 3D category</label>
                                <?php echo set_select_inputs( 'update-profile-main-category', $main_category[0]->name ); ?>
                            </div>
                            <div id="update-profile-tags">
                                <input type="text" name="update-profile-tags" onkeypress="addTag( event, 'profile' )" maxlength="25">
                                <label for="update-profile-tags">profile tags</label>
                                <small class="text-right">
                                    type and hit enter to add. Tag limit is 10.
                                </small>
                            </div>
                            <div class="flex-container" id="update-profile-tag-container" style="margin-top:-20px;">
                               <?php if ( !empty( $tags ) ) {
                                   foreach ( $tags as $tag ) {
                                       $args = array( 'tag' => $tag->slug , 'post_type' => 'profile' );
                                       get_template_part( 'template-parts/part', 'add-tag-item', $args ); } }
                                ?>
                            </div>
                            <br>
                            <div class="text-right" id="update-profile-details-errors"></div>
                            <?php echo submitButton ( 'button', 'updateProfileDetails();', 'save', 'update-profile-details-submit' ) ?>
                        </form>
                        <!-- PROFILE SOCIAL SECTION STARTS HERE -->
                        <form class="creators-settings-option hide" id="update-profile-connect-option" method="post">
                            <div>
                                <h3 class="blue-text no-margin">
                                    connect options
                                </h3>
                            </div>
                            <br>
                            <div>
                                <h4 class="no-margin">
                                    contact me
                                </h4>
                                <small class="block" style="margin-bottom:10px;">
                                    allows visitors and other users to contact you through your public profile.
                                </small>
                                <div>
                                    <small class="label">set status</small>
                                    <div>
                                        <label for="update-profile-contact-me" class="toggle-switch">
                                            <input class="toggle-switch-input" type="checkbox" id="update-profile-contact-me"
                                                name="update-profile-contact-me"
                                                onchange="updateProfileContact('update-profile-contact');">
                                            <span class="slider-toggle"></span>
                                        </label>
                                    </div>
                                </div>
                                <?php echo toggleSwitch( 'update-profile-contact-me', $current_user->contact_me ); ?>
                            </div>
                            <div class="text-right" id="update-profile-contact-errors"></div>
                            <br>
                            <hr>
                            <div>
                                <h4 class="no-margin">links</h4>
                                <small class="block">
                                    provide full url's.
                                </small>
                            </div>
                            <?php
                            echo socialInput( 'website', $current_user->profile_social['website'] );
                            echo socialInput( 'facebook', $current_user->profile_social['facebook'] );
                            echo socialInput( 'instagram', $current_user->profile_social['instagram'] );
                            echo socialInput( 'twitter', $current_user->profile_social['twitter'] );
                            echo socialInput( 'youtube', $current_user->profile_social['youtube'] );
                            echo socialInput( 'linkedin', $current_user->profile_social['linkedin'] );
                            ?>
                            <br>
                            <div class="text-right" id="update-profile-social-errors"></div>
                            <?php echo submitButton ( 'button', 'updateProfileSocial();', 'save', 'update-profile-social-submit' ) ?>
                        </form>
                        <!-- UPDATE USER SECTION STARTS HERE -->
                        <div class="creators-settings-option hide" id="update-user-details-option" method="post">
                            <h3 class="blue-text no-margin">user options</h3>
                            <br>
                            <!-- USER DETAILS SECTION STARTS HERE -->
                            <form>
                                <h4>details</h4>
                                <div id="update-user-details-errors"></div>
                                <?php
                                echo textInput( 'user-first-name', null, 'user-first-name', $current_user->first_name, 'first name', true );
                                echo textInput( 'user-last-name', null, 'user-last-name', $current_user->last_name, 'last name', true );
                                ?>
                                <?php echo submitButton ( 'button', 'updateUserDetails();', 'save', 'update-user-details-submit' ) ?>
                            </form>
                            <br>
                            <hr>
                            <!-- CHANGE EMAIL SECTION STARTS HERE -->
                            <div id="part-change-email">
                                <h4>email</h4>
                                <div id="change-email-success" class="success-container hide">
                                    <small class="block">
                                        request successful.
                                    </small>
                                    <br>
                                    <small class="block">
                                        An email was sent with instructions on how to verify your new email
                                        before it becomes associated with your account.
                                    </small>
                                </div>
                                <div id="verify-email-success" class="success-container hide">
                                    <small class="block">
                                        email changed.
                                    </small>
                                </div>
                                <div id="cancel-change-email-success" class="success-container hide">
                                    <small class="block">
                                        request cancelled.
                                    </small>
                                </div>
                                <!-- CHANGE EMAIL FORM STARTS HERE -->
                                <?php
                                if ( empty( $current_user->new_email ) && empty( $current_user->verify_email_key ) ) {
                                    $change_email_form_state = ' ';
                                    $verify_email_form_state = 'hide'; }
                                else {
                                    $change_email_form_state = 'hide';
                                    $verify_email_form_state = ' '; }
                                ?>
                                <form id="change-email-form" method="post" class="<?php echo $change_email_form_state ?>">
                                    <div id="change-email-errors"></div>
                                    <div class="flex-container" id="change-email-current">
                                        <small class="label">
                                            current
                                        </small>
                                        <p class="no-margin"><?php echo $current_user->user_email; ?></p>
                                    </div>
                                    <h5>change email</h5>
                                    <?php
                                    echo emailInput( 'change-email-new', null, 'change-email-new', null, 'new email', true );
                                    echo emailInput( 'change-email-confirm', null, 'change-email-confirm', null, 'confirm new email', true );
                                    echo submitButton ( 'button', "changeEmail();", 'change email', 'change-email-submit' );
                                    ?>
                                </form>
                                <!-- VERIFY EMAIL STARTS HERE -->
                                <form id="verify-email-form" method="post" class="<?php echo $verify_email_form_state ?>">
                                    <div class="flex-container" id="verify-email-current">
                                        <small class="label">
                                            current
                                        </small>
                                        <p class="no-margin">
                                            <?php echo $current_user->user_email; ?>
                                        </p>
                                    </div>
                                    <div class="flex-container" id="email-pending-verification">
                                        <small class="label">
                                            pending verification
                                        </small>
                                        <p class="no-margin"><em>
                                            <?php echo esc_html( $current_user->new_email ); ?>
                                        </em></p>
                                        <?php echo generateButton ( 'button-decline', 'button', 'cancel-change-email',
                                                                    'cancel-change-email', 'cancelChangeEmail();', 'cancel request' ) ?>
                                    </div>
                                    <div id="resend-change-email-success" class="success-container hide">
                                        <small>
                                            verification key sent
                                        </small>
                                    </div>
                                    <div>
                                        <?php textInput( 'verify-email-key', null, 'verify-email-key', null, 'verification key', false ) ?>
                                        <div class="text-right">
                                            <small id="resend-change-email" class="blue-text trigger" onclick="resendChangeEmail();">
                                                click here to resend verification key
                                            </small>
                                        </div>
                                    </div>
                                    <div id="verify-email-errors"></div>
                                    <?php echo submitButton ( 'button', 'verifyEmail();', 'verify new email', 'verify-email-submit' ) ?>
                                </form>
                            </div>
                            <br>
                            <hr>
                            <!-- CHANGE PASSWORD STARTS HERE -->
                            <form id="change-password-form" method="post" autocomplete="off">
                                <h4>password</h4>
                                <h5>change password</h5>
                                <div id="change-password-success" class="success-container hide">
                                    <small class="block white-text">
                                        password changed.
                                    </small>
                                    <br>
                                    <small class="block white-text">
                                        You may be required to sign in again to continue using this website.
                                    </small>
                                </div>
                                <div id="change-password-errors"></div>
                                <div id="change-password-inputs">
                                    <?php passwordInput ( 'change-password-current', 'change-password-current',
                                                                  'password-field', 'current password', 'off' ) ?>
                                    <?php passwordInput ( 'change-password-new', 'change-password-new',
                                                                'password-field', 'new password', 'off' ) ?>
                                    <?php passwordInput ( 'change-password-confirm', 'change-password-confirm',
                                                                  'password-field', 'confirm new password', 'off' ) ?>
                                    <div class="text-right trigger no-top-margin" onclick="showPasswords('password-field')">
                                        <small class="blue-text">
                                            show passwords
                                        </small>
                                    </div>
                                    <?php echo submitButton ( 'button', 'changePassword();', 'change password', 'change-password-submit' ) ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}  else {
     wp_redirect( get_home_url() . '/creators-space' );
     exit;
 }
get_footer(); ?>

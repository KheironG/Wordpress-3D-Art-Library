<?php
function generateContactInput( $id, $type, $name, $label ) {
    ?>
    <div id="<?php echo $id; ?>">
        <input type="<?php echo $type ?>" name="<?php echo $name ?>">
        <label for="<?php echo $name ?>"><?php echo $label ?></label>
    </div>
    <?php
}
?>
<h5 id="profile-contact-success" class="success-container hide">your email has been sent.</h5>
<small id="connect-contact-success" class="success-container hide">your email has been sent.</small>
<form id="contact-form" method="post">
    <div id="contact-error" class="text-right"></div>
    <?php if ( $current_user->ID === 0 ) {
        echo generateContactInput( 'contact-name', 'text', 'contact-name', 'name' );
        echo generateContactInput( 'contact-email', 'email', 'contact-email', 'email*' );
        echo generateContactInput( 'contact-subject', 'text', 'contact-subject', 'subject' );
        ?>
        <div id="contact-message" class="text-right">
            <textarea class="custom-textarea" name="contact-message" rows="5" maxlength="1000">
            </textarea>
            <label for="contact-message">message*</label>
        </div>
        <?php
    } else if ( $current_user->ID !== 0  ) {
        ?>
        <div>
            <small>sending message as <b class="label"><?php echo $current_user->display_name; ?></b></small>
        </div>
        <?php
        echo generateContactInput( 'contact-subject', 'text', 'contact-subject', 'subject' );
        ?>
        <div id="contact-message">
            <textarea class="custom-textarea" name="contact-message" rows="5" maxlength="1000">
            </textarea>
            <label for="contact-message">message*</label>
        </div>
        <?php
    }
        $profile = $post->post_title;
        $option;
        //Contacting profile as user
        if ( $current_user->ID !== 0 && is_single() ) {
            $option = 'profile-as-user';
        }
        //Contacting profile as public
        if ( $current_user->ID === 0 && is_single() ) {
            $option = 'profile-as-public';
        }
        //Contacting admin as user
        if ( $current_user->ID !== 0 && is_page( 'connect' ) ) {
            $option = 'admin-as-user';
        }
        //Contacting admin as public
        if ( $current_user->ID === 0 && is_page( 'connect' ) ) {
            $option = 'admin-as-public';
        }
        ?>
        <div class="text-right">
            <button class="button" id="contact-submit"
            onclick="contact('<?php echo $option; ?>', '<?php echo $profile; ?>')">send email</button>
        </div>
</form>

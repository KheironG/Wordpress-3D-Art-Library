<?php
$profile_details = get_post_meta( $post->ID, 'profile_details', true );
$profile_social  = get_post_meta( $post->ID, 'profile_social', true );
wp_nonce_field( 'profile_metabox_nonce_action', 'profile-metabox-nonce' );
?>
<div class="admin-flex-container">
    <div>
        <h4>profile details</h4>
        <br>
        <div>
            <label for="admin-profile-city">city</label>
            <input type="text" name="admin-profile-city" value="<?php echo esc_html( $profile_details['city'] ); ?>">
        </div>
        <br>
        <div>
            <label for="admin-profile-country">country</label>
            <input type="text" name="admin-profile-country" value="<?php echo esc_html( $profile_details['country'] ); ?>">
        </div>
    </div>
    <div>
        <h4>social links</h4>
        <br>
        <div>
            <label for="admin-profile-website">website url</label>
            <input type="url" name="admin-profile-website" value="<?php echo $profile_social['website']; ?>">
        </div>
        <br>
        <div>
            <label for="admin-profile-facebook">facebook url</label>
            <input type="url" name="admin-profile-facebook" value="<?php echo $profile_social['facebook']; ?>">
        </div>
        <br>
        <div>
            <label for="admin-profile-instagram">instagram url</label>
            <input type="url" name="admin-profile-instagram" value="<?php echo $profile_social['instagram']; ?>">
        </div>
        <br>
        <div>
            <label for="admin-profile-twitter">twitter url</label>
            <input type="url" name="admin-profile-twitter" value="<?php echo $profile_social['twitter']; ?>">
        </div>
        <br>
        <div>
            <label for="admin-profile-youtube">youtube url</label>
            <input type="url" name="admin-profile-youtube" value="<?php echo $profile_social['youtube'];; ?>">
        </div>
        <br>
        <div>
            <label for="admin-profile-linkedin">linkedin url</label>
            <input type="url" name="admin-profile-linkedin" value="<?php echo $profile_social['linkedin'];; ?>">
        </div>
    </div>
</div>

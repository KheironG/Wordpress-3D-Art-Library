<?php
if ( isset( $_POST['admin-create-profile'] ) ) {
    $get_user = get_user_by( 'login', $_POST['admin-create-profile-name'] );
    $user_has_profile = get_posts( array( 'post_type' => 'profile', 'author' => $get_user->ID ) );
    if ( !empty( $user_has_profile ) ) {
        $error = 'this artist already has a profile.'; }
    else {
        $create_profile_args = array(
            'post_type' => 'profile',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'author' => $get_user->ID,
            'post_title' => $get_user->display_name,
            'post_name' => $get_user->display_name,
        );
        $create_profile = wp_insert_post( $create_profile_args, true, true );
        if ( is_wp_error( $create_profile ) ) {
            $error = 'unable to create profile.'; }
        else {
            $add_profile_ID_to_user = update_user_meta( $get_user->ID, 'profile_ID', $create_profile );
            $add_cover_ID_to_user   = update_user_meta( $get_user->ID, 'cover_ID', 'default' );
            $add_cover_link_to_user = update_user_meta(
                                            $get_user->ID,
                                            'cover_link',
                                            get_template_directory_uri() . '/img/default-cover.png' );
            $add_portrait_ID_to_user = update_user_meta( $get_user->ID, 'portrait_ID', 'default' );
            $add_portrait_link_to_user = update_user_meta(
                                            $get_user->ID,
                                            'portrait_link',
                                            get_template_directory_uri() . '/img/default-portrait.png' );
            $set_category = wp_set_object_terms( $create_profile,
                                                 strtolower( $get_user->display_name[0] ),
                                                 'profile_initials' );
                                                 $user = wp_get_current_user();
             if ( !in_array( 'author', (array) $get_user->roles ) ) {
                 $get_user->add_role( 'author' ); }
            $success = 'profile created.';
            print_r($get_user);
         } } }
require wp_make_link_relative( get_template_directory() . '/template-parts/part-parts.php');
?>
<div class="admin-main-container">
    <div class="admin-flex-container">
        <img class="logo-cube" src="<?php echo get_template_directory_uri() . '/img/3D-cube.png'; ?>">
        <h2>create profile</h2>
    </div>
    <div class="fieldset">
        <p>A profile can be created with a user's artist name ( WordPress display name ). </p>
        <form method="post">
            <div class="admin-flex-container" >
                <?php echo adminTextInput( 'admin-create-profile-name', $value, "provide artist's name" ); ?>
            </div>
            <div class="text-right">
                <small class="block success"><?php echo $success; ?></small>
                <small class="block error"><?php echo $error; ?></small>
                <input type="submit" name="admin-create-profile" value="create profile">
            </div>
        </form>
    </div>
</div>

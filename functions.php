 <?php

/**
 * Adds post content support.
 *
 * @since  1.0
*/
function wpdocs_after_setup_theme() {
    add_theme_support( 'html5', array( 'search-form' ) );
    add_theme_support( 'excerpt' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_post_type_support( 'page', 'excerpt' );

    add_image_size( 'blender-preview', 320, 240, true );
    add_image_size( 'blog-preview', 490, 295, true );
    add_image_size( 'blog-image-main', 1000, 600, true );
    add_image_size( 'profile-cover', 800, 333, array( 'center', 'center' ) );
    add_image_size( 'profile-portrait', 250, 250, array( 'center', 'center' ) );
    add_image_size( 'profile-thumb', 250, 250, array( 'center', 'center' ) );
    add_image_size( 'profile-miniature', 35, 35, array( 'center', 'center' ) );
}
add_action( 'after_setup_theme', 'wpdocs_after_setup_theme' );


/**
 * Adds navigation menu for footer.
 *
 * @since  1.0
*/
function register_menus() {
  register_nav_menus(
    array(
      'footer_secondary' => 'Footer Secondary'
     )
   );
 }
 add_action( 'init', 'register_menus' );


 /**
  * Hide admin bar for non-admins
  *
  * @since  1.0
  */
 function hide_admin_bar( $show ) {
    $current_user = wp_get_current_user();
 	if ( $current_user->roles[0] !== 'administrator' ) {
 		return false;
 	}
 	return $show;
 }
 add_filter( 'show_admin_bar', 'hide_admin_bar' );


 /**
 * Block wp-admin access for non-admins
 */
function block_wp_admin() {
    $current_user = wp_get_current_user();
	if ( is_admin() && $current_user->roles[0] !== 'administrator' && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_safe_redirect( get_home_url() . '/creators-space' );
		exit;
	}
}
add_action( 'admin_init', 'block_wp_admin' );



 /**
  * Enqueues frontend css and JS.
  *
  * @since  1.0
 */
function enqueue_frontend_scripts () {
    //Frontend CSS.
    wp_enqueue_style( "frontend CSS", get_template_directory_uri() . '/css/frontend.css', array(), "1.0", "all" );
    //Frontend JS.
    wp_enqueue_script( "frontend JS", get_template_directory_uri() . '/js/frontend.js', array('jquery'), "1.0", "all" );
}
add_action( 'wp_enqueue_scripts', 'enqueue_frontend_scripts' );


/**
 * Enables custom logo.
 *
 * @since  1.0
*/
function custom_logo() {
    $defaults = array(
        'height'               => 250,
        'width'                => 50,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array( 'site-title', 'site-description' ),
        'unlink-homepage-logo' => true,
    );
}
add_action( 'after_setup_theme', 'custom_logo' );


if ( is_admin() ) {

    /**
     * Enqueues admin css and JS.
     *
     * @since  1.0
    */
    function enqueue_admin_scripts () {
        //Admin CSS
       wp_enqueue_style( "admin CSS", get_template_directory_uri() . '/admin/css/admin.css', array(), "1.0", "all" );
       //Admin JS
       wp_enqueue_script( "admin JS", get_template_directory_uri() . '/admin/js/admin.js', array('jquery'), "1.0", "all" );

    }
    add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );

    /**
     * Adds Customizer to admin dashboard menu.
     *
     * @since  1.0
    */
    function admin_custom_options() {
    		add_menu_page(
    			'Cuztomizer',
    			'Customizer',
                'manage_options',
    			'admin-cuztomizer',
                'customizer_callback',
    			'dashicons-schedule',
    			28
    		);
    	}
    add_action( 'admin_menu', 'admin_custom_options' );

    function customizer_callback ( ) {
        require wp_make_link_relative( get_template_directory() . '/admin/customizer.php' );
    }

    function customizer_ajax() {
        wp_enqueue_script( 'ajax-customizer-script',
                            get_template_directory_uri().'/admin/js/ajax-customizer.js',
                            array('jquery')
                        );
        wp_localize_script( 'ajax-customizer-script',
                            'ajax_customizer',
                            array(
                                'ajax_url' => admin_url( 'admin-ajax.php' ),
                                'nonce'    => wp_create_nonce('admin-customizer-nonce')
                            ));
    }
    add_action( 'admin_enqueue_scripts', 'customizer_ajax' );

    function customizer_ajax_callback() {
        require wp_make_link_relative( get_template_directory() . '/admin/scripts/script-customizer.php' );
    }
    add_action( 'wp_ajax_customizer_action', 'customizer_ajax_callback' );


    /**
     * Adds Cache Options to admin dashboard menu.
     *
     * @since  1.0
    */
    function admin_cache_options() {
    		add_menu_page(
    			'Cache Options',
    			'Cache Options',
                'manage_options',
    			'admin-cache-options',
                'cache_options_callback',
    			'dashicons-schedule',
    			29
    		);
    	}
    add_action( 'admin_menu', 'admin_cache_options' );
    function cache_options_callback ( ) {
        require wp_make_link_relative( get_template_directory() . '/admin/cache-options.php' );
    }

}


/**
 * Sets up CPT profile.
 *
 * @since  1.0
*/
function create_cpt_profile() {
    register_post_type(
        'profile',
        array(
            'labels' => array(
                'name' => 'Profiles',
            ),
            'show_ui'          => true,
            'show_in_menu'     => true,
            'menu_position'    => 27,
            'supports'         => array( 'title', 'editor', 'custom-fields' ),
            'delete_with_user' => true,
            'menu_icon'        => 'dashicons-admin-users',
            'rewrite'          => true,
            'register_meta_box_cb' => 'cpt_profile_metabox',
            'publicly_queryable' => true,
            'public' => true,
            'capabilities' => array( 'create_posts' => false ),
            'map_meta_cap' => true
        )
    );
    register_taxonomy( 'profile_initials', 'profile', array(
                      'hierarchical'      => true,
                      'show_in_rest'      => false,
                      'query_var'         => true,
                      'show_ui'           => false,
                      'show_admin_column' => true,
                      'label'             => 'Artist Initials',
    ));
    register_taxonomy( 'profile_categories', 'profile', array(
                        'label'             => 'Profile Categories',
                        'hierarchical'      => true,
                        'show_ui'           => false,
                        'show_in_rest'      => false,
                        'show_admin_column' => true,
                        'query_var'         => true
    ));
}
add_action( 'init', 'create_cpt_profile' );


/**
 * Adds metabox for CPT profile.
 *
 * @since  1.0
*/
function cpt_profile_metabox() {
    add_meta_box( 'profile-object-meta', 'Custom Meta', 'cpt_profile_metabox_callback', 'profile' );
}
if ( is_admin() ) {
    add_action( 'add_meta_boxes', 'cpt_profile_metabox' );
    function cpt_profile_metabox_callback( $post ) {
        require wp_make_link_relative( get_template_directory() . '/admin/cpt-profile-metabox.php' );
    }
}



if ( is_admin() ) {
    /**
     * The submenu page for creating new profile in admin.
     *
     * @since  1.0
     *
    */
    function register_create_profile_menu () {
        add_submenu_page(
            'edit.php?post_type=profile',
            'Create Profile',
            'Create Profile',
            'manage_options',
            'admin-create-profile',
            'admin_create_profile'
        );
    }

    /**
     * Display callback for the create profile submenu page.
     */
    function admin_create_profile() {
        require wp_make_link_relative( get_template_directory() . '/admin/create-profile.php' );
    }
}
add_action('admin_menu', 'register_create_profile_menu');



/**
 * Sets up CPT blender with taxonomy blender_categories.
 *
 * @since  1.0
*/
function create_cpt_blender() {
    register_post_type(
        'blender',
        array(
            'labels'                => array( 'name' => 'Blender Objects' ),
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 26,
            'description'           => 'Blender Object',
            'supports'              => array( 'title', 'comments', 'thumbnail', 'excerpt' ),
            'menu_icon'             => 'dashicons-format-image',
            'taxonomies'            => array( 'post_tag' ),
            'register_meta_box_cb'  => 'cpt_blender_metabox',
            'rewrite'               => true,
            'publicly_queryable'    => true,
            'public'                => true
        )
    );

}
add_action( 'init', 'create_cpt_blender' );



/**
 * Adds metabox for CPT blender in admin section.
 *
 * @since  1.0
*/
function cpt_blender_metabox() {
    add_meta_box( 'blender-object-meta', 'Custom Fields', 'cpt_blender_metabox_callback', 'blender' ); }
add_action( 'add_meta_boxes', 'cpt_blender_metabox' );



/**
 * The metabox for CPT blender in admin section.
 *
 * @since  1.0
 *
 * @global $post
*/
function cpt_blender_metabox_callback( $post ) {
    require wp_make_link_relative( get_template_directory() . '/admin/cpt-blender-metabox.php' ); }



/**
 * Saves and updates post of CPT Blender in admin. Sets post_name and renames blender file.
 *
 * @since  1.0
 *
 * @global $post_id
*/
function save_cpt_blender( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;  }

	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
			print_r( 'Sorry, you do not have access to edit post.' );
			exit; }

	if ( ! isset( $_POST['blender-metabox-nonce'] )
        || ! wp_verify_nonce( $_POST['blender-metabox-nonce'], 'blender_metabox_nonce_action' ) ) {
		return null; }

    global $wpdb;

    $wpdb->update( 'wp_posts',
                    array( 'post_content' => sanitize_text_field( $_POST['admin-blender-story'] ) ),
                    array( 'ID' => $post_id ) );


    //Adds custom fields
    $blender_meta = array(
        'triangles'      => sanitize_text_field( $_POST['admin-blender-triangles'] ) ,
        'quads'          => sanitize_text_field( $_POST['admin-blender-quads'] ),
        'polygons'       => sanitize_text_field( $_POST['admin-blender-polygons'] ),
        'vertices'       => sanitize_text_field( $_POST['admin-blender-vertices'] ),
        'pbr'            => sanitize_text_field( $_POST['admin-blender-pbr'] ),
        'textures'       => sanitize_text_field( $_POST['admin-blender-textures'] ),
        'materials'      => sanitize_text_field( $_POST['admin-blender-materials'] ),
        'uv_layers'      => sanitize_text_field( $_POST['admin-blender-uv-layers'] ),
        'vertex_colours' => sanitize_text_field( $_POST['admin-blender-vertex-colours'] ),
        'animations'     => sanitize_text_field( $_POST['admin-blender-animations'] ),
        'morph_geo'      => sanitize_text_field( $_POST['admin-blender-morph-geo'] ),
        'rigged_geo'     => sanitize_text_field( $_POST['admin-blender-rigged-geo'] ),
        'scales'         => sanitize_text_field( $_POST['admin-blender-scales'] )
    );
    $add_blender_meta = update_post_meta( $post_id, 'blender_meta', $blender_meta );
    $add_license      = update_post_meta( $post_id, 'license', sanitize_text_field( $_POST['admin-blender-license'] ) );
    $allow_download   = update_post_meta( $post_id, 'allow_download', sanitize_text_field( $_POST['admin-allow-download'] ) );


    //Manages blender file upload
    $post      = get_post( $post_id );
    $author    = get_userdata( $post->post_author );
    $post_name = $author->display_name . '-' . $post_id;

    $wpdb->update( 'wp_posts', array( 'post_name' => $post_name ), array( 'ID' => $post_id ) );

    //If multiple blender files attached to post, remove all but latest
    $get_blender_file = get_attached_media( 'application/octet-stream', $post_id );
    if ( count( $get_blender_file ) > 1 )  {
       $remove_these = array_slice( $get_blender_file, 1 );
       foreach ( $remove_these as $remove_this ) {
           wp_delete_attachment( $remove_this->ID, true ); } }

    // If has 1 blender file attachment, rename and update
    if ( count( $get_blender_file ) === 1 ) {
        $file_ID       = array_key_first( $get_blender_file );
        $upload_dir    = wp_upload_dir();
        $file          = get_attached_file( $file_ID );
        $path          = pathinfo($file);
        $new_file      = $path['dirname'] . '/' . $post_name . '.' . $path['extension'];
        $rename        = rename( $file, $new_file );
        $update_file   = update_attached_file( $file_ID, $new_file );
        $wpdb->update( 'wp_posts', array( 'post_name' => $post_name ), array( 'ID' => $file_ID ) ); }

    // If has no blender file attachment, maintain post_status draft
    if ( count( $get_blender_file ) === 0 ) {
        $wpdb->update( 'wp_posts', array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
        $alert_message = 'Post cannot be published without attaching blender file'; }

}
if ( is_admin () ) {
    add_action( 'save_post_blender', 'save_cpt_blender' ); }


/**
 * Sets ID and post name columns to CPT blender.
 *
 * @since  1.0
 *
 * @global $columns
*/
function set_blender_columns( $columns ) {
    $columns['ID']          = 'ID';
    $columns['post_name']   = 'Name';
    return $columns;
}
if ( is_admin() ) {
    add_filter( 'manage_blender_posts_columns', 'set_blender_columns' ); }


/**
 * Adds values to ID and post name columns for CPT blender.
 *
 * @since  1.0
 *
 * @global $column $post_id
*/
function add_blender_column_values ( $column, $post_id ) {
    if ( $column === 'ID' ) {
        echo $post_id; }
    if ( $column === 'post_name' ) {
        $post_data = get_post( $post_id );
        echo $post_data->post_name; }
}
add_action( 'manage_blender_posts_custom_column' , 'add_blender_column_values', 10, 2 );


/**
 * Adds .blend format to list of allowed mime-types.
 *
 * @since  1.0
 *
 * @param  $existing_mimes
*/
function allow_blend_upload( $existing_mimes ) {
    $existing_mimes['blend'] = 'application/octet-stream';
    return $existing_mimes;
}
add_filter( 'mime_types', 'allow_blend_upload' );


/**
 * Enqueus ajax for frontend, non admin, use.
 *
 * @since  1.0
 *
*/
function frontend_ajax( ) {

    global $post;

    wp_enqueue_script( 'ajax-frontend-script',
                        get_template_directory_uri().'/js/ajax-frontend.js',
                        array('jquery')
                    );
    wp_localize_script( 'ajax-frontend-script',
                        'frontend_ajax',
                        array(
                            'ajax_url'         => admin_url( 'admin-ajax.php' ),
                            'nonce'            => wp_create_nonce('frontend-ajax-nonce'),
                            'post_id'          => $post->ID,
                        ));
}
add_action( 'wp_enqueue_scripts', 'frontend_ajax' );


/**
 * Callbacks for public and private ajax handlers.
 *
 * @since  1.0
 *
*/
function all_ajax_callback() {

    $task  = ( isset( $_POST['task'] ) ) ? ( $_POST['task'] ) : ( $_GET['task'] );
    $strip = ( isset( $_POST['task'] ) ) ? ( str_replace('\\', '', $_POST["data"]) ) : ( str_replace('\\', '', $_GET["data"]) );
    $data  = json_decode( $strip, false );

    //SEARCH FUNCTION CALLBACKS START HERE
    if ( $task === 'search-autofill' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-search-autofill.php' ); }

    if ( $task === 'keyword' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-search.php' ); }

    if ( $task === 'autofill' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-autofill-search.php' ); }

    //POST OBJECT CALLBACKS START HERE
    if ( $task === 'get-objects' || $task === 'paginate-objects' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-get-objects.php' ); }

    // CONTACT CALLBACK HERE
    if ( $task === 'contact' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-contact.php' ); }

    // CREATORS SPACE CALLBACKS START HERE
    if ( $task === 'sign-in' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-sign-in.php' ); }

    if ( $task === 'sign-up' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-sign-up.php' ); }

    if ( $task === 'reset' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-reset-password.php' ); }

    if ( $task === 'activate' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-activate-account.php' ); }

    if ( $task === 'resend' ) {
        require wp_make_link_relative( get_template_directory() . '/scripts/script-resend-activation-key.php' ); }

}
add_action( 'wp_ajax_all_ajax_action', 'all_ajax_callback' );
add_action( 'wp_ajax_nopriv_all_ajax_action', 'all_ajax_callback' );



/**
 * Callbacks for private ajax handlers.
 *
 * @since  1.0
 *
*/
function private_ajax_callback( ) {

        $task  = ( isset( $_POST['task'] ) ) ? ( $_POST['task'] ) : ( $_GET['task'] );
        if ( $task === 'blender-file' ) {
            $post_id = $_POST['post-id']; }
        else {
            $data_object = ( isset( $_POST['data'] ) ) ? ( $_POST['data'] ) : ( $_GET['data'] );
            $strip = str_replace('\\', '', $data_object );
            $data = json_decode( $strip, false ); }

        //POST CREATOR CALLBACKS START HERE
        if ( $task === 'blender-file' && current_user_can( 'upload_files' ) ) {
            $mode = $_POST['mode'];
            if ( $mode === 'add' ) {
                require wp_make_link_relative( get_template_directory() . '/scripts/script-add-blender-file.php' ); }
            else if ( $mode === 'edit' && current_user_can( 'edit_post', $post_id ) ) {
                require wp_make_link_relative( get_template_directory() . '/scripts/script-edit-blender-thumb.php' ); } }

        if ( $task === 'blender-description' && current_user_can( 'edit_post', $data->post_id ) ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-add-blender-description.php' ); }

        if ( $task === 'blender-meta' && current_user_can( 'edit_posts', $data->post_id ) ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-add-blender-meta.php' ); }

        if ( $task === 'blender-options' && current_user_can( 'edit_posts', $data->post_id ) ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-add-blender-options.php' ); }

        //PRIVATE GALLLERY/CASE MANAGER SCRIPTS START HERE
        if ( $task === 'blender-delete' && current_user_can( 'delete_posts', $data->post_id ) ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-delete-blender.php' ); }


        //UPDATE PROFILE/USER SCRIPTS START HERE
        if ( $task === 'upload-media' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-upload-profile-media.php' ); }

        if ( $task === 'delete-media' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-delete-profile-media.php' ); }

        if ( $task === 'profile-details' || $task === 'profile-social' || $task === 'profile-contact' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-update-profile.php' ); }

        if ( $task === 'user-details' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-update-user-details.php' ); }

        if ( $task === 'change-email') {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-change-email.php' ); }

        if ( $task === 'cancel-change-email') {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-cancel-change-email.php' ); }

        if ( $task === 'resend-change-email' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-resend-change-email.php' ); }

        if ( $task === 'verify-email') {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-verify-email.php' ); }

        if ( $task === 'change-password' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-change-password.php' ); }


        //COMMENT HANDLERS START HERE
        if ( $task === 'add-comment' || $task === 'edit-comment' || $task === 'reply-comment' || $task === 'delete-comment'  ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-comment-handler.php' ); }

        if ( $task === 'get-comments' ) {
            require wp_make_link_relative( get_template_directory() . '/scripts/script-get-comments.php' ); }

}
if ( is_user_logged_in() ) {
    add_action( 'wp_ajax_private_ajax_action', 'private_ajax_callback' ); }



/**
 * Deletes CPT Blender post attacthments ( .blend file and feature image ) along with post.
 *
 * @since  1.0
 *
 *
 * @global $post_id
*/
function delete_blender_attachments( $post_id ) {
  if( get_post_type( $post_id ) == 'blender' ) {
    $blender_attachments = get_attached_media( '', $post_id );
    foreach ($blender_attachments as $blender_attachment) {
      wp_delete_attachment( $blender_attachment->ID, 'true' ); } }
  return; }
add_action( 'before_delete_post', 'delete_blender_attachments' );


/**
 * Registers default taxonomies on application post types.
 *
 * @since  1.0
 *
*/
function register_default_taxonomy() {
   register_taxonomy_for_object_type( 'post_tag', 'blender' );
   register_taxonomy_for_object_type( 'post_tag', 'page' );
   register_taxonomy_for_object_type( 'category', 'page' );
   register_taxonomy_for_object_type( 'category', 'blender' );
   register_taxonomy_for_object_type( 'category', 'profile' );
   register_taxonomy_for_object_type( 'post_tag', 'profile' ); }
add_action('init', 'register_default_taxonomy');

/**
 * Registers taxonomy blender_categories for CPT blender and profile
 *
 * @since  1.0
*/
register_taxonomy( 'blender_categories',
    array( 'blender', 'profile' ),
    array(  'hierarchical'      => true,
            'label'             => 'Blender Categories',
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true
        )
);

/**
 *Limits post excerpt length.
 *
 * @since  1.0
*/
function post_excerpt_length(){
    return 30; }
add_filter( 'excerpt_length', 'post_excerpt_length', 999 );


/**
 * Adds HTML support to wp_mail function.
 *
 * @since  1.0
*/
function set_wp_mail_content_type() {
        return "text/html"; }
add_filter( 'wp_mail_content_type','set_wp_mail_content_type' );


/**
 * Template for sign up email.
 *
 * @param $args
 *
 * @since  1.0
*/
function sign_up_email ( $args ) {
    $body =
    '<p>Hello <em>'. $args['user'] .'</em>,</p>
    <p>Thank you for signing up at '. $args['site'] .'.</p>
    <p>Please activate your account at
        <a href="'. $args['url'] .'">'. $args['url'] .'</a>.
    </p>
    <p>Your activation key: <b>'. $args['key'] .'</b></p>
    <br>
    <p>Regards,</p>
    <p><em>'. $args['site'] .'</em></p>';

    return $body; }


/**
 * Template for reset password email.
 *
 * @param $args
 *
 * @since  1.0
*/
function reset_password_email ( $args ) {
    $body =
    '<p>Hello <em>'. $args['user'] . '</em>,</p>
    <p>We have received a request to reset the password for the account associated with this email.</p>
    <p>A new password is provided in this email. It is recommended that you change it once you have signed in.</p>
    <p>Your new password: <b> '. $args['password'] . '</b></p>
    <p>Regards,</p>
    <p><em>'. $args['site'] .'</em></p>
    <a href="'. $args['url'] .'">'. $args['url'] .'</a>';

    return $body; }


/**
 * Template for changel email email
 *
 * @param $args
 *
 * @since  1.0
*/
function change_email_email ( $args ) {
   $body =
   '<p>Hello <em>' . $args['user_name']  . '</em>,</p>
   <p>We have received a request to change the email associated with your account.</p>
   <p>Complete the request under your settings/user section, with the verification key provided in this email.</p>
   <p>Verification key: <b>' . $args['verify_key'] . '</b></p>
   <p>Regards,</p>
   <em>'. get_bloginfo() .'</em>';

    return $body; }


/**
 * Sets radio input values in forms
 *
 * @since  1.0
 */
function set_radio_inputs( $status, $input ) {
    if ( $status === 'open' || $status === 'yes' ) {
        ?>
        <script type="text/javascript">
            document.getElementById('<?php echo $input . '-yes' ?>').checked = true;
        </script>
        <?php }
    if ( $status === 'closed' || $status === 'no') {
        ?>
        <script type="text/javascript">
            document.getElementById('<?php echo $input . '-no' ?>').checked = true;
        </script>
        <?php }
    if ( $status === 'icon' || $status === 'text' ) {
        ?>
        <script type="text/javascript">
            const radioInputs = document.querySelectorAll('[name="<?php echo $input ?>"]');
            for ( let radioInput of radioInputs ) {
                if ( radioInput.value === '<?php echo $status; ?>' ) {
                    radioInput.checked = true; } }
            const iconInputs = document.querySelectorAll('.menu-item-icon');
            for ( let iconInput of iconInputs ) {
                if ( '<?php echo $status; ?>' === 'icon' ) {
                    iconInput.classList.remove('hide'); }
                else {
                    iconInput.classList.add('hide'); } }
        </script>
        <?php
    }
    return;
}


/**
 * Handles toggle switches in forms.
 *
 * @since  1.0
 */
function toggleSwitch ( $toggleSwitch, $value ) {
    ?>
    <script type="text/javascript">
    const choice = <?php echo $value ?>;
    if ( choice == 1 ) {
        document.getElementById('<?php echo $toggleSwitch ?>').checked = true; }
    else if ( choice == 0 )  {
        document.getElementById('<?php echo $toggleSwitch ?>').checked = false; }
    </script>
    <?php
    return; }


/**
 * Sets select input value in forms.
 *
 * @since  1.0
 */
function set_select_inputs ( $input, $value ) {
    ?>
    <script type="text/javascript">
        document.getElementById('<?php echo $input; ?>').value = '<?php echo $value; ?>';
    </script>
    <?php
    return; }


/**
 * Sets the info items on page-home.php
 *
 * @since  1.0
 */
function set_home_info_items ( ) {
    ?>
    <script type="text/javascript">
        const container = document.getElementById('home-info-item-container');
        if ( container !== null ) {
        const items = container.children;
        switch ( items.length  ) {
            case 1:
                items[0].firstElementChild.className = 'polygon';
                break;
            case 2:
                items[0].firstElementChild.className = 'polygon-top';
                items[1].firstElementChild.className = 'polygon-bottom';
                break;
            case 3:
                items[0].firstElementChild.className = 'polygon-top';
                items[1].firstElementChild.className = 'rectangle';
                items[2].firstElementChild.className = 'polygon-bottom';
                break;
            case 4:
                items[0].firstElementChild.className = 'polygon-top';
                items[1].firstElementChild.className = 'rectangle';
                items[2].firstElementChild.className = 'rectangle';
                items[3].firstElementChild.className = 'polygon-bottom';
                break;
            default:
                break; }
        }
    </script>
    <?php
    return;
}


/**
 * Checks/sets and returns terms cache transient.
 *
 * @since  1.0
 */
function check_set_terms_cache() {

    $check_terms_cache_object = get_transient( 'terms_cache_object' );

    if ( $check_terms_cache_object === false ) {

        global $wpdb;

        $terms_to_cache = array(
            'blender_cat'  => $wpdb->get_results( "SELECT wp_terms.name, wp_terms.term_id, wp_term_taxonomy.parent, wp_term_taxonomy.count
                                                   FROM wp_terms
                                                   INNER JOIN wp_term_taxonomy
                                                   ON wp_terms.term_id = wp_term_taxonomy.term_id
                                                   WHERE taxonomy = 'blender_categories'
                                                   ORDER BY wp_terms.name ASC
                                                   LIMIT 150"
                                      ),
            'profile_cat'  => $wpdb->get_results( "SELECT wp_terms.name, wp_terms.term_id, wp_term_taxonomy.parent, wp_term_taxonomy.count
                                                   FROM wp_terms
                                                   INNER JOIN wp_term_taxonomy
                                                   ON wp_terms.term_id = wp_term_taxonomy.term_id
                                                   WHERE taxonomy = 'profile_categories'"
                                      ),
            'profile_init' => $wpdb->get_results( "SELECT wp_terms.name, wp_terms.term_id, wp_terms.slug, wp_term_taxonomy.count
                                                   FROM wp_terms
                                                   INNER JOIN wp_term_taxonomy
                                                   ON wp_terms.term_id = wp_term_taxonomy.term_id
                                                   WHERE taxonomy = 'profile_initials'
                                                   AND NOT count=0"
                                      ),
            'tags'         => $wpdb->get_results( "SELECT wp_terms.name, wp_terms.term_id
                                                   FROM wp_terms
                                                   INNER JOIN wp_term_taxonomy
                                                   ON wp_terms.term_id = wp_term_taxonomy.term_id
                                                   WHERE taxonomy = 'post_tag'
                                                   AND NOT count=0"
                                      ),
            'artists'      => $wpdb->get_results( "SELECT post_name
                                                   FROM wp_posts
                                                   WHERE post_type = 'profile'
                                                   LIMIT 2000"
                                                )
        );

        $search_autofill_categories = array();
        foreach ( $terms_to_cache['blender_cat'] as $object ) {
            $compiled_object                  = array();
            $compiled_object['query_type']    = 'category';
            $compiled_object['value']         = $object->name;
            $compiled_object['term_id']       = $object->term_id;
            array_push( $search_autofill_categories, $compiled_object ); }

        $search_autofill_tags = array();
        foreach ( $terms_to_cache['tags'] as $tag ) {
            $compiled_tag                  = array();
            $compiled_tag['query_type']    = 'tag';
            $compiled_tag['value']         = $tag->name;
            $compiled_tag['term_id']       = $tag->term_id;
            array_push( $search_autofill_tags, $compiled_tag ); }

        $search_autofill_artists = array();
        foreach ( $terms_to_cache['artists'] as $artist ) {
            $compiled_artist               = array();
            $compiled_artist['query_type'] = 'artist';
            $compiled_artist['value']      = $artist->post_name;
            array_push( $search_autofill_artists, $compiled_artist ); }

        $search_autofill_cache = array_merge( $search_autofill_categories, $search_autofill_tags, $search_autofill_artists );

        $cache_object = array(
            'terms_cache'    => $terms_to_cache,
            'autofill_cache' => $search_autofill_cache );

        $terms_posts_cache_timeout = get_option( 'terms_posts_cache_timeout' );

        if ( !empty( $terms_posts_cache_timeout )) {
            $set_terms_cache_object = set_transient( 'terms_cache_object', $cache_object, $terms_posts_cache_timeout ); }
        else {
            $set_terms_cache_object = set_transient( 'terms_cache_object', $cache_object, 900 ); }

        if ( $set_terms_cache_object === false ) {
            return 'unable to set cache object'; };

        $get_terms_cache_object = get_transient( 'terms_cache_object' );

        if ( $get_terms_cache_object === false ) {
            return 'unable to get cache object'; }

        return $get_terms_cache_object;
    }
    return $check_terms_cache_object;
}



/**
 * Checks/sets and returns post cache transient.
 *
 * @since  1.0
 */
function check_set_posts_cache() {

    $check_posts_cache_object = get_transient( 'posts_cache_object' );

    if ( $check_posts_cache_object === false ) {

        global $wpdb;

        $posts_to_cache = $wpdb->get_results( "SELECT post_status, post_author, post_content, post_excerpt,
                                                      post_type, post_title, ID, post_name
                                               FROM wp_posts
                                               WHERE ( post_status = 'publish' OR post_status = 'draft' )
                                               AND ( post_type = 'blender' OR post_type = 'profile' OR post_type = 'post'
                                                     OR post_type ='page' )
                                               LIMIT 3000"
                                           );

        $compiled_posts = array();
        foreach ( $posts_to_cache as $post ) {
            $compiled_posts[$post->ID] = $post; }

        $terms_posts_cache_timeout = get_option( 'terms_posts_cache_timeout' );

        if ( !empty( $terms_posts_cache_timeout )) {
            $set_posts_cache_object = set_transient( 'posts_cache_object', $compiled_posts, $terms_posts_cache_timeout ); }
            else {
            $set_posts_cache_object = set_transient( 'posts_cache_object', $compiled_posts, 900 ); }

        if ( $set_posts_cache_object === false ) {
            return 'unable to set cache object'; };

        $get_posts_cache_object = get_transient( 'posts_cache_object' );

        if ( $get_posts_cache_object === false ) {
            return 'unable to get cache object'; }

        return $get_posts_cache_object;
    }
    return $check_posts_cache_object;
}



function get_post_objects( $ID, $origin, $output_type, $pag_amount ) {
    ?>
    <script type="text/javascript">
        clearPaginatorUi( '<?php echo $origin  ?>' );
        getObjects( '<?php echo $ID  ?>', null, '<?php echo $origin  ?>', '<?php echo $output_type  ?>', '<?php echo $pag_amount ?>' );
    </script>
    <?php }


/**
 * Toggles to search option and triggers AJAX search() on page-discover.php.
 *
 * @since  1.0
*/
function activate_search( $search_type, $tax_name, $tax_ID, $search_query ) {
    $paging = get_option('paginators');
    $id     = $tax_ID !== null ? $tax_ID : null;
    $name   = $tax_name !== null ? $tax_name  : null;
    $query  = $search_query !== null ? $search_query : null;
    ?>
    <script type="text/javascript">
        document.getElementById('discover-objects-option').classList.add('hide');
        document.getElementById('discover-artists-option').classList.add('hide');
        document.getElementById('discover-search-option').classList.remove('hide');
        search( "<?php echo $search_type ?>" , "<?php echo $name ?>" , "<?php echo $id ?>",
                "<?php echo $query ?>", "<?php echo $paging['paginator_search'] ?>" );
    </script>
    <?php
    return; }


/**
 * Removes default p tags in post content output on pages
 *
 * @since  1.0
 */
function disable_wp_auto_p( $content ) {
  if ( is_singular( 'page' ) ) {
    remove_filter( 'the_content', 'wpautop' ); }
  return $content; }
add_filter( 'the_content', 'disable_wp_auto_p', 0 );



/**
 * Toggles the case option containers on redirect from page-creators-upload to page-creators-gallery
 *
 * @since  1.0
 */
function set_gallery_case( $selected ) {
  ?>
  <script type="text/javascript">
      const caseManagerOptions = document.getElementsByClassName('case-manager-option');
      for ( let option of caseManagerOptions ) {
          if ( option.id.includes( '<?php echo $selected ?>' ) ) {
              option.classList.remove('hide'); }
          else {
              option.classList.add('hide'); } }
  </script>
  <?php
  return; }



/**
 * Calls getComments AJAX on single post templates.
 *
 * @since  1.0
 */
function retrieve_comments( ) {
    ?>
    <script type="text/javascript">
        getParentComments();
    </script>
    <?php
    return; }

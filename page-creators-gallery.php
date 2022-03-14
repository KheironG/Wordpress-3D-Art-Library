<?php get_header();

if ( $current_user->ID != 0 || is_user_logged_in() ) {
    global $wpdb;
    $get_objects = $wpdb->get_results( "SELECT ID, post_name, post_title, post_status, post_excerpt
                                        FROM wp_posts
                                        WHERE post_author = $current_user->ID
                                        AND post_type = 'blender'
                                        AND ( post_status = 'publish' OR post_status = 'draft' OR post_status = 'pending')
                                        ORDER BY ID DESC ");

    $objects = array(
        'published' => array(),
        'submitted' => array(),
        'rejected'  => array()
    );

    foreach ( $get_objects as $object ) {
        switch ( $object->post_status ) {
            case 'publish':
                array_push( $objects['published'], $object );
                break;
            case 'draft':
                array_push( $objects['submitted'], $object );
                break;
            case 'pending':
                 array_push( $objects['rejected'], $object );
                break;
            default:
                break;
        }
    }?>
     <div class="container-1000">
         <h1>my gallery</h1>
         <h5><?php echo the_content( ); ?></h5>
         <br>
         <!-- If gallery is empty -->
        <?php
            if ( empty( $objects['published'] ) && empty( $objects['submitted'] ) && empty( $objects['rejected'] ) ) {
                ?>
                <div class="flex-container">
                    <p>your gallery is empty.</p>
                    <a class="private-gallery-icon" href="<?php echo esc_url( '/creators-upload' ) ?>">
                        <span class="fas fa-plus-square fa-3x"></span>
                        <small>create</small>
                    </a>
                </div>
                <?php
            }
        ?>
         <!-- Case menus -->
         <div class="flex-container gap-45 border-bottom" style="padding-bottom:25px;">
             <?php
             function case_manager_menu( $objects, $status, $icon ) {
                 if ( !empty( $objects[$status] ) ) {
                     ?><a class="case-manager-menu" onclick="toggleCaseManagager('<?php echo $status ?>')">
                         <span class="<?php echo $icon ?>"></span>
                         <b> <?php echo $status ?> ( <?php echo count( $objects[$status] ) ?> ) </b>
                     </a><?php
                 }
            }
            $menus = array(
                'published'  => 'fas fa-cube fa-2x',
                'submitted' => 'fas fa-server fa-2x',
                'rejected'   => 'fas fa-exclamation-circle fa-2x'
            );
            foreach ( $menus as $status => $icon ) {
                echo case_manager_menu( $objects, $status, $icon );;
            };?>
         </div>
         <!-- Case options -->
         <?php
         function case_manager_option( $objects, $status ) {
             $display =  ( $status === 'published' ) ? ( ' ') : 'hide';
             ?>
             <div class="case-manager-option <?php echo $display ?>" id="case-<?php echo $status ?>-option">
                 <h4 class="blue-text"><?php echo $status ?></h4>
                 <br>
                 <div class="post-results-container">
                     <?php
                     switch ( $status ) {
                         case 'published':
                             foreach ( $objects['published'] as $published ) {
                                 get_template_part('template-parts/content', 'creators-gallery-item', $published );
                             }
                             break;
                         case 'submitted':
                             foreach ( $objects['submitted'] as $submitted ) {
                                 get_template_part('template-parts/content', 'creators-gallery-item', $submitted );
                             }
                             break;
                         case 'rejected':
                             foreach ( $objects['rejected'] as $rejected ) {
                                  get_template_part('template-parts/content', 'creators-gallery-item', $rejected );
                             }
                             break;
                         default:
                             break;
                     }?>
                 </div>
             </div>
             <?php
         };
         if ( !empty( $objects['published'] ) ) {
            echo case_manager_option( $objects, 'published' );
         }
         if ( !empty( $objects['submitted'] ) ) {
             echo case_manager_option( $objects, 'submitted' );
         }
         if ( !empty( $objects['rejected'] ) ) {
             echo case_manager_option( $objects, 'rejected' );
         }
         ?>
     </div>
     <?php
     //If redirected from page-creators-upload.php
     $query = parse_str( $_SERVER['QUERY_STRING'] );
     if ( $case === 'draft' ) {
         echo set_gallery_case( 'submitted' );
     } elseif ( $case === 'publish' ) {
         echo set_gallery_case( 'published' );
     }
 }
 else {
     wp_redirect( get_home_url() . '/creators-space' );
     exit;
 }
 get_footer(); ?>

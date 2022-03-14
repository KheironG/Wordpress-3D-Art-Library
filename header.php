<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>3D Platform</title>
    <?php wp_head(); ?>
</head>
<body>
<?php
$current_user = wp_get_current_user();
require wp_make_link_relative( get_template_directory() . '/template-parts/part-header-menus.php' );
?>
<header>
    <div id="header-primary">
        <div id="header-logo">
            <?php the_custom_logo(); ?>
        </div>
        <div id="public-links">
            <?php
            echo headerMenu();
            echo signInOut();
            ?>
        </div>
        <div id="mobile-menu">
            <div id="hamburger" onclick="mobileMenu();">
                <i class="fas fa-bars fa-2x"></i>
                <small>menu</small>
            </div>
            <div>
                <?php echo signInOut();  ?>
            </div>
        </div>
    </div>
    <div id="mobile-links">
        <?php
        echo headerMenu();
         ?>
    </div>
    <div class="flex-container-center">
        <div class="search-container">
            <form id="header-search-form" method="post" autocomplete="off">
                    <input id="header-search-input" type="text" name="header-search-input"
                    onkeyup="searchAutofill('header');" autocomplete="off" >
                    <label class="trigger" onclick="activateSearch( 'keyword', null, null, null, 'header' );" for="header-search-input"><span class="header-search"></span></label>
            </form>
        </div>
    </div>
</header>
<div id="private-links">
    <?php
    echo privateMenu( $current_user );
    ?>
</div>
<div id="signed-in-as">
    <small class="block">signed in as <small class="label"> <?php echo $current_user->display_name; ?></small></small>
</div>
<main>

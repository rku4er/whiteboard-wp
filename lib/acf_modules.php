<?php

namespace Roots\Sage\ACFmodules;

use Roots\Sage\Utils;
use Roots\Sage\Titles;


/**
 *  Navbar Logo
 */

function sage_navbar_logo() {

    $output = '';
    $options = Utils\sage_get_options();
    $logo = $options['navbar_logo'];

    if( $logo ) {

        $output = sprintf('<a class="navbar-brand" href="%s">%s</a>',
            home_url('/'),
            sprintf('<img src="%s" alt="%s">', $logo['url'], get_bloginfo('name'))
        );

    }

    echo $output;
}


/**
 *  Navbar position class
 */

function sage_navbar_position_class() {

    $output = '';
    $options = Utils\sage_get_options();
    $navbar_position = $options['navbar_position'];

    if( $navbar_position ) {
        $output = 'navbar-'. $navbar_position;
    } else {
        $output = 'navbar-static-top';
    }

    echo $output;

}


/**
 *  Navbar phone
 */

function sage_navbar_phone() {

    $output = '';
    $options = Utils\sage_get_options();
    $phone = $options['navbar_phone'];

    if( $phone ) {
        $output = sprintf('<span class="nav-phone">%s</span>', $phone);
    }

    echo $output;

}


/**
 *  Navbar button
 */

function sage_navbar_button() {

    $output = '';
    $options = Utils\sage_get_options();
    $button = $options['navbar_button'];

    if( $button ) {
         $output = sprintf('<a href="%s" class="navbar-btn">%s</a>', $button, __('Contakt Oss', 'sage'));
    }

    echo $output;

}


/**
 *  Navbar menu
 */

function sage_navbar_menu($id) {

    $output = '';

    if ( $id && has_nav_menu('primary_navigation') ) {
        $output = sprintf('<div class="collapse navbar-toggleable-xs" id="%s">%s</div>',
            $id,
            wp_nav_menu( array(
                'menu_class'    => 'nav navbar-nav',
                'echo'          => false
            ))
        );
    }

    echo $output;

}


/**
 *  Navbar menu
 */

function sage_navbar_menu_toggler($id) {

    $output = '';

    if ( $id && has_nav_menu('primary_navigation') ) {
        $output = <<<EOT
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#{$id}">
                <span class="icon-menu">&#9776;</span>
                <span class="icon-close">&#10006;</span>
            </button>
EOT;
    }

    echo $output;

}


/**
 * Gravity form
 */

function sage_gravity_form($gform_field) {

    $output = '';
    $form = Utils\sage_get_field($gform_field);

    if( $form ) {
        $output = do_shortcode('[gravityform id="' . $form->id . '" title="true" description="true" ajax="true"]');
    }

    echo $output;
}


/**
 * Copyright info
 */

function sage_copyright() {

    $output = '';
    $options = Utils\sage_get_options();
    $copyright = $options['copyright'];

    if( $copyright ) {
        $output = sprintf('<div class="copyright">%s</div>', $copyright);
    }

    echo $output;

}

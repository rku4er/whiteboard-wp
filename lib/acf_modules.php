<?php

namespace Roots\Sage\ACFmodules;

use Roots\Sage\Utils;
use Roots\Sage\Titles;


/**
 *  Navbar Logo
 */

function sage_navbar_logo() {

    $output  = '';
    $options = Utils\sage_get_options();
    $logo_sm = $options['navbar_logo_mobile'];
    $logo_lg = $options['navbar_logo_desktop'];

    if ($logo_sm || $logo_lg) {

        $output = sprintf('<a class="navbar-brand" href="%s"><span class="hidden-sm-up">%s</span><span class="hidden-xs-down">%s</span></a>',
            home_url('/'),
            sprintf('<img src="%s" alt="%s">', $logo_sm['url'], get_bloginfo('name')),
            sprintf('<img src="%s" alt="%s">', $logo_lg['url'], get_bloginfo('name'))
        );

    }

    echo $output;
}


/**
 *  Navbar position class
 */

function sage_navbar_position_class() {

    $output          = '';
    $options         = Utils\sage_get_options();
    $navbar_position = $options['navbar_position'];

    if ($navbar_position) {
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

    $output  = '';
    $options = Utils\sage_get_options();
    $phone   = $options['navbar_phone'];

    if ($phone) {
        $output = sprintf('<a href="tel:+47%s" class="navbar-phone">(+47) %s</a>',
            preg_replace('/[^0-9]+/', '', $phone),
            $phone
        );
    }

    echo $output;

}


/**
 *  Navbar button
 */

function sage_navbar_button() {

    $output  = '';
    $options = Utils\sage_get_options();
    $id  = $options['contact_section_id'];

    if ($id) {
         $output = sprintf('<a href="#%s" class="navbar-btn">%s</a>', $id, __('Contakt Oss', 'sage'));
    }

    echo $output;

}


/**
 *  Navbar menu
 */

function sage_navbar_menu( $location = 'primary_navigation' ) {

    $output = '';

    if (has_nav_menu( $location )) {
        $output = wp_nav_menu( array(
                'theme_location'=> $location,
                'menu_class'    => 'nav navbar-nav',
                'echo'          => false
            ));
    }

    echo $output;

}


/**
 *  Navbar menu
 */

function sage_navbar_menu_toggler($id) {

    $output = '';

    if ($id && has_nav_menu('primary_navigation')) {
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
    $form   = Utils\sage_get_field($gform_field);

    if ($form) {
        $output = do_shortcode('[gravityform id="' . $form->id . '" title="true" description="true" ajax="true"]');
    }

    echo $output;
}


/**
 * Copyright info
 */

function sage_copyright() {

    $output    = '';
    $options   = Utils\sage_get_options();
    $copyright = $options['copyright'];

    if ($copyright) {
        $output = sprintf('<div class="copyright">%s</div>', $copyright);
    }

    echo $output;

}


/**
 * Flexible Layout content
 */

function sage_get_row_content ( $row ) {

    $output        = '';
    $layout        = $row['acf_fc_layout'];
    $prefix        = $layout .'_';
    $section_id    = $row['section_id'] ? $row['section_id'] : uniqid($prefix);
    $section_title = $row['section_title'];

    $bg_color      = $row['background_color'];
    $bg_image      = $row['background_image'];
    $bg_size       = $row['background_size'];
    $bg_position   = $row['background_position_x'] .' '. $row['background_position_y'];
    $bg_attachment = $row['background_attachment'];
    $style         = '';

    if ($section_title) $output .= sprintf('<h2 class="section-title">%s</h2>', $section_title);

    if ($bg_color) $style .= sprintf('background-color: %s; ', $bg_color);
    if ($bg_image) {
        $style .= sprintf('background-image: url(%s); ', $bg_image);
        if ($bg_size) $style .= sprintf('background-size: %s; ', $bg_size);
        if ($bg_position) $style .= sprintf('background-position: %s; ', $bg_position);
        if ($bg_attachment) $style .= sprintf('background-attachment: %s; ', $bg_attachment);
    }

    if ($layout === 'video') {

        if ($row['video']) {
            preg_match('/(?<=src=\")[^\"]*/', $row['video'], $video_src);
            preg_match('/(?<=width=\")[^\"]*/', $row['video'], $video_width);
            preg_match('/(?<=height=\")[^\"]*/', $row['video'], $video_height);
            $video_percent_height = round($video_height[0] / $video_width[0] * 100);

            $output .= <<<EOT
                <div class="video-wrapper" style="padding-bottom: {$video_percent_height}%">
                    <a href="{$video_src[0]}">
                        <img src="{$row['thumbnail']}" alt="intro">
                    </a>
                </div>
EOT;
        }

    } elseif ($layout === 'goals') {

        $goals = $row['goals'];

        if ($goals) {
            $output .= '<ul class="goals-wrapper">';
            foreach ($goals as $goal) {
                $output .= <<<EOT
                    <li>
                        <span class="thumb-wrapper">
                            <img src="{$goal['icon']}" alt="goal-icon"/>
                        </span>
                        {$goal['text']}
                    </li>
EOT;
            }
            $output .= '</ul>';
        }

    } elseif ($layout === 'price') {

        $header = __('Pris', 'sage') . $row['price'] . ' kr';

        if ($row['content']) {
            $output .= <<<EOT
                <div class="price-wrapper">
                    <header>{$header}</header>
                    <div class="content">{$row['content']}</div>
                </div>
EOT;
        }

    }


    return <<<EOT
        <div id="{$section_id}" class="section {$layout}" style="{$style}">
            <div class="container">{$output}</div>
        </div>
EOT;

}

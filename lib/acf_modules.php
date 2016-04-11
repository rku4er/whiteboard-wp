<?php

namespace Roots\Sage\ACFmodules;

use Roots\Sage\Utils;
use Roots\Sage\Titles;


/**
 * Get gravity form
 */

function sage_get_gravity_form($form_object) {

    if( $form_object ) {
        return do_shortcode('[gravityform id="' . $form_object->id . '" title="true" description="true" ajax="true"]');
    }
}


/**
 *  Navbar Logo
 */

function sage_navbar_logo() {

    $output  = '';
    $options = Utils\sage_get_options();
    $logo_sm = $options['navbar_logo_mobile'];
    $logo_lg = $options['navbar_logo_desktop'];

    if ($logo_sm || $logo_lg) {

        $blogname = get_bloginfo('name');
        $tag      = (is_front_page()) ? 'h1' : 'a';
        $href     = (!is_front_page()) ? 'href="'. home_url('/') .'"' : '';

        $output .= <<<EOT

        <{$tag} class="navbar-brand" {$href}>
            <span class="hidden-sm-up">
                <img src="{$logo_sm['url']}" alt="{$blogname}">
            </span>
            <span class="hidden-xs-down">
                <img src="{$logo_lg['url']}" alt="{$blogname}">
            </span>
        </{$tag}>
EOT;
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
        $phone_trim = preg_replace('/[^0-9]+/', '', $phone);
        $output .= <<<EOT

        <a href="tel:+47{$phone_trim}" class="navbar-phone">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#phone"></use></svg>
            (+47) {$phone}</a>
EOT;
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
         $output = sprintf('<a href="#%s" class="navbar-btn nav-link">%s</a>', $id, __('Kontakt Oss', 'sage'));
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

function sage_get_row_content ( $row, $args ) {

    $output        = '';
    $layout        = $row['acf_fc_layout'];
    $scroll_target = $row['next_sibling_id'];
    $prefix        = $layout .'_';
    $section_id    = $row['section_id'] ? $row['section_id'] : uniqid($prefix);
    $section_title = $row['section_title'];
    $numberposts   = array_key_exists('numberposts', $args) ? $args['numberposts'] : '';

    $title_color   = $row['title_color'];
    $content_color = array_key_exists('content_color', $row) ? $row['content_color'] : '';
    $bg_color      = $row['background_color'];
    $bg_image      = $row['background_image'];
    $bg_size       = $row['background_size'];
    $bg_repeat     = $row['background_repeat'];
    $bg_position   = $row['background_position_x'] .' '. $row['background_position_y'];
    $bg_attachment = $row['background_attachment'];
    $section_style = '';

    if ($bg_color) $section_style .= sprintf('background-color: %s; ', $bg_color);
    if ($bg_image) {
        $section_style .= sprintf('background-image: url(%s); ', $bg_image);
        if ($bg_size) $section_style .= sprintf('background-size: %s; ', $bg_size);
        if ($bg_repeat) $section_style .= sprintf('background-repeat: %s; ', $bg_repeat);
        if ($bg_position) $section_style .= sprintf('background-position: %s; ', $bg_position);
        if ($bg_attachment) $section_style .= sprintf('background-attachment: %s; ', $bg_attachment);
    }

    if ($layout === 'video') {

        if ($row['video']) {
            preg_match('/(?<=src=\")[^\"]*/', $row['video'], $video_src);
            preg_match('/(?<=width=\")[^\"]*/', $row['video'], $video_width);
            preg_match('/(?<=height=\")[^\"]*/', $row['video'], $video_height);
            $video_percent_height = round($video_height[0] / $video_width[0] * 100);

            $output .= <<<EOT

            <div class="{$layout}-wrapper" style="padding-bottom: {$video_percent_height}%">
                <a href="{$video_src[0]}">
                    <img src="{$row['thumbnail']}" alt="intro">
                </a>
            </div>
EOT;
        }

    } elseif ($layout === 'goals') {

        $goals = $row['goals'];

        if ($goals) {
            $output .= '<ul class="'. $layout .'-wrapper">';
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

        if ($row['content']) {
            $output .= <<<EOT

            <div class="{$layout}-wrapper">
                <header class="price">
                    <h3 class="title" style="color: {$title_color}">{$section_title}</h3>
                    <span class="amount">
                        {$row['price']}<span class="addon">kr</span>
                    </span>
                </header>
                <div class="content">{$row['content']}</div>
            </div>
EOT;
        }

    } elseif ($layout === 'portfolio') {

        $carousel =  $row['carousel'];
        $carouselID = 'portfolio-carousel';

        if ($carousel) {
            $output .= <<<EOT

            <div id="{$carouselID}"  class="{$layout}-wrapper">
                <div class="jcarousel">
                    <ul>
EOT;

            foreach ($carousel as $item) {
                $output .= <<<EOT

                <li>
                    <div class="thumb-wrapper">
                        <a href="{$item['video']}" class="video-lightbox">
                            <img src="{$item['thumb']}" alt="{$item['title']}">
                        </a>
                    </div>
                    <h3 class="title">{$item['title']}</h3>
                </li>
EOT;
            }
            $output .= <<<EOT

                    </ul>
                </div>
                <button type="button" class="jcarousel-control-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"><use xlink:href="#prev"></use></svg>
                </button>
                <button type="button" class="jcarousel-control-next">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon"><use xlink:href="#next"></use></svg>
                </button>
            </div>
EOT;
        }

    } elseif ($layout === 'testimonials') {

        $carousel =  $row['carousel'];
        $carouselID = 'testimonial-carousel';
        $indicators = array();

        if ($carousel) {
            $output .= <<<EOT

            <div id="{$carouselID}" class="{$layout}-wrapper carousel slide" data-ride="carousel" data-interval="0">
                <ul class="carousel-inner" role="listbox">
EOT;
            $i=-1;

            foreach ($carousel as $item) {
                $i++;
                $active = ($i === 0) ? 'active' : '';

                $output .= <<<EOT

                <li class="carousel-item {$active}">
                    <blockquote class="quote">
                        {$item['quote']}
                        <footer class="author">
                            <img class="thumb" src="{$item['thumb']['sizes']['thumbnail']}" alt="{$item['name']}">
                            <span class="cite">
                                <strong class="name">{$item['name']}</strong>
                                <em class="position">{$item['position']}</em>
                            </span>
                        </footer>
                    </blockquote>
                </li>
EOT;
            }

            $output .= <<<EOT

                </ul>

                <a class="left carousel-control" href="#{$carouselID}" role="button" data-slide="prev">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon"><use xlink:href="#left"></use></svg>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#{$carouselID}" role="button" data-slide="next">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon"><use xlink:href="#right"></use></svg>
                  <span class="sr-only">Next</span>
                </a>

            </div>
EOT;
        }

    } elseif ($layout === 'text') {

        if ($row['content']) {
            $output .= <<<EOT

            <div class="{$layout}-wrapper">
                <div class="content">{$row['content']}</div>
            </div>
EOT;
        }

    } elseif ($layout === 'ordering') {

       $items = $row['items'];

       if ($items) {
           $output .= '<ol class="'. $layout .'-wrapper">';

           $i = 0;
           foreach ($items as $item) {
               $i++;
               $id = ($i%7 !== 0) ? $i%7 : 7;

               $output .= <<<EOT

               <li>
                   <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#forma{$id}"></use></svg>
                   <h3 class="title">{$item['title']}</h3>
                   <div class="description">{$item['description']}</div>
               </li>
EOT;
           }
           $output .= '</ol>';
       }

   } elseif ($layout === 'blog') {

       $load_p_mesg = __('Eldre Innlegg', 'sage');

       if($numberposts) {

           $posts = get_posts(array(
               'post_type'   => 'post',
               'numberposts' => $numberposts,
               'offset'      => $args['offset'],
               'post_status' => 'publish'
           ));

           if ($posts) {
                foreach ($posts as $post) {
                    $thumb       = get_the_post_thumbnail($post->ID, 'medium');
                    $title       = Titles\title($post->ID);
                    $excerpt     = Utils\excerpt($post->ID);

                    $output .= <<<EOT

                    <li>
                        <article>{$thumb}
                            <h3 class="title">{$title}</h3>
                            <div class="excerpt">{$excerpt}</div>
                        </article>
                    </li>
EOT;
                }
            }
       } else {
            $output .= <<<EOT

            <div class="{$layout}-wrapper">
                <ul class="article-list"></ul>
                <p class="load-p-control">
                    <button type="button">
                        <div class="spinner">
                          <div class="bounce1"></div>
                          <div class="bounce2"></div>
                          <div class="bounce3"></div>
                        </div>
                        {$load_p_mesg}
                    </button>
                </p>
            </div>
EOT;
       }

    } elseif ($layout === 'contact') {

       $blogname = get_bloginfo('name');
       $phone_trim = preg_replace('/[^0-9]+/', '', $row['phone']);
       $contact_form = sage_get_gravity_form($row['contact_form']);

       $output .= <<<EOT

        <div class="{$layout}-wrapper">
            <div class="column">
                <a href="#document_top" class="nav-link logo"><img src="{$row['logo']}" alt="{$blogname}"/></a>
                <ul class="contacts">
                    <li>
                        <span class="address">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#point"></use></svg>
                            {$row['address']}
                        </span>
                    </li>
                    <li>
                        <a href="mailto:{$row['email']}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#sign"></use></svg>
                            {$row['email']}
                        </a>
                    </li>
                    <li>
                        <a href="tel:+47{$phone_trim}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#phone"></use></svg>
                            (+47) {$row['phone']}
                        </a>
                    </li>
                </ul>
                <ul class="socials">
                    <li>
                        <a href="{$row['facebook']}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#facebook"></use></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{$row['twitter']}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#twitter"></use></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{$row['vimeo']}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#vimeo"></use></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{$row['youtube']}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#youtube"></use></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{$row['pinterest']}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" ><use xlink:href="#pinterest"></use></svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="column">{$contact_form}</div>
        </div>
EOT;

   }

   if ($numberposts) {
      return $output;
   } else {

      $content_style = $content_color ? sprintf('style="color: %s"', $content_color) : '';

      $section_title_html = ($section_title && $layout !== 'price' ) ? sprintf('<h2 class="section-title" style="color: %s">%s</h2>', $title_color, $section_title) : '';

      $down_icon_color = Utils\sage_complementary_color($bg_color);

      return <<<EOT
          <div id="{$section_id}" class="section {$layout}" style="{$section_style}">
              <div class="container" {$content_style}>
                  {$section_title_html}
                  {$output}
              </div>
              <a href="#{$scroll_target}" class="nav-link scroll-btn" style="color: {$down_icon_color}; background-color: {$bg_color}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" style="fill: {$down_icon_color}"><use xlink:href="#down"></use></svg>
              </a>
          </div>
EOT;
    }

}



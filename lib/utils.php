<?php

namespace Roots\Sage\Utils;

use Roots\Sage\ACFmodules;


/**
 * Custom Excerpt
 */
function excerpt($id=false, $limit=30) {
  if ( isset( $post->ID ) && !$id ) $id = $post->ID;

  $content = explode(' ', get_post_field('post_content', $id), $limit);

  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content);
    $content .= sprintf('&hellip; <a class="more" href="%s">%s</a>',
        get_the_permalink($id),
        __('Les mer', 'sage')
    );
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}


/**
 * Get Theme Options Fields
 */

function sage_get_options() {

    if( function_exists('get_fields') ) {

        $options = get_fields('option');

        return $options;
    }
}

/**
 * Return a custom field stored by the Advanced Custom Fields plugin
 *
 * @global $post
 * @param str $key The key to look for
 * @param mixed $id The post ID (int|str, defaults to $post->ID)
 * @param mixed $default Value to return if get_field() returns nothing
 * @return mixed
 * @uses get_field()
 */

function sage_get_field( $key, $id=false, $format=true ) {

  global $post;

  $key = trim( filter_var( $key, FILTER_SANITIZE_STRING ) );
  $result = '';

  if ( function_exists( 'get_field' ) ) {
    if ( isset( $post->ID ) && !$id )
      $result = get_field( $key, $post->ID, $format);
    else
      $result = get_field( $key, $id, $format );

  }

  return $result;

}


/**
 * Shortcut for 'echo _get_field()'
 * @param str $key The meta key
 * @param mixed $id The post ID (int|str, defaults to $post->ID)
 * @param mixed $default Value to return if there's no value for the custom field $key
 * @return void
 * @uses _get_field()
 */

function sage_the_field( $key, $id=false ) {

  echo sage_get_field( $key, $id );

}


/**
 * Get a sub field of a Repeater field
 * @param str $key The meta key
 * @param mixed $default Value to return if there's no value for the custom field $key
 * @return mixed
 * @uses get_sub_field()
 */

function sage_get_sub_field( $key, $format=true ) {

    if ( function_exists( 'get_sub_field' ) &&  get_sub_field( $key ) ) {

        return get_sub_field( $key, $format );

    }
}


/**
 * Shortcut for 'echo _get_sub_field()'
 * @param str $key The meta key Value to return if there's no value for the custom field $key
 * @return void
 * @uses _get_sub_field()
 */

function sage_the_sub_field( $key, $format=true ) {

  echo sage_get_sub_field( $key, $format );

}


/**
 * Check if a given field has a sub field
 * @param str $key The meta key
 * @param mixed $id The post ID
 * @return bool
 * @uses has_sub_field()
 */

function sage_has_sub_field( $key, $id=false ) {

  if ( function_exists('has_sub_field') )

    return has_sub_field( $key, $id );

  else

    return false;

}

/**
 * Get the excerpt
 * @param mixed $post_id The post ID (int|str, defaults to $post->ID)
 * @return mixed
 * @uses get_the_excerpt()
 * @uses get_post()
 */

function sage_get_the_excerpt($post_id) {
    global $post;
    $save_post = $post;

    if ( isset( $post->ID ) && !$post_id )
        $post = get_post($post->ID);
    else
        $post = get_post($post_id);

    $output = get_the_excerpt();
    $post = $save_post;

    return $output;
}


/**
 * Creates flexible content instanse
 * @param mixed $name Flexible content name
 * @return 'string'
 * @uses sage_init_flexible_content( $name )
 */

function sage_flexible_content() {

    $output = '';
    $field_name = 'content_rows';
    $field_data = sage_get_field( $field_name );

    // check if the flexible content field exists
    if( $field_data ){

        // loop through the rows of data
        $i = 0;
        foreach ( $field_data as $field ) :

            // pass next sibling id
            $index                    = ++$i;
            $next_sibling             = isset($field_data[$index]) ? $field_data[$index] : array();
            $field['next_sibling_id'] = array_key_exists('section_id', $next_sibling) ? $next_sibling['section_id'] : '-1';

            // collect layout content
            $output .= ACFmodules\sage_get_row_content($field);

        endforeach;


    } else {

        // return the content otherwise
        $output .= sprintf('<div class="container hentry">%s</div>', apply_filters('the_content', get_the_content()));

    }

    echo $output;
}


/**
 * Adjust brightness
 * @param string $hex Hex formatted color
 * @param number $steps Hex formatted color
 * @return 'string'
 * @uses sage_adjust_brightness( '#9d9d9d', 100 );
 */

function sage_adjust_brightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}


/**
 * Get closest color name
 * @param string $rgb Hex formatted color
 * @return 'string'
 * @uses sage_closest_color( '#b2b2b2' )
 */

function sage_closest_color($hex) {
    // these are not the actual rgb values
    $colors = array('WHITE' => '#FFFFFF', 'BRAND' => '#E5921B', 'BLACK' => '#000000');

    $deviation = PHP_INT_MAX;
    $closestColor = "";
    foreach ($colors as $name => $rgbColor) {
      $diff = sage_color_diff($rgbColor, $hex);
        if ( $diff < $deviation) {
            $deviation = $diff;
            $closestColor = $name;
        }

    }
    return $closestColor;

}


/**
 * Get two hex colors diff
 * @param string $hex1 Hex formatted color
 * @param string $hex2 Hex formatted color
 * @return 'number'
 * @uses sage_color_diff( '#9d9d9d', '#a3a3a3' )
 */

function sage_color_diff($hex1, $hex2) {
    $rgb1 = sage_hex2rgb($hex1);
    $rgb2 = sage_hex2rgb($hex2);

    return abs($rgb1[0] - $rgb2[0]) + abs($rgb1[1] - $rgb2[1]) + abs($rgb1[2] - $rgb2[2]) ;
}


/**
 * Convert hex to rgb color
 * @param string $hex Hex formatted color
 * @return 'array'
 * @uses sage_hex2rgb( '#9d9d9d' )
 */

function sage_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}


/**
 * Get complementary color
 * @param string $hex Hex formatted color
 * @return 'string'
 * @uses sage_complementary_color( '#9d9d9d' )
 */

function sage_complementary_color($hex) {
  $output = '';
  $color_name = sage_closest_color($hex);

  if ($color_name === 'WHITE') {
    $output = sage_adjust_brightness($hex, -50);

  } elseif ($color_name === 'BLACK') {
    $output = sage_adjust_brightness($hex, 100);

  } elseif ($color_name === 'BRAND') {
    $output = sage_adjust_brightness($hex, 50);

  }

  return $output;

}

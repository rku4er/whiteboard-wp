<?php

namespace Roots\Sage\Utils;


/**
 * Custom Excerpt
 */
function excerpt($limit=40) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'&hellip; <a class="more" href="' . get_permalink() . '">' . __('Read More', 'sage') . '</a>';
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


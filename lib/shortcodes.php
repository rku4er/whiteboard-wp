<?php

namespace Roots\Sage\Shortcodes;

use Roots\Sage\Extras;

/*
 * Row
 */
function sage_row( $args, $content = null ) {
  $defaults = array ();
  $args = wp_parse_args( $args, $defaults );

  array_walk($args, function(&$a, $b) { $a = "$b: $a"; });
  $style = !empty($args) ? sprintf('style="%s"', implode('; ', $args)) : '';

  return apply_filters('the_content', sprintf('<div class="flex-row" %s>%s</div>', $style, $content));
}

add_shortcode( 'row', __NAMESPACE__ .'\\sage_row' );


/*
 *Column
 */
function sage_col( $args, $content = null ) {
  $defaults = array ();
  $args = wp_parse_args( $args, $defaults );

  array_walk($args, function(&$a, $b) { $a = "$b: $a"; });
  $style = !empty($args) ? sprintf('style="%s"', implode('; ', $args)) : '';

  return apply_filters('the_content', sprintf('<div class="flex-col" %s>%s</div>', $style, $content));
}

add_shortcode( 'col', __NAMESPACE__ .'\\sage_col' );

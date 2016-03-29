<?php

namespace Roots\Sage\ACFmodules;

use Roots\Sage\Utils;
use Roots\Sage\Titles;


/**
 *  Header Logo
 */

function sage_header_logo() {

    $options = Utils\sage_get_options();
    $logo_image = $options['logo'];

    if($logo_image) {

        $logo_html = sprintf('<img src="%s" alt="%s">',
            $logo_image['url'],
            get_bloginfo('name')
        );


        printf('<div class="navbar-brand-wrapper"><a class="%s" href="%s">%s</a></div>',
            'navbar-brand withoutripple',
            esc_url(home_url('/')),
            $logo_html
        );

    }
}


/**
 *  Header position class
 */

function sage_navbar_position_class() {

    $options = Utils\sage_get_options();

    $navbar_position = $options['header_navbar_position'];
    $navbar_class = $navbar_position ? 'navbar-'. $navbar_position : 'navbar-static-top';

    echo $navbar_class;

}


/**
 * Newsletter
 */

function sage_newsletter() {

    $options = Utils\sage_get_options();
    $form_object = $options['newsletter_form'];

    if( $form_object ): ?>
        <div class="newsletter">
            <?php echo do_shortcode('[gravityform id="' . $form_object->id . '" title="false" description="false" ajax="true" tabindex="999"]'); ?>
        </div>
    <?php endif;
}


/**
 * Footer info
 */

function sage_info() {
    $options = Utils\sage_get_options();

    if($options['content-info']) printf('<div class="info">%s</div>',
        $options['content-info']
    );
}


/**
 * Gravity form
 */

function sage_gravity_form($gform_field) {

    $form_object = Utils\sage_get_field($gform_field);

    if( $form_object ) {
        echo do_shortcode('[gravityform id="' . $form_object->id . '" title="true" description="true" ajax="true"]');
    }
}

<?php use Roots\Sage\Utils; ?>

<?php
    if( have_rows('content_rows') ){
        Utils\sage_init_flexible_content( 'content_rows' );
    } else {
        the_content();
    }
?>

<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>')); ?>

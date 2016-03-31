<?php use Roots\Sage\Utils; ?>

<?php Utils\sage_flexible_content(); ?>

<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>')); ?>

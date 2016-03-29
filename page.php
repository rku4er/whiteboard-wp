<?php while (have_posts()) : the_post(); ?>
    <div class="container-fluid">
        <?php get_template_part('templates/content', 'page'); ?>
    </div>
<?php endwhile; ?>

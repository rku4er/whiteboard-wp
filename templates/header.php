<?php use Roots\Sage\ACFmodules; ?>

<nav class="navbar <?php ACFmodules\sage_navbar_position_class(); ?>" role="navigation">

    <div class="container">
        <div class="row">
            <?php ACFmodules\sage_navbar_logo(); ?>
            <?php ACFmodules\sage_navbar_phone(); ?>
            <?php ACFmodules\sage_navbar_button(); ?>
        </div>
    </div>

    <div class="navbar-nav-wrapper">
        <div class="container">
            <?php ACFmodules\sage_navbar_menu_toggler('nav-menu'); ?>
            <?php ACFmodules\sage_navbar_menu('nav-menu'); ?>
        </div>
    </div>

</nav>

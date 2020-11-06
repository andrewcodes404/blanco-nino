<nav>

    <div class="nav-mob-cont">

        <div class="nav-mob-logo">
            <a href="<?php echo get_home_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/images/graphics/logo-nav-mobile.png" alt="">
            </a>
        </div>

        <div class="hamburger-cont">
            <!-- font-awesome class is added in app.js -->
            <i class="menu-btn fas "></i>
        </div>

    </div>

    <!-- <div class="nav-menu-desktop <?php if (is_front_page()) : echo 'nav-home';
                                    else : echo 'nav-all-else';
                                    endif; ?> "> -->



    <div class="nav-menu-desktop  nav-home">

        <?php
        $menuParameters = array(
            'theme_location' => 'nav-menu-left',
            'container' => false,
            'items_wrap' => '%3$s',
        );

        echo strip_tags(wp_nav_menu($menuParameters), '<a>');
        ?>


        <!-- <div class="nav-logo <?php if (is_front_page()) : echo 'nav-logo-lrg';
                                else : echo 'nav-logo-sml';
                                endif; ?> "> -->

        <div class="nav-logo nav-logo-lrg">



            <a href="<?php echo get_home_url(); ?>">
                <!-- <img src="<?php echo get_template_directory_uri(); ?>/images/graphics/<?php if (is_front_page()) : echo 'logo-nav-home.png';
                                                                                        else : echo 'logo-nav.png';
                                                                                        endif; ?> "
                        alt="Blanco Nino Logo"> -->

                <img src="<?php echo get_template_directory_uri(); ?>/images/graphics/logo-nav-home.png"
                    alt="Blanco Nino Logo">
            </a>
        </div>

        <?php
        $menuParameters = array(
            'theme_location' => 'nav-menu-right',
            'container' => false,
            'items_wrap' => '%3$s',
        );

        echo strip_tags(wp_nav_menu($menuParameters), '<a>');
        ?>

    </div>

    <div class="nav-menu-mobile">
        <?php
        $menuParameters = array(
            'theme_location' => 'nav-menu-mobile',
            'container' => false,
            'echo' => false,
            'items_wrap' => '%3$s',
            'depth' => 0,
        );

        echo strip_tags(wp_nav_menu($menuParameters), '<a>');
        ?>
    </div>
</nav>
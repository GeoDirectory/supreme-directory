<article <?php post_class(); ?>>
    <?php
        if ((function_exists('is_buddypress') && !is_buddypress()) || !function_exists('is_buddypress')) { ?>


            <header>

                <div class="featured-area">
                    <div class="featured-img" <?php
                    if (has_post_thumbnail()) { // check if the post has a Post Thumbnail assigned to it.
                        $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                    }else{
                        $full_image_url[0] = SD_DEFAULT_FEATURED_IMAGE;
                    }
                        ?> style="background-image: url(<?php echo $full_image_url[0]; ?>);" <?php
                    ?>>

                    </div>
                    <div class="header-wrap">
                        <?php
                        if (is_singular()) {
                            ?>
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        <?php
                        } else {
                            ?>
                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php
                        }
                        if (get_post_meta(get_the_ID(), 'subtitle', true)) {
                            echo '<div class="entry-subtitle">' . get_post_meta(get_the_ID(), 'subtitle', true) . '</div>';
                        }
                        ?>

                        <?php if (is_front_page()) {
                            echo do_shortcode('[gd_advanced_search]');
                            echo do_shortcode('[gd_popular_post_category category_limit=5]');
                            echo '<div class="home-more"  id="sd-home-scroll" ><a href="#sd-home-scroll"><i class="fa fa-chevron-down"></i></a></div>';
                        }
                        ?>
                    </div>
                </div>
            </header> <?php
    } ?>
    <div class="container" id="home-scroll">
        <div class="entry-content entry-summary">
            <?php
            global $more;
            $more = 0;
            if (is_singular()) {
                the_content();
            } else {
                directory_theme_post_thumbnail();
                the_excerpt();
            }
            ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'supreme-directory') . '</span>',
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
            ));
            ?>
        </div>
        <footer class="entry-footer">
            <?php directory_theme_entry_meta(); ?>
            <?php edit_post_link(__('Edit', 'supreme-directory'), '<span class="edit-link">', '</span>'); ?>
        </footer>
    </div>
</article>
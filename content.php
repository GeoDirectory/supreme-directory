<?php global $dt_blog_sidebar_position,$sd_sidebar_class;?>
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

        <?php
        if($dt_blog_sidebar_position=='left'){?>
        <div class="sd-sidebar sd-sidebar-left">
            <div class="sidebar blog-sidebar page-sidebar">
                <?php if(is_page()){get_sidebar('pages');}else{get_sidebar();}?>
            </div>
        </div>
        <?php }?>

        <div class="entry-content entry-summary <?php echo $sd_sidebar_class;?>">
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
            <footer class="entry-footer">
                <?php directory_theme_entry_meta(); ?>
                <?php edit_post_link(__('Edit', 'supreme-directory'), '<span class="edit-link">', '</span>'); ?>



                <?php
                if(!is_page()){
                    // Previous/next post navigation.
                    the_post_navigation(array(
                        'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next', 'supreme-directory') . '</span> ' .
                            '<span class="screen-reader-text">' . __('Next post:', 'supreme-directory') . '</span> ' .
                            '<span class="post-title">%title</span>',
                        'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous', 'supreme-directory') . '</span> ' .
                            '<span class="screen-reader-text">' . __('Previous post:', 'supreme-directory') . '</span> ' .
                            '<span class="post-title">%title</span>',
                    )); 
                }



                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) : ?>
                <div class="comments-container">
                    <?php comments_template(); ?>
                </div>
                    <?php endif;
                ?>




            </footer>
        </div>

        <?php
        if($dt_blog_sidebar_position=='right'){?>
            <div class="sd-sidebar sd-sidebar-right">
                <div class="sidebar blog-sidebar page-sidebar">
                    <?php if(is_page()){get_sidebar('pages');}else{get_sidebar();} ?>
                </div>
            </div>
        <?php }?>

    </div>
</article>
<?php global $dt_blog_sidebar_position,$sd_sidebar_class,$post;?>
<article <?php post_class(); ?>>
    <?php get_template_part('content-featured-area');?>
    <div class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?>" id="home-scroll">

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
            if(get_post_meta($post->ID, '_sd_featured_area', true) == 'remove'){
                ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <?php
            }

            if (is_singular() || ( function_exists('is_bbpress') && is_bbpress() )) {
                the_content();
            } else {
                //directory_theme_post_thumbnail();
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
                global $post;
                if(is_single() && isset($post->post_type) && $post->post_type == 'post'){
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
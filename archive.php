<?php get_header(); ?>
<?php get_template_part('content-featured-area');?>
<section class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-3">

<div class="row">
            <?php if (!have_posts()) : ?>
                <div class="alert-error">
                    <p><?php _e('Sorry, no results were found.', 'supreme-directory'); ?></p>
                </div>
                <?php get_search_form(); ?>
            <?php endif; ?>
            <?php
            while (have_posts()) : the_post();

                // Include the page content template.
                get_template_part('content-blog');

                // End the loop.
            endwhile;

            // Previous/next page navigation.
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'supreme-directory'),
                'next_text' => __('Next', 'supreme-directory'),
            ));
            ?>
        </div>
</section>

<?php get_footer(); ?>
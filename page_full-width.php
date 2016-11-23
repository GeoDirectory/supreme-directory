<?php
/**
 * Template Name: Full Width Page
 *
 * @package Directory_Starter
 * @since 1.0.4
 */
get_header();

do_action('dt_page_before_main_content');

global $dt_blog_sidebar_position,$sd_sidebar_class;
$dt_blog_sidebar_position = '';
$sd_sidebar_class = '';
?>
    <div class="sd-container">
        <div class="content-box content-single">
            <?php if (!have_posts()) : ?>
                <div class="alert alert-warning">
                    <?php _e('Sorry, no results were found.', 'supreme-directory'); ?>
                </div>
                <?php get_search_form(); ?>
            <?php endif; ?>
            <?php
            while (have_posts()) : the_post();

                // Include the page content template.
                get_template_part('content');

                // End the loop.
            endwhile;
            ?>
        </div>
    </div>
<?php get_footer(); ?>
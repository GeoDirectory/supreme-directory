<?php get_header();

do_action('dt_page_before_main_content');

global $dt_blog_sidebar_position,$sd_sidebar_class;
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
$sd_sidebar_class = 'sidebar-active sidebar-'.$dt_blog_sidebar_position;
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
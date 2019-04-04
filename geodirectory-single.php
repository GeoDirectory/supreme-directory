<?php
/**
 * Template Name: GeoDirectory Single
 *
 * @package Supreme_Directory
 * @since 2.0.0
 */

get_header();

do_action('dt_page_before_main_content');

global $dt_blog_sidebar_position,$sd_sidebar_class;
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
$sd_sidebar_class = 'sidebar-active sidebar-'.$dt_blog_sidebar_position;
?>
<?php get_template_part('content-featured-area');?>

    <div class="fullwidth-sidebar-container">
        <div class="sidebar top-sidebar">
            <?php dynamic_sidebar('sidebar-gd-top'); ?>
        </div>
    </div>

    <div class="fullwidth-sidebar-container sd-details-top-section">
        <div class="sidebar top-sidebar">
            <?php get_template_part( 'template-parts/header/header', 'single-top' ); ?>
        </div>
    </div>

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
                get_template_part('content-geodirectory');

                // End the loop.
            endwhile;
            ?>
        </div>
    </div>

    <div class="fullwidth-sidebar-container">
        <div class="sidebar bottom-sidebar">
            <?php dynamic_sidebar('sidebar-gd-bottom'); ?>
        </div>
    </div>
<?php get_footer(); ?>
<?php
/**
 * Template Name: GeoDirectory Archive
 *
 * @package Supreme_Directory
 * @since 2.0.0
 */
add_filter('body_class', 'sd_add_gd_archive_class');

function sd_add_gd_archive_class($classes) {
    $classes[] = 'geodir-fixed-archive';
    return $classes;
}
get_header();

do_action('dt_page_before_main_content');

global $dt_blog_sidebar_position,$sd_sidebar_class;
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
$sd_sidebar_class = 'sidebar-active sidebar-'.$dt_blog_sidebar_position;
?>

    <div class="container-fluid">
            <?php if (!have_posts()) :
                // Include the page content template.
                get_template_part('content-geodirectory-archive');
            endif;

            while (have_posts()) : the_post();

                // Include the page content template.
                get_template_part('content-geodirectory-archive');

                // End the loop.
            endwhile;
            ?>
    </div>

<?php get_footer(); ?>
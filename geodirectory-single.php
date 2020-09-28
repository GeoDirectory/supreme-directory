<?php get_header(); ?>

<?php do_action('dt_single_before_main_content'); ?>

<?php
$dt_enable_blog_sidebar = esc_attr(get_theme_mod('dt_enable_blog_sidebar', DT_ENABLE_BLOG_SIDEBAR));
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));

if ($dt_enable_blog_sidebar == '1') {
    $content_class = 'col-lg-8 col-md-9 border-right pt-3';
} else {
    $content_class = 'col-lg-12';
}
?>
<?php get_template_part('content-featured-area');



?>

    <div class="fullwidth-sidebar-container">
        <div class="sidebar top-sidebar">
            <?php dynamic_sidebar('sidebar-gd-top'); ?>
        </div>
    </div>

    <div class="fullwidth-sidebar-container sd-details-top-section container-fluid border-bottom">
        <div class="sidebar top-sidebar container">
            <?php get_template_part( 'template-parts/header/header', 'single-top' ); ?>
        </div>
    </div>

    <section class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-0">
        <div class="row">
            <?php if ($dt_enable_blog_sidebar == '1' && $dt_blog_sidebar_position == 'left') { ?>
                <div class="col-lg-4 col-md-3 pt-3">
                    <div class="sidebar blog-sidebar page-sidebar">
                        <?php dynamic_sidebar('sidebar-gd');?>
                    </div>
                </div>
            <?php } ?>
            <div class="<?php echo $content_class; ?>">
                <div class="content-single">
                    <?php if (!have_posts()) : ?>
                        <div class="alert-error">
                            <p><?php _e('Sorry, no results were found.', 'directory-starter'); ?></p>
                        </div>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                    <?php
                    while ( have_posts() ) : the_post();

                        // Include the page content template.
                        get_template_part('content-geodirectory');

                        // End the loop.
                    endwhile;
                    ?>
                </div>
            </div>
            <?php if ($dt_enable_blog_sidebar == '1' && $dt_blog_sidebar_position == 'right') { ?>
                <div class="col-lg-4 col-md-3 pt-3">
                    <div class="sidebar blog-sidebar page-sidebar">
                        <?php dynamic_sidebar('sidebar-gd');?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <div class="fullwidth-sidebar-container">
        <div class="sidebar bottom-sidebar">
            <?php dynamic_sidebar('sidebar-gd-bottom'); ?>
        </div>
    </div>


<?php do_action('dt_single_after_main_content'); ?>

<?php get_footer(); ?>
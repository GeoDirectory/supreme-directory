<?php get_header();

do_action('dt_page_before_main_content');

global $dt_blog_sidebar_position,$sd_sidebar_class;
$dt_enable_blog_sidebar = esc_attr(get_theme_mod('dt_enable_blog_sidebar', DT_ENABLE_BLOG_SIDEBAR));
if($dt_enable_blog_sidebar){
    $dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
    $sd_sidebar_class = 'sidebar-active sidebar-'.$dt_blog_sidebar_position;
}else{
    $dt_blog_sidebar_position = '';
    $sd_sidebar_class = '';
}

?>
    <div class="sd-container">
        <div class="content-box content-single">
            <?php if (!have_posts()) : ?>
                <div class="alert-error">
                    <p><?php _e('Sorry, no results were found.', 'supreme-directory'); ?></p>
                </div>
                <?php get_search_form(); ?>
            <?php endif; ?>
            <?php
            while (have_posts()) :
            the_post();

            // Include the page content template.
            get_template_part('content');

            endwhile;
            ?>
        </div>
    </div>
<?php get_footer(); ?>
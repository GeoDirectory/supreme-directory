<?php get_header();

do_action('dt_page_before_main_content');

$dt_enable_woo_sidebar = esc_attr(get_theme_mod('dt_enable_woo_sidebar', DT_ENABLE_WOO_SIDEBAR));
if($dt_enable_woo_sidebar){
    $dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
    $sd_sidebar_class = 'sidebar-active sidebar-'.$dt_blog_sidebar_position;
}else{
    $dt_blog_sidebar_position = '';
    $sd_sidebar_class = '';
}

?>
<?php get_template_part('content-featured-area');?>
        <div class="container sd-container" style="overflow: hidden;">
            <div class="content-box content-single">
                <?php if (!have_posts()) : ?>
                    <div class="alert-error">
                        <p><?php _e('Sorry, no results were found.', 'supreme-directory'); ?></p>
                    </div>
                    <?php get_search_form(); ?>
                <?php endif; ?>
                <?php
                if($dt_blog_sidebar_position=='left'){?>
                    <div class="sd-sidebar sd-sidebar-left">
                        <div class="sidebar blog-sidebar page-sidebar">
                            <?php dynamic_sidebar('sidebar-wc'); ?>
                        </div>
                    </div>
                <?php }?>
                <div class="entry-content <?php echo $sd_sidebar_class;?>">
                <?php
                woocommerce_content();
                ?>
                </div>
                <?php
                if($dt_blog_sidebar_position=='right'){?>
                    <div class="sd-sidebar sd-sidebar-right">
                        <div class="sidebar blog-sidebar page-sidebar">
                            <?php dynamic_sidebar('sidebar-wc'); ?>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
<?php get_footer(); ?>
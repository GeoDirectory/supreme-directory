<?php
/**
 * Template Name: Full Width Page
 *
 * @package Directory_Starter
 * @since 1.0.4
 */


get_header();

do_action('dt_page_before_main_content');
$sidebar = 'pages';
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));
?>
<?php get_template_part('content-featured-area');?>
    <section class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-0">

        <div class="row">
            <div class="col pt-3">
                <div class="content-single">
                    <?php if (!have_posts()) : ?>
                        <div class="alert alert-warning">
                            <?php _e('Sorry, no results were found.', 'directory-starter'); ?>
                        </div>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                    <?php
                    while ( have_posts() ) : the_post();

                        // Include the page content template.
                        get_template_part( 'template-parts/content/content' );

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                        // End the loop.
                    endwhile;
                    ?>
                </div>
            </div>
        </div>
    </section>


<?php do_action('dt_page_after_main_content'); ?>

<?php get_footer(); ?>
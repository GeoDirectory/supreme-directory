<?php get_header(); ?>
    <div class="featured-area">
        <div class="featured-img" <?php
        $page_for_posts = get_option('page_for_posts');
        if (has_post_thumbnail($page_for_posts)) { // check if the post has a Post Thumbnail assigned to it.
            $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($page_for_posts), 'full');
        }else{
            $full_image_url[0] = SD_DEFAULT_FEATURED_IMAGE;
        }
            ?> style="background-image: url('<?php echo esc_url($full_image_url[0]); ?>');" <?php
        ?>>

        </div>
        <div class="header-wrap">

            <h1 class="entry-title">
                <?php
                if ( is_search() ) {
                    echo __('Your Search Results for ', 'supreme-directory').get_search_query(false);
                } else {
                    echo get_the_title($page_for_posts);
                }
                ?>
            </h1>
            <?php if (get_post_meta($page_for_posts, 'subtitle', true)) {
                echo '<div class="entry-subtitle">' . get_post_meta($page_for_posts, 'subtitle', true) . '</div>';
            } ?>

        </div>
    </div>

    <div class="container">
        <div class="content-box content-single">
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
    </div>
<?php get_footer(); ?>

<?php get_header(); ?>
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

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) : ?>
            <div class="container">
                <?php comments_template(); ?>
                <?php endif;

                // Previous/next post navigation.
                the_post_navigation(array(
                    'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next', 'supreme-directory') . '</span> ' .
                        '<span class="screen-reader-text">' . __('Next post:', 'supreme-directory') . '</span> ' .
                        '<span class="post-title">%title</span>',
                    'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous', 'supreme-directory') . '</span> ' .
                        '<span class="screen-reader-text">' . __('Previous post:', 'supreme-directory') . '</span> ' .
                        '<span class="post-title">%title</span>',
                ));

                // End the loop.
                endwhile;
                ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
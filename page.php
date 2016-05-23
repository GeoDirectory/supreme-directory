<?php get_header(); ?>
    <div class="sd-container">
        <div class="content-box content-single">
            <?php if (!have_posts()) : ?>
                <div class="alert alert-warning">
                    <?php _e('Sorry, no results were found.', 'directory-starter'); ?>
                </div>
                <?php get_search_form(); ?>
            <?php endif; ?>
            <?php
            while (have_posts()) : the_post();

                // Include the page content template.
                get_template_part('content');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) : ?>
                    <div class="container">
                        <?php comments_template(); ?>
                    </div>
                <?php endif;

                // End the loop.
            endwhile;
            ?>
        </div>
    </div>
<?php get_footer(); ?>
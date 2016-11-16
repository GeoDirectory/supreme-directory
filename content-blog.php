<article <?php post_class(); ?>>
    <header>

        <a class="featured-area" href="<?php the_permalink(); ?>">
	<span class="featured-img" <?php
    if (has_post_thumbnail()) { // check if the post has a Post Thumbnail assigned to it.
        $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    } else {
        $full_image_url[0] = SD_DEFAULT_FEATURED_IMAGE;
    }
    ?> style="background-image: url('<?php echo esc_url($full_image_url[0]); ?>');" <?php
    ?>>
	
	</span>
        </a>
    </header>
    <div class="container">
        <div class="entry-content entry-summary">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </div>
        <footer class="entry-footer">
            <?php supreme_entry_meta(); ?>
        </footer>
    </div>
</article>
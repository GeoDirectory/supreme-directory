<?php get_header();

$full_image_url = SD_DEFAULT_FEATURED_IMAGE;
?>

    <div class="sd-container">
        <div class="content-box content-single">

            <article <?php post_class(); ?>>

                <header>

                    <div class="featured-area">
                        <div class="featured-img" style="background-image: url(<?php echo $full_image_url; ?>);"></div>

                        <div class="header-wrap">

                            <?php echo get_avatar(get_the_author_meta('ID'), 120, '', get_the_author_meta('display_name')); ?>
                            <h1 class="entry-title"><?php $author_obj = $wp_query->get_queried_object();
                                echo $author_obj->nickname; ?></h1>

                        </div>
                    </div>
                </header>
                <div class="container" id="home-scroll">
                    <div class="entry-content entry-summary">

                        <?php
                        // user profile text
                        echo "<h1>".ucfirst(esc_attr(sprintf( __("%s's Profile", 'directory-starter'), $author_obj->user_nicename )))."</h1>";

                        // user listings
                            echo "<h3>".__("Listings", "directory-starter")."</h3>";
                            geodir_user_show_listings($author_obj->ID,'link');

                        // user favs
                        $fav_count = geodir_user_favourite_listing_count($author_obj->ID);
                        if(!empty($fav_count )){
                            echo "<h3>".__("Favorites", "directory-starter")."</h3>";
                            geodir_user_show_favourites($author_obj->ID,'link');
                        }
                        ?>


                        <h3><?php echo ucfirst($author_obj->nickname); ?>
                            's <?php _e("Reviews", "directory-starter"); ?> (coming soon)</h3>

                    </div>
                </div>
            </article>


        </div>
    </div>

<?php get_footer(); ?>
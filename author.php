<?php get_header(); ?>

    <div class="sd-container">
        <div class="content-box content-single">

            <article <?php post_class(); ?>>

                <header>

                    <div class="featured-area">
                        <div class="header-wrap">

                            <?php echo get_avatar(get_the_author_meta('ID'), 120, '', get_the_author_meta('display_name')); ?>
                            <h1 class="entry-title"><?php $author_obj = $wp_query->get_queried_object();
                                echo $author_obj->nickname; ?></h1>

                        </div>
                    </div>
                </header>
                <div class="container" id="home-scroll">
                    <div class="entry-content entry-summary">
                        <h3><?php echo ucfirst($author_obj->nickname); ?>
                            's <?php _e("Listings", "directory-starter"); ?></h3>

                        <h3><?php echo ucfirst($author_obj->nickname); ?>
                            's <?php _e("Favorites", "directory-starter"); ?></h3>

                        <h3><?php echo ucfirst($author_obj->nickname); ?>
                            's <?php _e("Reviews", "directory-starter"); ?></h3>

                    </div>
                </div>
            </article>


        </div>
    </div>

<?php get_footer(); ?>
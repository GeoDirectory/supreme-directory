<?php get_header();
$full_image_url = SD_DEFAULT_FEATURED_IMAGE;
?>

    <div class="sd-container">


                <header>

                    <div class="featured-area">
                        <div class="featured-img" style="background-image: url('<?php echo esc_url($full_image_url); ?>');"></div>

                        <div class="header-wrap">

                            <?php $author_obj = get_user_by( 'slug', get_query_var( 'author_name' ) );
                            echo get_avatar($author_obj->ID, 120, '', get_the_author_meta('display_name')); ?>
                            <h1 class="entry-title"><?php $author_obj = $wp_query->get_queried_object();
                                echo ucfirst(esc_attr(sprintf( __("%s's Profile", 'supreme-directory'), esc_attr($author_obj->display_name) )))?></h1>

                        </div>
                    </div>
                </header>

                <div class="container">

                    <div class="content-box content-archive content-author">


                        <?php

                        /**
                         * Output the author page content.
                         *
                         * @param Object $author_obj The author object.
                         */
                        do_action('sd_author_content',$author_obj);

                       
                        ?>


                </div>


        </div>
    </div>
<?php get_footer(); ?>
<?php global $dt_blog_sidebar_position,$sd_sidebar_class,$post;?>
<article <?php post_class(); ?>>
    <div class="container" id="home-scroll">

        <?php
        if($dt_blog_sidebar_position=='left'){?>
        <div class="sd-sidebar sd-sidebar-left" id="sd-sidebar-left">
            <div class="sidebar blog-sidebar page-sidebar">
                <?php echo do_shortcode('[gd_map width="100%" height="100vh" maptype="ROADMAP" zoom="0" map_type="auto"]');?>
            </div>
        </div>
        <?php }?>

        <div id="sd-archive-map" class="entry-content entry-summary <?php echo $sd_sidebar_class;?>" onscroll='jQuery(window).trigger("lookup");'>
            <?php

            // add the title if its not added in the featured area
            $post_id = $post->ID;
            if(function_exists('geodir_is_page') && geodir_is_page('single') && isset($post->post_type)){
                $page_id = geodir_cpt_template_page('page_details',$post->post_type);
                if($page_id){
                    $post_id = $page_id;
                }
            }
            $featured_type  = get_post_meta($post_id, '_sd_featured_area', true);
           // if($featured_type == 'remove' || 1==1){
                ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <?php
            //}


            global $more;
            $more = 0;
            if (is_singular() || ( function_exists('is_bbpress') && is_bbpress() )) {
                the_content();
            } else {
                directory_theme_post_thumbnail();
                the_excerpt();
            }
            ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'supreme-directory') . '</span>',
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
            ));
            ?>
            <footer class="entry-footer">
                <?php directory_theme_entry_meta(); ?>
                <?php edit_post_link(__('Edit', 'supreme-directory'), '<span class="edit-link">', '</span>'); ?>



                <?php
                global $post;

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) : ?>
                <div class="comments-container">
                    <?php comments_template(); ?>
                </div>
                    <?php endif;
                ?>

            </footer>
        </div>
        <script>
            function sd_maybe_set_archive_content_width(){
                if(typeof(Storage) !== "undefined"){
                    $width = localStorage.getItem('sd_archive_width');
                    $screen_width = screen.width;
                    if($width && $screen_width > 992){
                        if(document.body.classList.contains('sd-left-sidebar')) {
                            document.getElementById("sd-sidebar-left").style.width = $width+"px";
                        }else{
                            document.getElementById("sd-archive-map").style.width = $width+"px";
                        }
                    }
                }
            }
            sd_maybe_set_archive_content_width();

            // insert the mobile switch buttons if needed
            function sd_insert_mobile_archive_buttons(){
                var html = `
            <div class="sd-mobile-search-controls">
                <a class="dt-btn" id="showSearch" href="#">
                    <i class="fas fa-search"></i> <?php _e('SEARCH LISTINGS', 'supreme-directory');?></a>
                <a class="dt-btn" id="hideMap" href="#"><i class="fas fa-th-large">
                    </i> <?php _e('SHOW LISTINGS', 'supreme-directory'); ?></a>
                <a class="dt-btn" id="showMap" href="#"><i class="far fa-map">
                    </i> <?php _e('SHOW MAP', 'supreme-directory') ?></a>
			</div>
`;
                jQuery('#sd-archive-map').prepend(html);
            }


            sd_insert_mobile_archive_buttons();
        </script>

        <?php
        if($dt_blog_sidebar_position=='right'){?>
            <div class="sd-sidebar sd-sidebar-right" id="sd-sidebar-right">
                <div class="sidebar blog-sidebar page-sidebar">
                    <?php echo do_shortcode('[gd_map width="100%" height="100vh" maptype="ROADMAP" zoom="0" map_type="auto"]');?>
                </div>
            </div>
        <?php }?>

    </div>
</article>
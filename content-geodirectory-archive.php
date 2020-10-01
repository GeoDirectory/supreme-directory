<?php 
global $dt_blog_sidebar_position, $sd_sidebar_class, $post; 
$map_shortcode = apply_filters( 'sd_archive_gd_map_shortcode', '[gd_map width="100%" height="100vh" maptype="ROADMAP" zoom="0" map_type="auto"]' );
?>
<div class="row map-archive-container">

    <?php
    if($dt_blog_sidebar_position=='left'){?>
    <div class="sd-sidebar sd-sidebar-left col col-12 col-md-5 px-0 sd-archive-map" id="sd-sidebar-left">
        <div class="sidebar blog-sidebar page-sidebar sticky-top">
            <?php echo do_shortcode( $map_shortcode );?>
        </div>
    </div>
    <?php }?>

    <div id="sd-archive-map" class="col col-12 col-md-7 sd-archive-listings entry-content entry-summary <?php echo $sd_sidebar_class;?>" onscroll='jQuery(window).trigger("lookup");'>
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
            //directory_theme_post_thumbnail();
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

    <?php
    if($dt_blog_sidebar_position=='right'){?>
        <div class="sd-sidebar sd-sidebar-right sticky-sidebar col col-12 col-md-5 px-0 sd-archive-map d-none d-md-block" id="sd-sidebar-right">
            <div class="sidebar blog-sidebar page-sidebar sticky-top">
                <?php echo do_shortcode( $map_shortcode );?>
            </div>
        </div>
    <?php }?>

</div>
<script type="text/javascript">
    /* <![CDATA[ */
    function sd_maybe_set_archive_content_width(){
        if(typeof(Storage) !== "undefined"){
            $width = localStorage.getItem('sd_archive_width');
            $screen_width = screen.width;
            if($width && $screen_width > 992){
                if(document.body.classList.contains('sd-left-sidebar')) {
                    document.getElementById("sd-sidebar-left").style.width = $width+"px";
                    document.getElementById("sd-sidebar-left").style['max-width'] = $width+"px";
                    document.getElementById("sd-sidebar-left").style['flex-basis'] = "auto";
                    document.getElementById("sd-sidebar-left").classList.remove('col-md-5');
                    document.getElementById("sd-sidebar-left").classList.remove('col-12');
                    document.getElementById("sd-archive-map").classList.remove('col-md-7');
                    document.getElementById("sd-archive-map").classList.remove('col-12');
                }else{
                    document.getElementById("sd-archive-map").style.width = $width+"px";
                    document.getElementById("sd-archive-map").style['max-width'] = $width+"px";
                    document.getElementById("sd-archive-map").style['flex-basis'] = "auto";
                    document.getElementById("sd-archive-map").classList.remove('col-md-7');
                    document.getElementById("sd-archive-map").classList.remove('col-12');
                    document.getElementById("sd-sidebar-right").classList.remove('col-md-5');
                    document.getElementById("sd-sidebar-right").classList.remove('col-12');
                }
            }
        }
    }
    sd_maybe_set_archive_content_width();

    // insert the mobile switch buttons if needed
    function sd_insert_mobile_archive_buttons(){
        var html = '<div class="sd-mobile-search-controls d-block d-md-none col-12 mt-2 mb-2">' +
//            '<a class="btn btn-sm btn-primary" id="showSearch" href="#"><i class="fas fa-search"></i> <?php //_e('SEARCH LISTINGS', 'supreme-directory');?>//</a>' +
            '<a class="btn btn-sm btn-primary d-none" id="hideMap" href="#"><i class="fas fa-th-large"></i> <?php _e('SHOW LISTINGS', 'supreme-directory'); ?></a>' +
            '<a class="btn btn-sm btn-primary" id="showMap" href="#"><i class="far fa-map"></i> <?php _e('SHOW MAP', 'supreme-directory') ?></a>' +
            '</div>';
        if (!jQuery('#sd-archive-map .sd-mobile-search-controls').length) {
            jQuery('.map-archive-container').prepend(html);
        }
    }

    sd_insert_mobile_archive_buttons();
    /* ]]> */
</script>

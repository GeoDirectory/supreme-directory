<?php
global $preview, $gd_post;
do_action( 'sd-detail-details-before' ); ?>
<div class="sd-detail-details  <?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> ">
	<div class="row">
		<div class="sd-detail-author col col-md-2 text-center">
			<?php

			global $gd_post;
			$is_owned     = false;
			$author_link  = do_shortcode( '[gd_post_meta key="post_author" show="value"]' );
			$author_id   = isset( $gd_post->post_author ) ? absint( $gd_post->post_author ) : '0';
			$author_image = get_avatar( get_the_author_meta( 'email',$author_id ), 100, 'mm', '', array( 'class' => "author_avatar rounded-circle shadow" ) );


			if ( ! $author_link && function_exists( 'geodir_claim_show_claim_link' ) && geodir_claim_show_claim_link( $gd_post->ID ) ) {
				$author_name  = __( 'Claim Me', 'supreme-directory' );
				$author_link  = do_shortcode( '[gd_claim_post text="' . esc_attr( $author_name ) . '" output="button"]' );
				$author_image = '<img class="avatar avatar-default rounded-circle shadow" src="' . get_stylesheet_directory_uri() . "/images/none.png" . '"  height="100" width="100">';
			} else {
				$is_owned    = true;

				$author_name = get_the_author_meta( 'user_nicename', $author_id );
			}

			$author_name  = apply_filters( 'sd_detail_author_name', $author_name, $author_id );
			$author_image = apply_filters( 'sd_detail_entry_author', $author_image, $author_id );
			$author_link  = apply_filters( 'sd_detail_author_link', $author_link, $author_id );

			// verified owner
			if ( function_exists( 'geodir_claim_show_claim_link' ) && GeoDir_Claim_Post::is_claimed( $gd_post->ID ) ) {
				$is_owned     = true;
				?>
				<span class="fa-stack sd-verified-badge" title="<?php _e( 'Verified Owner', 'supreme-directory' ); ?>">
                                <i class="fas fa-circle fa-inverse"></i>
                                <i class="fas fa-check-circle"></i>
				</span>
				<?php
			}

			?>
			<div class="author-avatar mt-n5x mt-3">
				<?php echo $author_image; ?>
			</div>

			<div class="author-link">
				<?php echo $author_link; ?>
			</div>
			<?php

			// edit link
			if ( is_user_logged_in() && geodir_listing_belong_to_current_user() ) {
				echo "<span class=\"supreme-btn supreme-btn-small supreme-edit-btn\">" . do_shortcode( '[gd_author_actions hide_delete="1"]' ) . "</span>";
			}

			?>
		</div>
		<!-- sd-detail-author end -->
		<div class="sd-detail-info col mt-3">
			<?php
			$post_id = $post->ID;
			if(function_exists('geodir_is_page') && geodir_is_page('single') && isset($post->post_type)){
				$page_id = geodir_cpt_template_page('page_details',$post->post_type);
				if($page_id){
					$post_id = $page_id;
				}
			}
			$featured_type  = get_post_meta($post_id, '_sd_featured_area', true);

			// Title
			$title_extra_class = apply_filters( 'sd_detail_title_extra_class', "" );
			if($featured_type=='remove'){
				echo '<h1 class="sd-entry-title' . $title_extra_class . '">' . stripslashes( get_the_title() ).'</h1>';
			} else{
				echo '<h2 class="sd-entry-title' . $title_extra_class . '">' . stripslashes( get_the_title() ).'</h2>';
			}

			// Address
			$address_shortcode = '[gd_post_address show="icon-value" address_template="%%city%%, %%region%%, %%country%%" ]';
			$address_shortcode = apply_filters( 'sd_details_output_address_shortcode', $address_shortcode );
			$address = do_shortcode($address_shortcode);
			$sd_address = '<div class="sd-address">';
			$sd_address .= $address;
			$sd_address .= '</div>';
			echo apply_filters( 'sd_details_output_address', $sd_address );

			// Ratings
			$ratings_shortcode = apply_filters( 'sd_details_output_ratings_shortcode','[gd_post_rating ]');
			$ratings = do_shortcode($ratings_shortcode);
			$sd_ratings = '';//'<div class="sd-ratings">' . $ratings . '</div>';
			echo apply_filters( 'sd_details_output_ratings', $sd_ratings );

			// Social links
			$social_shortcodes = '[gd_post_badge key="facebook" condition="is_not_empty" icon_class="fab fa-facebook-f fa-fw" link="%%input%%" new_window="1" color=\'facebook\' alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="twitter" condition="is_not_empty" icon_class="fab fa-twitter fa-fw" link="%%input%%" new_window="1" bg_color="#2bb8e8" txt_color="#ffffff" alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="instagram" condition="is_not_empty" icon_class="fab fa-instagram fa-fw" link="%%input%%" new_window="1" bg_color="#a94999" txt_color="#ffffff" alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="website" condition="is_not_empty" icon_class="fas fa-link fa-fw" link="%%input%%" new_window="1" bg_color="#85a9b5" txt_color="#ffffff" alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="phone" condition="is_not_empty" icon_class="fas fa-phone fa-fw" link="%%input%%" badge="%%input%%" color=\'secondary\' alignment="left"]';
			$social_shortcode = apply_filters( 'sd_details_output_social_shortcode',$social_shortcodes);
			$sd_social = '<div class="sd-contacts clearfix mt-2">';
			$sd_social .= do_shortcode($social_shortcode);
			$sd_social .= '</div>';
			echo apply_filters( 'sd_details_output_social', $sd_social );

			do_action( 'sd_detail_before_cat_links' );

			// Categories
			$cat_shortcode = '[gd_categories title_tag="h4" post_type="0" hide_count="1" sort_by="count" max_level="1" max_count="all" max_count_child="all"]';
			$cat_shortcode = "[gd_categories title=''  post_type='0'  cpt_title='false'  title_tag='h6'  cpt_ajax='false'  filter_ids=''  hide_empty='true'  hide_count='false'  hide_icon='false'  use_image='false'  cpt_left='false'  sort_by='count'  max_level='1'  max_count='all'  max_count_child='all'  no_cpt_filter='false'  no_cat_filter='false'  design_type=''  card_padding_inside='1'  card_color=''  icon_color=''  icon_size=''  bg=''  mt=''  mr=''  mb='0'  ml=''  pt=''  pr=''  pb=''  pl=''  border=''  rounded=''  rounded_size=''  shadow='' ]";
			$cat_shortcode = apply_filters( 'sd_details_output_cat_links_shortcode',$cat_shortcode);
			$cat_links = '<div class="sd-detail-cat-links mt-3">';
			$cat_links .= do_shortcode($cat_shortcode);;
			$cat_links .= '</div><!-- sd-detail-cat-links end -->';
			echo apply_filters( 'sd_details_output_cat_links', $cat_links );

			?>
		</div> <!-- sd-detail-info end -->

		<div class="sd-detail-cta col col-md-3 mt-3">
			<?php
			// write a review
			if ( comments_open() ) {
				$review_button_text = __( "Write a Review", "supreme-directory" );
				$review_button_shortcode = '[gd_post_badge size="large" key="post_title" condition="is_not_empty"  link="#reviews" badge="' . $review_button_text . '" new_window="0" color="primary" alignment="center" css_class="gd-write-a-review-badge" size="h3" mb="2"]';
				$review_button = apply_filters( 'sd_details_output_review_button_shortcode', $review_button_shortcode );
				$review_button = do_shortcode( $review_button );
				echo apply_filters( 'sd_details_output_review_button', $review_button );
			}

			// send buttons
			$send_buttons = '';
			echo apply_filters( 'sd_details_output_send_buttons', $send_buttons );

			// fav
			$fav_html = do_shortcode('[gd_post_fav show=\'\'  icon=\'\'  icon_color_off=\'\'  icon_color_on=\'\'  type=\'badge\'  shadow=\'\'  color=\'gray\'  bg_color=\'\'  txt_color=\'\'  size=\'h3\'  alignment=\'block\'  position=\'\'  mt=\'\'  mr=\'\'  mb=\'2\'  ml=\'\'  list_hide=\'\'  list_hide_secondary=\'\' ]');
			echo apply_filters( 'sd_details_output_fav', $fav_html );


			// share args
			$share_html = '';
			$share_url = $preview ? '' : urlencode( get_the_permalink() );
			$share_title =  $preview ? '' :  urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );

			// share on facebook
			$share_html .= apply_filters( 'sd_details_output_share_facebook', do_shortcode( '[gd_post_badge size="large" key="post_title" condition="is_not_empty"  link="https://www.facebook.com/sharer.php?u='.esc_attr($share_url).'&t='.esc_attr($share_title).'" badge="' . esc_attr__( 'Share on Facebook', 'supreme-directory' ). '" new_window="1" color="facebook" alignment="center" css_class="gd-write-a-review-badge" size="h3" mb="2"]' ) );

			// share on twitter
			$share_html .= apply_filters( 'sd_details_output_share_twitter', do_shortcode( '[gd_post_badge size="large" key="post_title" condition="is_not_empty"  link="https://twitter.com/share?text='.esc_attr($share_title).'url='.esc_attr($share_url).'" badge="' . esc_attr__( 'Share on Twitter', 'supreme-directory' ). '" new_window="1" color="twitter" alignment="center" css_class="gd-write-a-review-badge" size="h3" mb="2"]' ) );

			$share_html = '';
			echo apply_filters( 'sd_details_output_share_links', $share_html );
			echo '</div><!-- sd-detail-cta end -->'; ?>
			<?php do_action( 'sd-detail-details-container-inner-after' ); ?>
		</div>
		<!-- container end -->
		<?php do_action( 'sd-detail-details-container-after' ); ?>
	</div><!-- sd-detail-details end -->
<?php do_action( 'sd-detail-details-after' ); ?>
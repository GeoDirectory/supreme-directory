<?php
global $preview, $gd_post;
do_action( 'sd-detail-details-before' ); ?>
<div class="sd-detail-details  <?php if ( isset( $extra_class ) ) {
	echo $extra_class;
} ?>">
	<div class="container">
		<div class="sd-detail-author">
			<?php

			global $gd_post;
			$is_owned     = false;
			$author_link  = do_shortcode( '[gd_post_meta key="post_author" show="value"]' );
			$author_id   = isset( $gd_post->post_author ) ? absint( $gd_post->post_author ) : '0';
			$author_image = get_avatar( get_the_author_meta( 'email',$author_id ), 100, 'mm', '', array( 'class' => "author_avatar" ) );


			if ( ! $author_link && function_exists( 'geodir_claim_show_claim_link' ) && geodir_claim_show_claim_link( $gd_post->ID ) ) {
				$author_name  = __( 'Claim Me', 'supreme-directory' );
				$author_link  = do_shortcode( '[gd_claim_post text="Claim Me" output="button"]' );
				$author_image = '<img src="' . get_stylesheet_directory_uri() . "/images/none.png" . '"  height="100" width="100">';
			} else {
				$is_owned    = true;

				$author_name = get_the_author_meta( 'user_nicename', $author_id );
			}

			$author_name  = apply_filters( 'sd_detail_author_name', $author_name );
			$author_image = apply_filters( 'sd_detail_entry_author', $author_image );
			$author_link  = apply_filters( 'sd_detail_author_link', $author_link );

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
			<div class="author-avatar">
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
		<div class="sd-detail-info">
			<?php

			// Title
			$title_extra_class = apply_filters( 'sd_detail_title_extra_class', "" );
			echo '<h1 class="sd-entry-title ' . $title_extra_class . '">' . stripslashes( get_the_title() ).'</h1>';

			// Address
			$address_shortcode = '[gd_post_address show="icon-value" address_template="%%city%%, %%region%%, %%country%%" alignment="left"]';
			$address_shortcode = apply_filters( 'sd_details_output_address_shortcode', $address_shortcode );
			$address = do_shortcode($address_shortcode);
			$sd_address = '<div class="sd-address">';
			$sd_address .= $address;
			$sd_address .= '</div>';
			echo apply_filters( 'sd_details_output_address', $sd_address );

			// Ratings
			$ratings_shortcode = apply_filters( 'sd_details_output_ratings_shortcode','[gd_post_rating alignment="left"]');
			$ratings = do_shortcode($ratings_shortcode);
			$sd_ratings = '<div class="sd-ratings">' . $ratings . '</div>';
			echo apply_filters( 'sd_details_output_ratings', $sd_ratings );

			// Social links
			$social_shortcodes = '[gd_post_badge key="facebook" condition="is_not_empty" icon_class="fab fa-facebook-f fa-fw" link="%%input%%" new_window="1" bg_color="#2b4be8" txt_color="#ffffff" alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="twitter" condition="is_not_empty" icon_class="fab fa-twitter fa-fw" link="%%input%%" new_window="1" bg_color="#2bb8e8" txt_color="#ffffff" alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="website" condition="is_not_empty" icon_class="fas fa-link fa-fw" link="%%input%%" new_window="1" bg_color="#85a9b5" txt_color="#ffffff" alignment="left"]';
			$social_shortcodes .= '[gd_post_badge key="phone" condition="is_not_empty" icon_class="fas fa-phone fa-fw" link="%%input%%" badge="%%input%%" new_window="1" bg_color="#ed6d61" txt_color="#ffffff" alignment="left"]';
			$social_shortcode = apply_filters( 'sd_details_output_social_shortcode',$social_shortcodes);
			$sd_social = '<div class="sd-contacts">';
			$sd_social .= do_shortcode($social_shortcode);
			$sd_social .= '</div>';
			echo apply_filters( 'sd_details_output_social', $sd_social );

			do_action( 'sd_detail_before_cat_links' );

			// Categories
			$cat_shortcode = '[gd_categories title_tag="h4" post_type="0" hide_count="1" sort_by="count" max_level="1" max_count="all" max_count_child="all"]';
			$cat_shortcode = apply_filters( 'sd_details_output_cat_links_shortcode',$cat_shortcode);
			$cat_links = '<div class="sd-detail-cat-links">';
			$cat_links .= do_shortcode($cat_shortcode);;
			$cat_links .= '</div><!-- sd-detail-cat-links end -->';
			echo apply_filters( 'sd_details_output_cat_links', $cat_links );

			?>
		</div> <!-- sd-detail-info end -->


		<div class="sd-detail-cta">
			<?php


			// write a review
			if(comments_open( )){
				//$review_button = '<a class="dt-btn" href="' . get_the_permalink() . '#reviews">' . __( 'Write a Review', 'supreme-directory' ) . '</a>';
				$review_button_text = __("Write a Review","supreme-directory");
				$review_button_shortcode = '[gd_post_badge size="large" key="post_title" condition="is_not_empty"  link="#reviews" badge="'.$review_button_text .'" new_window="0" bg_color="#ed6d61" txt_color="#ffffff" alignment="center"]';
				$review_button = apply_filters( 'sd_details_output_review_button_shortcode',$review_button_shortcode);
				$review_button = do_shortcode($review_button);
				echo apply_filters( 'sd_details_output_review_button', $review_button );
			}


			// send buttons
			$send_buttons = '';
			echo apply_filters( 'sd_details_output_send_buttons', $send_buttons );


			// fav
			$fav_html = do_shortcode('[gd_post_fav show="icon"]');
			echo apply_filters( 'sd_details_output_fav', $fav_html );

			ob_start();
			?>
			<ul class="sd-cta-favsandshare">
				<?php if ( ! $preview ) { ?>
					<li><a rel="nofollow" target="_blank"
					       title="<?php echo __( 'Share on Facebook', 'supreme-directory' ); ?>"
					       href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php urlencode( the_title() ); ?>"><i
								class="fab fa-facebook"></i></a></li>
					<li><a rel="nofollow" target="_blank"
					       title="<?php echo __( 'Share on Twitter', 'supreme-directory' ); ?>"
					       href="http://twitter.com/share?text=<?php echo urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) ); ?>&url=<?php echo urlencode( get_the_permalink() ); ?>"><i
								class="fab fa-twitter"></i></a></li>

				<?php } else { ?>
					<li><a rel="nofollow" target="_blank"
					       title="<?php echo __( 'Share on Facebook', 'supreme-directory' ); ?>"
					       href=""><i class="fab fa-facebook"></i></a></li>
					<li><a rel="nofollow" target="_blank"
					       title="<?php echo __( 'Share on Twitter', 'supreme-directory' ); ?>"
					       href=""><i class="fab fa-twitter"></i></a></li>
				<?php } ?>
			</ul>
			<?php

			$share_html = ob_get_clean();
			echo apply_filters( 'sd_details_output_share_links', $share_html );
			echo '</div><!-- sd-detail-cta end -->'; ?>
			<?php do_action( 'sd-detail-details-container-inner-after' ); ?>
		</div>
		<!-- container end -->
		<?php do_action( 'sd-detail-details-container-after' ); ?>
	</div><!-- sd-detail-details end -->
<?php do_action( 'sd-detail-details-after' ); ?>
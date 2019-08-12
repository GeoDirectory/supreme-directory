<?php
global $post;
if(empty($pfp_post)){
	$pfp_post = $post;
}
if ( ( ( function_exists( 'is_buddypress' ) && ! is_buddypress() ) || ! function_exists( 'is_buddypress' ) ) && ! get_post_meta( $pfp_post->ID, 'sd_remove_head', true ) ) {


	$pid        = $pfp_post->ID;
	$featured_image = '';
	if ( function_exists( 'geodir_is_page' ) && geodir_is_page( 'single' ) && isset( $pfp_post->post_type ) ) {
		$page_id = geodir_cpt_template_page( 'page_details', $pfp_post->post_type );
		if ( $page_id ) {
			$pid = $page_id;
		}
	}

	$featured_type = get_post_meta( $pid, '_sd_featured_area', true );
	if ( empty( $featured_type ) ) {

		if ( function_exists( 'geodir_is_page' ) && geodir_is_page( 'location' ) ) {
			$featured_type = 'location';

		} elseif ( is_front_page() ) {
			$featured_type = 'location';
		} else {
			$featured_type = 'parallax';

		}

	}
	if ( $featured_type == 'remove' || ( function_exists('is_product_category') && is_product_category() ) ) {
		return;
	}

	?>
	<header>

		<div class="featured-area type-<?php echo esc_attr( $featured_type ); ?>">

			<?php if ( $featured_type == 'parallax' || $featured_type == 'location' ) {
				?>
				<div id="sd-featured-img" class="featured-img" <?php
				$featured_image = apply_filters( 'sd_featured_image', '' );

				if (!$featured_image && has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$featured_image = $full_image_url[0];
				} else {
					$featured_image = SD_DEFAULT_FEATURED_IMAGE;
				}
				?>>
				</div>
				<?php
			} ?>

			<script>
				(function () {
					var img = new Image(),
						x = document.getElementById('sd-featured-img');

					img.onload = function () {
						x.style.backgroundImage = "url('" + img.src + "')";
						x.classList.add("sd-fade-in");
					};

					img.src = "<?php echo esc_url( $featured_image ); ?>";
				})();
			</script>


			<div class="header-wrap">
				<?php

				do_action( 'sd_feature_area' );

				?>
			</div>
		</div>
	</header>
	<?php

}
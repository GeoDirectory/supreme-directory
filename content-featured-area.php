<?php
global $post,$pid;
if ( empty( $pfp_post ) ) {
	$pfp_post = $post;
}

// WooCommerce shop page
if ( function_exists( 'wc_get_page_id' ) && is_shop() ) {
    $pfp_post = get_post( (int) wc_get_page_id( 'shop' ) );
}

// if blog page
if(is_home()){
	$pfp_post = get_option('page_for_posts');
	if($pfp_post){
		$pfp_post = get_post($pfp_post);
	}
}

if ( ( ( function_exists( 'is_buddypress' ) && ! is_buddypress() ) || ! function_exists( 'is_buddypress' ) ) && ! get_post_meta( $pfp_post->ID, 'sd_remove_head', true ) ) {
	$pid        = $pfp_post->ID;
	$jumbotron_size = '';
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


	if ( $featured_type == 'parallax' || $featured_type == 'location' ) {
		$featured_image = apply_filters( 'sd_featured_image', '' );

		if ( ! $featured_image && has_post_thumbnail( $pfp_post->ID ) ) { // check if the post has a Post Thumbnail assigned to it.
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $pfp_post->ID), 'full' );
			$featured_image = $full_image_url[0];
		}elseif ( ! $featured_image && has_post_thumbnail($pid) ) { // check if the post has a Post Thumbnail assigned to it.
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($pid), 'full' );
			$featured_image = $full_image_url[0];
		} elseif( ! $featured_image ) {
			$featured_image = SD_DEFAULT_FEATURED_IMAGE;
		}

	}elseif($featured_type == 'title'){
		$jumbotron_size = 'jumbotron-sm';
	}
	?>
	<header class="featured-area">
		<div class="jumbotron jumbotron-fluid <?php echo $jumbotron_size;?> overlay overlay-black mb-0 bg-dark position-relative " id="sd-featured-imgx">
			<div id="sd-featured-img" class="featured-img w-100 position-absolute h-100 overlay overlay-black " style="top:0;left:0;background-position:50% 20%;background-repeat: no-repeat;background-size: cover;" ></div>
			<div class="container text-center text-white h-100">
				<?php
				do_action( 'sd_feature_area' );
				?>
			</div>
	</header>
	<script type="text/javascript">
		/* <![CDATA[ */
		(function() {
			var img = new Image(), x = document.getElementById('sd-featured-img');

			img.onload = function() {
				x.style.backgroundImage = "url('" + img.src + "')";
				x.classList.add("sd-fade-in");
			};

			img.src = "<?php echo esc_url( $featured_image ); ?>";
		})();
		/* ]]> */
	</script>
	<?php
}
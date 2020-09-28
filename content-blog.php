<?php
if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
} else {
	$full_image_url[0] = SD_DEFAULT_FEATURED_IMAGE;
}
?>
<article <?php post_class('col-4'); ?>>

	<div class="card bg-dark overlay overlay-black text-white shadow-sm border-0">
		<img class="card-img" src="<?php echo esc_url( $full_image_url[0] ); ?>" alt="<?php the_title(); ?>">
		<div class="card-img-overlay d-flex align-items-center text-center">
			<div class="card-body">
				<a href="<?php the_permalink(); ?>" class="text-white"><h3 class="card-title text-white"><?php the_title(); ?></h3></a>
				<p class="card-text text-white">
					<?php directory_theme_entry_meta('text-white'); //supreme_entry_meta(); ?>
				</p>
				<a href="<?php the_permalink(); ?>" class="btn btn-light btn-round btn-sm"><?php _e("View Post","supreme-directory"); ?></a>
			</div>
		</div>
	</div>

</article>


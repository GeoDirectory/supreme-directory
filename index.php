<?php get_header();

do_action( 'dt_page_before_main_content' );
if ( is_home() && get_option( 'page_for_posts' ) ) {
	$pfp_post        = get_post( get_option( 'page_for_posts' ) );

	get_template_part( 'content-featured-area' );

} else if ( is_search() ) { ?>
	<header>
		<div class="featured-area type-title">
			<div class="header-wrap">
				<?php do_action( 'sd_feature_area' ); ?>
			</div>
		</div>
	</header>
<?php } ?>

<div class="container">
	<div class="content-box content-single <?php echo( is_search() ? 'content-search' : '' ); ?>">
		<?php if ( ! have_posts() ) : ?>
			<div class="alert-error">
				<p><?php _e( 'Sorry, no results were found.', 'supreme-directory' ); ?></p>
			</div>
			<?php get_search_form(); ?>
		<?php endif; ?>
		<?php
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'content-blog' );

			// End the loop.
		endwhile;

		// Previous/next page navigation.
		the_posts_pagination( array(
			'prev_text' => __( 'Previous', 'supreme-directory' ),
			'next_text' => __( 'Next', 'supreme-directory' ),
		) );
		?>
	</div>
</div>
<?php get_footer(); ?>

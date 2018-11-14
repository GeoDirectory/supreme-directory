<?php

abstract class SD_Metabox
{
	public static function add()
	{
		$screens = array('post', 'page');
		foreach ($screens as $screen) {
			add_meta_box(
				'sd_featured_area_settings',          // Unique ID
				__('Featured Area Settings','supreme-directory'), // Box title
				array(__CLASS__, 'html'),   // Content callback, must be of type callable
				$screen,                 // Post type
				'side'
			);
		}
	}

	public static function save($post_id)
	{
		if (array_key_exists('sd_featured_area', $_POST)) {
			update_post_meta(
				$post_id,
				'_sd_featured_area',
				$_POST['sd_featured_area']
			);
		}
	}

	public static function html($post)
	{
		$value = get_post_meta($post->ID, '_sd_featured_area', true);
		$options = array(
			''  => __('Auto','supreme-directory'),
			'parallax'  => __('Title over parallax image','supreme-directory'),
			'title'  => __('Title','supreme-directory'),
			'remove'  => __('None','supreme-directory'),
		);
		?>
		<label for="sd_featured_area"><?php _e('Show:','supreme-directory');?></label>
		<select name="sd_featured_area" id="sd_featured_area" class="postbox">
			<?php

			foreach($options as $option => $desc){
				echo '<option value="'.$option.'" '.selected($value, $option).'>'.$desc.'</option>';
			}
			?>
		</select>
		<?php
	}
}

add_action('add_meta_boxes', array('SD_Metabox', 'add'));
add_action('save_post', array('SD_Metabox', 'save'));
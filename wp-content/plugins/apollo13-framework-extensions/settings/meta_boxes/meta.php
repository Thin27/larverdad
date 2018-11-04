<?php
add_filter( 'apollo13framework_meta_boxes_post', 'a13fe_custom_meta_boxes_post' );
/**
 * Adds meta fields creating content for posts
 *
 * @since  1.4.0
 *
 * @param array $meta_fields - array of current meta fields
 *
 * @return array updated meta fields
 */
function a13fe_custom_meta_boxes_post($meta_fields){
	//add field in posts_list tab
	array_splice( $meta_fields['posts_list'], 1, 0, array(
			array(
				'name'        => __( 'Alternative Link', 'a13_framework_cpt' ),
				'description' => __( 'If you fill this then when selecting post from Blog(post list), it will lead to this link instead of opening post.', 'a13_framework_cpt' ),
				'id'          => 'alt_link',
				'default'     => '',
				'type'        => 'text',
			)
		)
	);

	return $meta_fields;
}



add_filter( 'apollo13framework_meta_boxes_page', 'a13fe_custom_meta_boxes_page' );
/**
 * Adds meta fields creating content for pages
 *
 * @since  1.4.0
 *
 * @param array $meta_fields - array of current meta fields
 *
 * @return array updated meta fields
 */
function a13fe_custom_meta_boxes_page($meta_fields){
	//add field in title_bar tab
	array_splice( $meta_fields['title_bar'], 1, 0, array(
			array(
				'name'    => __( 'Subtitle', 'a13_framework_cpt' ),
				'id'      => 'subtitle',
				'default' => '',
				'type'    => 'text'
			)
		)
	);

	return $meta_fields;
}



add_filter( 'apollo13framework_meta_boxes_album', 'a13fe_custom_meta_boxes_album' );
/**
 * Adds meta fields creating content for albums
 *
 * @since  1.4.0
 *
 * @param array $meta_fields - array of current meta fields
 *
 * @return array updated meta fields
 */
function a13fe_custom_meta_boxes_album($meta_fields){
	/*
	 *
	 * Tab: Album info
	 *
	 */
	$meta_fields['album_info'] = array(
		array(
			'name' => __('Album info', 'a13_framework_cpt'),
			'type' => 'fieldset',
			'tab'   => true,
			'icon'  => 'fa fa-info-circle'
		),
		array(
			'name'        => __( 'Internet address', 'a13_framework_cpt' ),
			'description' => __( 'If empty it will not be displayed.', 'a13_framework_cpt' ),
			'id'          => 'www',
			'default'     => '',
			'placeholder' => 'http://link-to-somewhere.com',
			'type'        => 'text'
		),
	);

	/**
	 * Increase number of custom fields in albums & works
	 *
	 *
	add_filter('apollo13framework_custom_fields_number', function(){
		return 13;//change this number to any value you need
	});
	 *
	 *
	 */
	$custom_fields_number = apply_filters('apollo13framework_custom_fields_number', 5);

	for($i=1; $i <= $custom_fields_number; $i++){
		array_push($meta_fields['album_info'],
			array(
				/* translators: %d - index of field  */
				'name'        => sprintf( __( 'Custom info %d', 'a13_framework_cpt' ), $i ),
				'description' => __( 'If empty it will not be displayed. Use pattern <strong>Field name: Field value</strong>. Colon(:) is most important to get full result.', 'a13_framework_cpt' ),
				'id'          => 'custom_'.$i,
				'default'     => '',
				'placeholder' => 'Label: value',
				'type'        => 'text'
			)
		);
	}

	//add field in albums_list tab
	array_splice( $meta_fields['albums_list'], 1, 0, array(
			array(
				'name'        => __( 'Alternative Link', 'a13_framework_cpt' ),
				'description' => __( 'If you fill this then clicking in your album on albums list will not lead to single album page but to link from this field.', 'a13_framework_cpt' ),
				'id'          => 'alt_link',
				'default'     => '',
				'type'        => 'text',
			),
			array(
				'name'    => __( 'Subtitle', 'a13_framework_cpt' ),
				'id'      => 'subtitle',
				'default' => '',
				'type'    => 'text'
			)
		)
	);

	return $meta_fields;
}



add_filter( 'apollo13framework_meta_boxes_work', 'a13fe_custom_meta_boxes_work' );
/**
 * Adds meta fields creating content for works
 *
 * @since  1.4.0
 *
 * @param array $meta_fields - array of current meta fields
 *
 * @return array updated meta fields
 */
function a13fe_custom_meta_boxes_work($meta_fields){
	/*
	 *
	 * Tab: Work info
	 *
	 */
	$meta_fields['work_info'] = array(
		array(
			'name' => __('Work info', 'a13_framework_cpt'),
			'type' => 'fieldset',
			'tab'   => true,
			'icon'  => 'fa fa-info-circle'
		),
		array(
			'name'        => __( 'Internet address', 'a13_framework_cpt' ),
			'description' => __( 'If empty it will not be displayed.', 'a13_framework_cpt' ),
			'id'          => 'www',
			'default'     => '',
			'placeholder' => 'http://link-to-somewhere.com',
			'type'        => 'text'
		),
	);

	$custom_fields_number = apply_filters('apollo13framework_custom_fields_number', 5);

	for($i=1; $i <= $custom_fields_number; $i++){
		array_push($meta_fields['work_info'],
			array(
				/* translators: %d - index of field  */
				'name'        => sprintf( __( 'Custom info %d', 'a13_framework_cpt' ), $i ),
				'description' => __( 'If empty it will not be displayed. Use pattern <strong>Field name: Field value</strong>. Colon(:) is most important to get full result.', 'a13_framework_cpt' ),
				'id'          => 'custom_'.$i,
				'default'     => '',
				'placeholder' => 'Label: value',
				'type'        => 'text'
			)
		);
	}




	//add field in works_list tab
	array_splice( $meta_fields['works_list'], 1, 0, array(
			array(
				'name'        => __( 'Alternative Link', 'a13_framework_cpt' ),
				'description' => __( 'If you fill this then clicking in your work on works list will not lead to single work page but to link from this field.', 'a13_framework_cpt' ),
				'id'          => 'alt_link',
				'default'     => '',
				'type'        => 'text',
			),
			array(
				'name'    => __( 'Subtitle', 'a13_framework_cpt' ),
				'id'      => 'subtitle',
				'default' => '',
				'type'    => 'text'
			)
		)
	);

	return $meta_fields;
}

add_filter( 'apollo13framework_meta_boxes_images_manager', 'a13fe_custom_meta_images_manager' );
/**
 * Adds meta fields creating content for images manager
 *
 * @since  1.4.0
 *
 * @return array updated meta fields
 */
function a13fe_custom_meta_images_manager(){
	$meta =
		array(
			'images_manager' => array(
				array(
					'name' => '',
					'type' => 'fieldset'
				),
				array(
					'name'       => __( 'Multi upload', 'a13_framework_cpt' ),
					'id'         => 'images_n_videos',
					'type'       => 'multi-upload',
					'default'    => '[]', //empty JSON
					'media_type' => 'image,video', /* 'audio,video' */
				),
				array(
					'name'         => '',
					'type'         => 'fieldset',
					'is_prototype' => true,
					'id'           => 'mu-prototype-image',
				),
				array(
					'name'        => __( 'Tags', 'a13_framework_cpt' ),
					'description' => __( 'Separate tags with commas', 'a13_framework_cpt' ),
					'id'          => 'image_tags',
					'default'     => '',
					'type'        => 'tag_media',
				),
				array(
					'name'        => __( 'Link', 'a13_framework_cpt' ),
					'description' => __( 'Alternative link', 'a13_framework_cpt' ),
					'id'          => 'image_link',
					'default'     => '',
					'type'        => 'text',
				),
				array(
					'name'    => __( 'Color under photo', 'a13_framework_cpt' ),
					'id'      => 'image_bg_color',
					'default' => '',
					'type'    => 'color'
				),
				array(
					'name'        => __( 'RatioX in bricks theme', 'a13_framework_cpt' ),
					'description' => __( 'How many bricks area should take this image.', 'a13_framework_cpt' ),
					'id'          => 'image_ratio_x',
					'default'     => 1,
					'unit'        => '',
					'min'         => 1,
					'max'         => 6,
					'type'        => 'slider'
				),
				array(
					'name'         => '',
					'type'         => 'fieldset',
					'is_prototype' => true,
					'id'           => 'mu-prototype-video',
				),
				array(
					'name'        => __( 'Tags', 'a13_framework_cpt' ),
					'description' => __( 'Separate tags with commas', 'a13_framework_cpt' ),
					'id'          => 'video_tags',
					'default'     => '',
					'type'        => 'tag_media',
				),
				array(
					'name'        => __( 'Autoplay video', 'a13_framework_cpt' ),
					'description' => __( 'Works only in slider', 'a13_framework_cpt' ),
					'id'          => 'video_autoplay',
					'default'     => '0',
					'options'     => array(
						'1' => __( 'On', 'a13_framework_cpt' ),
						'0' => __( 'Off', 'a13_framework_cpt' ),
					),
					'type'        => 'radio',
				),
				array(
					'name'        => __( 'RatioX in bricks theme', 'a13_framework_cpt' ),
					'description' => __( 'How many bricks area should take this video.', 'a13_framework_cpt' ),
					'id'          => 'video_ratio_x',
					'default'     => 1,
					'unit'        => '',
					'min'         => 1,
					'max'         => 6,
					'type'        => 'slider'
				),
				array(
					'name'         => '',
					'type'         => 'fieldset',
					'is_prototype' => true,
					'id'           => 'mu-prototype-audio',
				),
				array(
					'name'        => __( 'Tags', 'a13_framework_cpt' ),
					'description' => __( 'Separate tags with commas', 'a13_framework_cpt' ),
					'id'          => 'audio_tags',
					'default'     => '',
					'type'        => 'tag_media',
				),
				array(
					'name'    => __( 'Autoplay audio', 'a13_framework_cpt' ),
					'id'      => 'audio_autoplay',
					'default' => '0',
					'options' => array(
						'1' => __( 'On', 'a13_framework_cpt' ),
						'0' => __( 'Off', 'a13_framework_cpt' ),
					),
					'type'    => 'radio',
				),
				array(
					'name'        => __( 'RatioX in bricks theme', 'a13_framework_cpt' ),
					'description' => __( 'How many bricks area should take this audio.', 'a13_framework_cpt' ),
					'id'          => 'audio_ratio_x',
					'default'     => 1,
					'unit'        => '',
					'min'         => 1,
					'max'         => 6,
					'type'        => 'slider'
				),
				array(
					'name'         => '',
					'type'         => 'fieldset',
					'is_prototype' => true,
					'id'           => 'mu-prototype-videolink',
				),
				array(
					'name'        => __( 'Link to video', 'a13_framework_cpt' ),
					'description' => __( 'Insert here link to your  youtube/vimeo video.', 'a13_framework_cpt' ),
					'id'          => 'videolink_link',
					'default'     => '',
					'type'        => 'text',
				),
				array(
					'name'             => __( 'Video Thumb', 'a13_framework_cpt' ),
					'description'      => __( 'Displayed instead of video placeholder in some cases. If none, placeholder will be used(for youtube movies default thumbnail will show).', 'a13_framework_cpt' ),
					'id'               => 'videolink_poster',
					'default'          => '',
					'button_text'      => __( 'Upload Image', 'a13_framework_cpt' ),
					'attachment_field' => 'videolink_attachment_id',
					'type'             => 'upload'
				),
				array(
					'name'    => __( 'Attachment id', 'a13_framework_cpt' ),
					'id'      => 'videolink_attachment_id',
					'default' => '',
					'type'    => 'hidden'
				),
				array(
					'name'        => __( 'Tags', 'a13_framework_cpt' ),
					'description' => __( 'Separate tags with commas', 'a13_framework_cpt' ),
					'id'          => 'videolink_tags',
					'default'     => '',
					'type'        => 'tag_media',
				),
				array(
					'name'        => __( 'Autoplay video', 'a13_framework_cpt' ),
					'description' => __( 'Works only in slider', 'a13_framework_cpt' ),
					'id'          => 'videolink_autoplay',
					'default'     => '0',
					'options'     => array(
						'1' => __( 'On', 'a13_framework_cpt' ),
						'0' => __( 'Off', 'a13_framework_cpt' ),
					),
					'type'        => 'radio',
				),
				array(
					'name'        => __( 'RatioX in bricks theme', 'a13_framework_cpt' ),
					'description' => __( 'How many bricks area should take this video.', 'a13_framework_cpt' ),
					'id'          => 'videolink_ratio_x',
					'default'     => 1,
					'unit'        => '',
					'min'         => 1,
					'max'         => 6,
					'type'        => 'slider'
				),
				array(
					'name'    => __( 'Title', 'a13_framework_cpt' ),
					'id'      => 'videolink_title',
					'default' => '',
					'type'    => 'text'
				),
				array(
					'name'    => __( 'Description', 'a13_framework_cpt' ),
					'id'      => 'videolink_desc',
					'default' => '',
					'type'    => 'textarea',
				),
			)
		);

	return $meta;
}



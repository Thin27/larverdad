<?php
namespace Apollo13_FE\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Gallery extends Widget_Base {

	public function get_name() {
		return 'a13fe-gallery';
	}

	public function get_title() {
		return __( 'Theme Gallery', 'a13_framework_cpt' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general', 'apollo13-framework' ];
	}

	private function get_albums_works(){
		$options = [ '' => '' ];

		//list all Albums
		$wp_query_params = array(
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'post_type' => A13FRAMEWORK_CUSTOM_POST_TYPE_ALBUM,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'orderby' => 'date'
		);

		$r = new \WP_Query($wp_query_params);

		if ($r->have_posts()){
			while ($r->have_posts()){
				$r->the_post();
				$options[ get_the_ID() ] = __( 'Album', 'a13_framework_cpt' ) . ': '. get_the_title();
			}
		}

		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		//list all Works
		$wp_query_params = array(
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'post_type' => A13FRAMEWORK_CUSTOM_POST_TYPE_WORK,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'orderby' => 'date'
		);

		$r = new \WP_Query($wp_query_params);

		if ($r->have_posts()){
			while ($r->have_posts()){
				$r->the_post();
				$options[ get_the_ID() ] = __( 'Work', 'a13_framework_cpt' ) . ': '. get_the_title();
			}
		}

		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		return $options;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'gallery_settings',
			[
				'label' => __( 'Gallery settings', 'a13_framework_cpt' ),
			]
		);

		$this->add_control(
			'post_id',
			[
				'label' => __( 'Album/Work', 'a13_framework_cpt' ),
				'description' => __( 'Album or Work that images should be taken from.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => "",
				'options' => $this->get_albums_works(),
			]
		);

		$this->add_control(
			'post_description',
			[
				'raw' => __( 'You can edit images used in the widget, by editing selected Album/work.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);


		$this->add_control(
			'filter',
			[
				'label' => __( 'Filter', 'a13_framework_cpt' ),
				'description' => __( 'If tags for items are provided should filter be displayed.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gallery_layout',
			[
				'label' => __( 'Gallery layout', 'a13_framework_cpt' ),
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 3,
					'unit' => 'cols'
				],
				'range' => [
					'cols' => [
						'min' => 1,
						'max' => 6,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'margin',
			[
				'label' => __( 'Margin', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SLIDER,
				'description' => __( 'Space between bricks.', 'a13_framework_cpt' ),
				'default' => [
					'size' => 5,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'ratio',
			[
				'label' => __( 'Aspect ratio', 'a13_framework_cpt' ),
				'description' => __( 'Width to height aspect ratio of the bricks. Should be provided as 2 numbers divided with "/". Example: 21/9. Use  "0" for masonry look with natural image ratio.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::TEXT,
				'default' => '0',
				'placeholder' => __( '16/9, 4/3, 21/9...', 'a13_framework_cpt' ),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'brick_look',
			[
				'label' => __( 'Bricks behaviour', 'a13_framework_cpt' ),
			]
		);

		$this->add_control(
			'lightbox',
			[
				'label' => __( 'Open to lightbox', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Effect on hover', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'drop',
				'options' => [
					'cross'  => __( 'Show cross', 'a13_framework_cpt' ),
					'drop'   => __( 'Drop', 'a13_framework_cpt' ),
					'shift'  => __( 'Shift', 'a13_framework_cpt' ),
					'pop'    => __( 'Pop Text', 'a13_framework_cpt' ),
					'border' => __( 'Border', 'a13_framework_cpt' ),
					'none'   => __( 'None', 'a13_framework_cpt' ),
				],
			]
		);

		$this->add_control(
			'cover',
			[
				'label' => __( 'Cover', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cover_hover',
			[
				'label' => __( 'Cover on hover', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'cover_color',
			[
				'label' => __( 'Cover Color', 'a13_framework_cpt' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.7)',
			]
		);

		$this->add_control(
			'texts',
			[
				'label' => __( 'Titles &amp; descriptions', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'texts_hover',
			[
				'label' => __( 'Titles &amp; descriptions on hover', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'texts_position',
			[
				'label' => __( 'Texts position', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom_center',
				'options' => [
					'top_left'      => __( 'Top left', 'a13_framework_cpt' ),
					'top_center'    => __( 'Top center', 'a13_framework_cpt' ),
					'top_right'     => __( 'Top right', 'a13_framework_cpt' ),
					'mid_left'      => __( 'Middle left', 'a13_framework_cpt' ),
					'mid_center'    => __( 'Middle center', 'a13_framework_cpt' ),
					'mid_right'     => __( 'Middle right', 'a13_framework_cpt' ),
					'bottom_left'   => __( 'Bottom left', 'a13_framework_cpt' ),
					'bottom_center' => __( 'Bottom center', 'a13_framework_cpt' ),
					'bottom_right'  => __( 'Bottom right', 'a13_framework_cpt' ),
				],
			]
		);

		$this->add_control(
			'gradient',
			[
				'label' => __( 'Gradient', 'a13_framework_cpt' ),
				'description' => __( 'Gradient position is dependant on "Texts position".', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'gradient_hover',
			[
				'label' => __( 'Gradient on hover', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		/** @noinspection HtmlUnknownTarget */
		$this->add_control(
			'socials',
			[
				'label' => __( 'Socials', 'a13_framework_cpt' ),
				'description' => sprintf(__( 'Requires <a href="%s">AddToAny</a> plugin.', 'a13_framework_cpt' ), esc_url( 'https://rifetheme.com/help/docs/plugins-recommendations/addtoany-share-icons/' ) ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$id          = $settings['post_id'];

		//without id
		if ( $id === "" ) {
			echo esc_html__( 'Please select album/work to use.', 'a13_framework_cpt' );
			return;
		}


		//it doesn't contain items to use
		$has_images = strlen( get_post_meta( $id, '_images_n_videos', true ) ) > 2; //2 => [] - empty array
		if ( ! $has_images ) {
			echo esc_html__( 'Error: Selected post does not contain any items to use in slider. Use proper album or work.', 'a13_framework_cpt' );
			return;
		}

		$cover_color    = $settings['cover_color'];
		$filter         = $settings['filter'];
		$lightbox       = $settings['lightbox'];
		$texts_position = $settings['texts_position'];
		$hover_effect   = $settings['hover_effect'];
		$cover          = $settings['cover'];
		$cover_hover    = $settings['cover_hover'];
		$gradient       = $settings['gradient'];
		$gradient_hover = $settings['gradient_hover'];
		$texts          = $settings['texts'];
		$texts_hover    = $settings['texts_hover'];
		$socials        = $settings['socials'];
		$margin         = $settings['margin']['size'].$settings['margin']['unit'];
		$ratio          = $settings['ratio'];
		$columns        = $settings['columns']['size'];

		//make sure on/off params have proper values
		$on_off_attrs = array(
			'filter',
			'lightbox',
			'cover',
			'cover_hover',
			'gradient',
			'gradient_hover',
			'texts',
			'texts_hover',
			'socials'
		);

		foreach($on_off_attrs as $attribute ){
			$$attribute = $$attribute === 'yes'? 'on' : 'off';
		}

		$gallery_opts = array(
			'cover_color'            => $cover_color,
			'filter'                 => $filter,
			'lightbox'               => $lightbox,
			'title_position'         => $texts_position,
			'hover_effect'           => $hover_effect,
			'overlay_cover'          => $cover,
			'overlay_cover_hover'    => $cover_hover,
			'overlay_gradient'       => $gradient,
			'overlay_gradient_hover' => $gradient_hover,
			'overlay_texts'          => $texts,
			'overlay_texts_hover'    => $texts_hover,
			'socials'                => $socials,
			'margin'                 => $margin,
			'proportion'             => $ratio,
			'columns'                => $columns,
		);

		apollo13framework_make_bricks_gallery($gallery_opts, $id);
	}

}

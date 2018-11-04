<?php
namespace Apollo13_FE\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Scroller extends Widget_Base {

	public function get_name() {
		return 'a13fe-scroller';
	}

	public function get_title() {
		return __( 'Theme Scroller', 'a13_framework_cpt' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
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
			'scroller_settings',
			[
				'label' => __( 'Scroller settings', 'a13_framework_cpt' ),
			]
		);


		/** @noinspection HtmlUnknownTarget */
		$this->add_control(
			'paid_only_description',
			[
				'raw' => sprintf(__( 'This works only with <a href="%s">Rife Pro theme</a>. Rife Free will display slider instead.', 'a13_framework_cpt' ), esc_url( 'https://apollo13themes.com/rife/' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
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


		$this->end_controls_section();

		$this->start_controls_section(
			'scroller_look',
			[
				'label' => __( 'Scroller layout', 'a13_framework_cpt' ),
			]
		);


		$this->add_control(
			'window_high',
			[
				'label' => __( 'Take available height', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'ratio',
			[
				'label' => __( 'Aspect ratio', 'a13_framework_cpt' ),
				'description' => __( 'Width to height aspect ratio of the slider. Should be provided as 2 numbers divided with "/". Example: 21/9. Takes effect when the slider is not stretched to full height.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::TEXT,
				'default' => '16/9',
				'placeholder' => __( '16/9, 4/3, 21/9...', 'a13_framework_cpt' ),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'scroller_interface',
			[
				'label' => __( 'Scroller interface', 'a13_framework_cpt' ),
			]
		);

		$this->add_control(
			'parallax',
			[
				'label' => __( 'Parallax', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'effect',
			[
				'label' => __( 'Effect on not active images', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'off',
				'options' => [
					'off'        => __( 'Disabled', 'a13_framework_cpt' ),
					'opacity'    => __( 'Opacity', 'a13_framework_cpt' ),
					'scale-down' => __( 'Scale down', 'a13_framework_cpt' ),
					'grayscale'  => __( 'Grayscale', 'a13_framework_cpt' ),
					'blur'       => __( 'Blur', 'a13_framework_cpt' ),
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'a13_framework_cpt' ),
				'description' => __( '0 for no autoplay, any other positive number to set number of seconds between "scrolls".', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 3,
					'unit' => 's'
				],
				'range' => [
					's' => [
						'min' => 0,
						'max' => 15,
						'step' => 0.5,
					],
				],
				'separator' => 'before',
			]
		);


		$this->add_control(
			'texts',
			[
				'label' => __( 'Titles &amp; descriptions', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
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

		$opts = [];

		$opts['autoPlay']      = $settings['autoplay']['size'] * 1000;
		$opts['a13ShowDesc']   = $settings['texts'] === 'yes';
		$opts['a13WindowHigh'] = $settings['window_high'] === 'yes';
		$opts['a13Socials']    = $settings['socials'] === 'yes';
		$opts['a13Ratio']      = $settings['ratio'];
		$opts['a13Parallax']   = $settings['parallax'] === 'yes';
		$opts['a13Effect']     = $settings['effect'];


		//check if such options are defined in parent post. If not don't set them so they will return to theme defaults
		$test = strlen( get_post_meta( $id, '_scroller_wrap_around', true ) );


		//other settings from parent album
		if ( strlen( $test ) ) {
			$opts['wrapAround']         = get_post_meta( $id, '_scroller_wrap_around', true ) === 'on';
			$opts['contain']            = get_post_meta( $id, '_scroller_contain', true ) === 'on';
			$opts['freeScroll']         = get_post_meta( $id, '_scroller_free_scroll', true ) === 'on';
			$opts['prevNextButtons']    = get_post_meta( $id, '_scroller_arrows', true ) === 'on';
			$opts['pageDots']           = get_post_meta( $id, '_scroller_dots', true ) === 'on';
			$opts['a13CellWidth']       = get_post_meta( $id, '_scroller_cell_width', true );
			$opts['a13CellWidthMobile'] = get_post_meta( $id, '_scroller_cell_width_mobile', true );
		}


		wp_enqueue_script('flickity');
		if(function_exists('apollo13framework_make_scroller')) {
			apollo13framework_make_scroller( $opts, $id );
		}
	}

}

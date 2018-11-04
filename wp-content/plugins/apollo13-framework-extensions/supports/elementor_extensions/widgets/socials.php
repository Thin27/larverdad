<?php
namespace Apollo13_FE\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Socials extends Widget_Base {

	public function get_name() {
		return 'a13fe-socials';
	}

	public function get_title() {
		return __( 'Theme Social icons', 'a13_framework_cpt' );
	}

	public function get_icon() {
		return 'eicon-social-icons';
	}

	public function get_categories() {
		return [ 'general', 'apollo13-framework' ];
	}

	protected function _register_controls() {
		$social_colors = array(
			'black'            => esc_html__( 'Black', 'a13_framework_cpt' ),
			'color'            => esc_html__( 'Color', 'a13_framework_cpt' ),
			'white'            => esc_html__( 'White', 'a13_framework_cpt' ),
			'semi-transparent' => esc_html__( 'Semi transparent', 'a13_framework_cpt' ),
		);

		$this->start_controls_section(
			'colors',
			[
				'label' => __( 'Colors', 'a13_framework_cpt' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		/** @noinspection HtmlUnknownTarget */
		$this->add_control(
			'anchor_description',
			[
				'raw' => sprintf(__( 'If you need to edit social links, you can do it in Customizer <a href="%s">Social settings</a>.', 'a13_framework_cpt' ), esc_url( admin_url( '/customize.php?autofocus[section]=section_social' ) ) ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Normal color', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'options' => $social_colors,
				'default' => 'color',
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Hover color', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'options' => $social_colors,
				'default' => 'semi-transparent',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		echo apollo13framework_social_icons( $settings['color'], $settings['hover_color'] );
	}
}

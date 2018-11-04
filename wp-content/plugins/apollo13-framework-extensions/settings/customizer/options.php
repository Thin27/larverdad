<?php

/**
 * Adds customizer options for few features
 *
 * @since  1.4.0
 */
function a13fe_add_theme_options() {
	global $apollo13framework_a13;
//CUSTOM CSS
	$apollo13framework_a13->set_sections( array(
		'title'         => esc_html__( 'Custom CSS', 'a13_framework_cpt' ),
		'desc'          => '',
		'id'            => 'section_custom_css',
		'icon'          => 'fa fa-css3',
		'priority'      => 25,
		'without_panel' => true,
		'fields'        => array(
			array(
				'id'    => 'custom_css',
				'type'  => 'code_editor',
				'title' => esc_html__( 'Custom CSS', 'a13_framework_cpt' ),
				'js'    => true
			)
		)
	) );

//ADD SIDEBARS
	$apollo13framework_a13->set_sections( array(
		'title'         => esc_html__( 'Add custom Sidebars', 'a13_framework_cpt' ),
		'desc'          => esc_html__( 'You can add here custom sidebars that can be used on pages for example.', 'a13_framework_cpt' ),
		'id'            => 'section_sidebars',
		'icon'          => 'fa fa-columns',
		'priority'      => 26,
		'without_panel' => true,
		'fields'        => array(
			array(
				'id'      => 'custom_sidebars',
				'type'    => 'custom_sidebars',
				'title'   => esc_html__( 'Add custom sidebars', 'a13_framework_cpt' ),
				'default' => array(),
			),
		)
	) );
}
add_action( 'apollo13framework_additional_theme_options', 'a13fe_add_theme_options' );
<?php
if ( ! function_exists( 'apollo13framework_add_sidebars' ) ) {
	/**
	 * Functions that register all theme sidebars
	 *
	 */
	function apollo13framework_add_sidebars() {
		//defined sidebars
		$widget_areas = array(
			// Shown on blog
			'blog-widget-area'   => array(
				'name'        => esc_html__( 'Blog sidebar', 'rife-free' ),
				'description' => esc_html__( 'Widgets from this sidebar will appear on blog, search results, archive page and 404 error page.', 'rife-free' ),
			),
			// Shown in post
			'post-widget-area'   => array(
				'name'        => esc_html__( 'Post sidebar', 'rife-free' ),
				'description' => esc_html__( 'Widgets from this sidebar will appear in single posts.', 'rife-free' ),
			),
			// Shown in pages
			'page-widget-area'   => array(
				'name'        => esc_html__( 'Page sidebar', 'rife-free' ),
				'description' => esc_html__( 'Widgets from this sidebar will appear in static pages.', 'rife-free' ),
			),
			// Shown in shop pages
			'shop-widget-area'   => array(
				'name'        => esc_html__( 'Shop sidebar', 'rife-free' ),
				'description' => esc_html__( 'Widgets from this sidebar will appear only in shop pages. Woocommerce have to be installed and activated.', 'rife-free' ),
			),
			// Shown everywhere
			'side-widget-area'   => array(
				'name'        => esc_html__( 'Hidden sidebar', 'rife-free' ),
				'description' => esc_html__( 'It is always available sidebar, that is activated by clicking on icon in header. Good for some special menus or other tips, information for user.', 'rife-free' ),
			),
			// Shown everywhere
			'basket-widget-area' => array(
				'name'        => esc_html__( 'Basket sidebar', 'rife-free' ),
				'description' => esc_html__( 'It is always available sidebar (but only active if woocommerce is installed and activated), that is activated by clicking on icon in header. You should place there cart widget and also some promotions widgets for example.', 'rife-free' ),
			),
			// Shown in header
			'header-widget-area' => array(
				'name'          => esc_html__( 'Header(vertical) default widget area', 'rife-free' ),
				'description'   => esc_html__( 'Widgets from this area will appear in vertical header only.', 'rife-free' ),
			),
			// Shown in footer
			'footer-widget-area' => array(
				'name'          => esc_html__( 'Footer widget area(Header horizontal)', 'rife-free' ),
				'description'   => esc_html__( 'Widgets from this area will appear in footer, only if horizontal header is used.', 'rife-free' ),
			),
		);

		//custom sidebars
		global $apollo13framework_a13;
		$custom_sidebars = $apollo13framework_a13->get_option( 'custom_sidebars' );
		if($custom_sidebars === false ){
			$custom_sidebars = array();
		}
		else{
			$custom_sidebars = is_array($custom_sidebars)? $custom_sidebars : array($custom_sidebars);
		}
		$sidebars_count  = count( $custom_sidebars );
		if ( is_array( $custom_sidebars ) && $sidebars_count > 0 ) {
			foreach ( $custom_sidebars as $sidebar ) {
				$widget_areas[ $sidebar['id'] ] = array(
					'name'        => $sidebar['name'],
					'description' => esc_html__( 'Widgets from this sidebar will appear in static pages.', 'rife-free' ),
				);
			}
		}

		/**
		 * Register widgets areas
		 */
		foreach ( $widget_areas as $id => $sidebar ) {
			register_sidebar( array(
				'name'          => $sidebar['name'],
				'id'            => $id,
				'description'   => $sidebar['description'],
				'before_widget' => ( isset( $sidebar['before_widget'] ) ? $sidebar['before_widget'] : '<div id="%1$s" class="widget %2$s">' ),
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="title"><span>',
				'after_title'   => '</span></h3>',
			) );
		}
	}
}

add_action( 'widgets_init', 'apollo13framework_add_sidebars' );
?>
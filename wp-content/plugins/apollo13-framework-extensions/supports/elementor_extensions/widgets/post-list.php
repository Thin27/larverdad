<?php
namespace Apollo13_FE\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_List extends Widget_Base {

	public function get_name() {
		return 'a13fe-post-list';
	}

	public function get_title() {
		return __( 'Theme Post List', 'a13_framework_cpt' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'general', 'apollo13-framework' ];
	}

	private function add_taxonomies(){

		$album_tax  = defined( 'A13FRAMEWORK_CPT_ALBUM_TAXONOMY' ) ? A13FRAMEWORK_CPT_ALBUM_TAXONOMY : 'genre';
		$work_tax  = defined( 'A13FRAMEWORK_CPT_WORK_TAXONOMY' ) ? A13FRAMEWORK_CPT_WORK_TAXONOMY : 'work_genre';
		$people_tax  = defined( 'A13FRAMEWORK_CPT_PEOPLE_TAXONOMY' ) ? A13FRAMEWORK_CPT_PEOPLE_TAXONOMY : 'group';


		$taxonomies = [
			'category' => get_taxonomy('category'),
			$album_tax => get_taxonomy($album_tax),
			$work_tax => get_taxonomy($work_tax),
			$people_tax => get_taxonomy($people_tax),
		];

		foreach ( $taxonomies as $taxonomy => $object ) {
			$taxonomy_args = [
				'label' => $object->label,
				'description' => __( 'Categories that items must have to be displayed. By default all items will be displayed.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'object_type' => $taxonomy,
				'options' => [],
				'condition' => [
					'post_type' => $object->object_type,
				],
			];

			$options = [];

			$terms = get_terms( [
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			] );

			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}

			$taxonomy_args['options'] = $options;

			$this->add_control(
				'taxonomy_'.$object->object_type[0],
				$taxonomy_args
			);
		}
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'post_list_settings',
			[
				'label' => __( 'Data source settings', 'a13_framework_cpt' ),
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Post type', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => "post",
				'options' => [
					'post'   => __( 'Post', 'a13_framework_cpt' ),
					'album'  => __( 'Album', 'a13_framework_cpt' ),
					'work'   => __( 'Work', 'a13_framework_cpt' ),
					'people' => __( 'People', 'a13_framework_cpt' )
				],
			]
		);

		$this->add_taxonomies();

		$this->add_control(
			'category_operator',
			[
				'label' => __( 'Categories operator', 'a13_framework_cpt' ),
				'description' => __( 'If you have selected more than one category, then choose should items have all the categories(AND) or one of them at least(IN).', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => "IN",
				'options' => [
					'IN'     => __( 'IN', 'a13_framework_cpt' ),
					'AND'  => __( 'AND', 'a13_framework_cpt' ),
				],
			]
		);

//		$this->add_control(
//			'post_description',
//			[
//				'raw' => __( 'You can edit images used in the widget, by editing selected Album/work.', 'a13_framework_cpt' ),
//				'type' => Controls_Manager::RAW_HTML,
//				'content_classes' => 'elementor-descriptor',
//			]
//		);

		$this->add_control(
			'order',
			[
				'label'       => __( 'Order', 'a13_framework_cpt' ),
				'description' => __( 'If it is left as "Not Set" plugins can affect ordering.', 'a13_framework_cpt' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'separator'   => 'before',
				'default'     => "",
				'options'     => [
					''     => __( 'Not Set', 'a13_framework_cpt' ),
					'asc'  => __( 'Ascending', 'a13_framework_cpt' ),
					'desc' => __( 'Descending', 'a13_framework_cpt' ),
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'       => __( 'Order by:', 'a13_framework_cpt' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => "date",
				'options'     => [
					'date'          => __( 'Date', 'a13_framework_cpt' ),
					'title'         => __( 'Title', 'a13_framework_cpt' ),
					'name'          => __( 'Slug', 'a13_framework_cpt' ),
					'rand'          => __( 'Random', 'a13_framework_cpt' ),
					'modified'      => __( 'Modified date', 'a13_framework_cpt' ),
					'comment_count' => __( 'Comments number', 'a13_framework_cpt' ),
				],
			]
		);


		$this->add_control(
			'posts_number',
			[
				'label' => __( 'Posts Number', 'a13_framework_cpt' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9,
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_settings',
			[
				'label' => __( 'Layout settings', 'a13_framework_cpt' ),
			]
		);


		$this->add_control(
			'filter',
			[
				'label' => __( 'Filter', 'a13_framework_cpt' ),
				'description' => __( 'If items have categories should filter be displayed.', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'after',
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
						'max' => 4,
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
			'max_width',
			[
				'label' => __( 'Max width', 'a13_framework_cpt' ),
				'type' => Controls_Manager::SLIDER,
				'description' => __( 'Maximum width that bricks can take - it is used to prepare resized images.', 'a13_framework_cpt' ),
				'default' => [
					'size' => 1920,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2500,
						'step' => 1,
					],
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$type = $settings['post_type'];
		$order = $settings['order'];
		$orderby = $settings['orderby'];
		$posts = $settings['posts_number'];
		$columns = $settings['columns']['size'];
		$categories = empty( $settings['taxonomy_'.$type] ) ? [] : $settings['taxonomy_'.$type];
		$category_operator = $settings['category_operator'];
		$filter = $settings['filter'] === 'yes';
		$max_width = $settings['max_width']['size'];
		$margin = $settings['margin']['size'];

		// define query parameters based on attributes
		$options = array(
			'post_type' => $type,
			'order' => $order,
			'posts_per_page' => $posts,
		);

		//add orderby only if needed so plugins sorting CPT could act
		if( strlen($orderby) ){
			$options['orderby'] = $orderby;
		}

		//define custom post types & taxonomies from theme
		$a13_custom_types = array(
			defined( 'A13FRAMEWORK_CUSTOM_POST_TYPE_ALBUM' ) ? A13FRAMEWORK_CUSTOM_POST_TYPE_ALBUM : 'album',
			defined( 'A13FRAMEWORK_CUSTOM_POST_TYPE_PEOPLE' ) ? A13FRAMEWORK_CUSTOM_POST_TYPE_PEOPLE : 'people',
			defined( 'A13FRAMEWORK_CUSTOM_POST_TYPE_WORK' ) ? A13FRAMEWORK_CUSTOM_POST_TYPE_WORK : 'work',
		);
		$a13_custom_taxonomies = array(
			'album' => defined( 'A13FRAMEWORK_CPT_ALBUM_TAXONOMY' ) ? A13FRAMEWORK_CPT_ALBUM_TAXONOMY : 'genre',
			'work' => defined( 'A13FRAMEWORK_CPT_WORK_TAXONOMY' ) ? A13FRAMEWORK_CPT_WORK_TAXONOMY : 'work_genre',
			'people' => defined( 'A13FRAMEWORK_CPT_PEOPLE_TAXONOMY' ) ? A13FRAMEWORK_CPT_PEOPLE_TAXONOMY : 'group'
		);

		//filtering by category name
		if(count($categories)){
			//if querying custom post type
			if(in_array($type, $a13_custom_types)){
				$tax_query['field']    = 'term_id';
				$tax_query['taxonomy'] = $a13_custom_taxonomies[ $type ];
				$tax_query['terms']    = $categories;
				$tax_query['operator'] = $category_operator;

				$options['tax_query'] = array( $tax_query );
			}
			//simple post or unknown post type
			else{
				$options[ ($category_operator === 'AND' ? 'category__and' : 'category__in' ) ] = $categories;
			}
		}

		//make query
		$query = new \WP_Query( $options );

		$args = array(
			'columns' => $columns,
			'filter' => $filter,
			'display_post_id' => false,
			'max_width' => $max_width,
			'margin' => $margin
		);

		//check for special post types
		if(in_array($type, $a13_custom_types)){
			$function_name = 'apollo13framework_display_items_from_query_'.$type.'_list';
			if(function_exists($function_name)){
				$function_name($query, $args);
			}
		}
		//simple post or unknown post type
		else{
			$options['category_name'] = $categories;
			if(function_exists('apollo13framework_display_items_from_query_post_list')){
				apollo13framework_display_items_from_query_post_list($query, $args);
			}
		}

		// Reset Post Data
		wp_reset_postdata();
	}

}

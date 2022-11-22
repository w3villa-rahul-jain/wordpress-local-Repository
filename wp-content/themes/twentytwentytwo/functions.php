<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */




if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support('wp-block-styles');

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

function get_data($arg){
	$message = "Hello World";
	return $message;
}


add_shortcode('getMessage', 'get_data');

function wpdocs_footag_func( $atts , $pttr) {
	return  "foo variable" .$atts['id']  .$atts['name'] ;
}

add_shortcode( 'footag', 'wpdocs_footag_func' );


// $args = array(
// 	'post_type' => 'pages',
// 	'posts_per_page' => $number,
// 	'order' => $sort_by,
// 	'orderby' => 'title',
// 	'post_status' => 'publish',
// 	'tag' => $tags,
// 	'ignore_sticky_posts' => 1,
// 	);
// $args['tax_query'] =  array(
// 		array(
// 		'taxonomy' => 'post_format',
// 		'field' => 'slug',
// 		'terms' => 'post-format-video',
// 		));
// $query = new WP_Query($args);



// function get_allpost(){
// 	$args = array(
// 	'post_type' => 'post'
// 	);

// 	$query = new WP_Query($args);
// 	print_r($query);
// }

// add_shortcode('all_post', 'get_allpost');


// function get_allpost(){
// 	$args = array(
// 	'post_type' => 'jobpost'
// 	);

// 	$query = new WP_Query($args);
	
// 	print_r($query);
// }


function get_all_data(){
		
	$args = array(
		'post_type'=> 'post',
		'orderby'    => 'ID',
		'post_status' => 'publish',
		'order'    => 'DESC',
		'posts_per_page' => -1 // this will retrive all the post that is published 
		);
		$result = new WP_Query( $args );
		$data="";
		if ( $result-> have_posts() ) : ?>
		<?php while ( $result->have_posts() ) : $result->the_post(); ?>
		<?php $data .= get_the_title(); ?>   
		<?php endwhile; return $data; ?>
		<?php endif; wp_reset_postdata(); 	
}

add_shortcode('all_post', 'get_all_data');






// $args = array(
// 	'post_type' => 'pages',
// 	'posts_per_page' => $number,
// 	'order' => $sort_by,
// 	'orderby' => 'title',
// 	'post_status' => 'publish',
// 	'tag' => $tags,
// 	'ignore_sticky_posts' => 1,
// 	);
// $args['tax_query'] =  array(
// 		array(
// 		'taxonomy' => 'post_format',
// 		'field' => 'slug',
// 		'terms' => 'post-format-video',
// 		));
// $query = new WP_Query($args);


<?php

//Require Custom Location Files
require_once( get_stylesheet_directory(). '/inc/location-acf.php' );
require_once( get_stylesheet_directory(). '/inc/maps-route.php' );

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}

function custom_demo_files()
{
    wp_enqueue_script('main-demo-js', get_theme_file_uri('/build/index.js'), array(), '1.0', true);

    wp_localize_script('main-demo-js', 'php_to_js', [
        'data' => array(
            'theme_uri' => get_theme_file_uri(),
            'root_url' => get_site_url()
        )
    ]);

    wp_enqueue_style('university_main_style', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_style', get_theme_file_uri('/build/index.css'));


};

add_action('wp_enqueue_scripts', 'custom_demo_files');

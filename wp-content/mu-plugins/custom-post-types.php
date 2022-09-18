<?php

function wordpress_acf_leaflet_create_custom_post_types()
{
    //Event Post Type
    $event_labels = array(
        'name' => __('Events'),
        'singular_name' => __('Event'),
        'all_items' => __('All Events'),
        'add_new' => _x('Add new Event', 'Event'),
        'add_new_item' => __('Add new Event'),
        'edit_item' => __('Edit Event'),
        'new_item' => __('New Event'),
        'view_item' => __('View Event'),
        'search_items' => __('Search in Events'),
        'not_found' =>  __('No Events found'),
        'not_found_in_trash' => __('No Events found in trash'),
        'parent_item_colon' => ''
    );

    $event_args = array(
        'labels' => $event_labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar',
        'rewrite' => array('slug' => 'events'),
        'query_var' => true,
        'menu_position' => 5,
        'supports'    => array('excerpt', 'title', 'editor', 'thumbnail')
    );

    register_post_type('event', $event_args);

}

add_action('init', 'wordpress_acf_leaflet_create_custom_post_types');

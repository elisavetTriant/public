<?php

add_action('rest_api_init', 'demoRegisterMaps');

function demoRegisterMaps()
{
    register_rest_route('demo/v1', 'maps', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'query_posts_and_pages_with_params'
    ));
};

function query_posts_and_pages_with_params(WP_REST_Request $request)
{
    $args = array();
    $args['post_type'] = $request->get_param('type');
    $args['id'] = $request->get_param('id');

    return query_posts_and_pages($args);
}

function query_posts_and_pages($args)
{

    if ($args['post_type'] == 'page' && !$args['id']) {

        $query_args = array(
            'page_id' => $args['id'],
            'post_type' => $args['post_type'],
            'nopaging' => true,
            'order' => 'ASC',
            'orderby' => 'title',
        );
    } elseif ($args['post_type'] == 'page' && $args['id']) {
        $query_args = array(
            'page_id' => $args['id'],
            'nopaging' => true,
            'order' => 'ASC',
            'orderby' => 'title',
        );
    } elseif ($args['post_type'] != 'page' && !$args['id']) {
        $query_args = array(
            'post_type' => $args['post_type'],
            'nopaging' => true,
            'order' => 'ASC',
            'orderby' => 'title',
        );
    } elseif ($args['post_type'] != 'page' && $args['id']) {
        $query_args = array(
            'p' => $args['id'],
            'post_type' => $args['post_type'],
            'nopaging' => true,
            'order' => 'ASC',
            'orderby' => 'title',
        );
    }


    return fetchByPostTypeAndID($query_args);
}

function fetchByPostTypeAndID($query_args)
{
    // Run a custom query
    $meta_query = new WP_Query($query_args);
    if ($meta_query->have_posts()) {
        //Define an empty array
        $data = array();
        // Store each post's data in the array
        while ($meta_query->have_posts()) {
            $meta_query->the_post();

            if (get_field('location_latitude') && get_field('location_longitude')) {
                $post_object = (object) [
                    'id' => get_the_ID(),
                    'title' => (object) ['rendered' => get_the_title()],
                    'link' => get_the_permalink(),
                    'location' => (object) [
                        'latitude' => get_field('location_latitude'),
                        'longitude' => get_field('location_longitude'),
                        'address' => get_field('location_address')
                    ]
                ];
                $data[] = $post_object;
            }
        }
        // Return the data
        return $data;
    } else {
        // If there is no post
        return new WP_Error('rest_not_found', esc_html__('Error fetching data.', 'twentytwentytwochild'), array('status' => 404));
    }
}
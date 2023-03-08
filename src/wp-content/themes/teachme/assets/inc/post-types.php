<?php
add_action( 'init', 'create_taxonomy' );
function create_taxonomy(){

    register_taxonomy( 'category_news', [ 'news' ], [
        'label'                 => 'Category News',
        'labels'                => [
            'name'              => 'Category News',
            'singular_name'     => 'Category News',
            'search_items'      => 'Search Category News',
            'all_items'         => 'All Category News',
            'view_item '        => 'View Category News',
            'parent_item'       => 'Parent Category News',
            'parent_item_colon' => 'Parent Category News:',
            'edit_item'         => 'Edit Category News',
            'update_item'       => 'Update Category News',
            'add_new_item'      => 'Add New Category News',
            'new_item_name'     => 'New Category News Name',
            'menu_name'         => 'Category News',
        ],
        'description'           => '', 
        'public'                => true,
        'publicly_queryable'    => null, 
        'show_in_nav_menus'     => true, 
        'show_ui'               => true, 
        'show_in_menu'          => true, 
        'show_tagcloud'         => true, 
        'show_in_quick_edit'    => null, 
        'hierarchical'          => true,
        // 'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'category_news' ),
    ] );
}

add_action('init', 'register_post_news');
function register_post_news(){
    $labels = array(
            'name' => __('News'), 
            'singular_name' => __('News'),
            'menu_name' => __('News'), 
            'all_items' => __('All Items'),           
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Item'),
            'edit_item' => __('Edit Item'),
            'view_item' => __('View Item'),
            'search_items' => __('Search Item'),
            'not_found' =>  __('Not Found'),
            'not_found_in_trash' => __('Not Found In Trash'),
            'new_item' => __('New Item'),
            );
    $supports = array(
            'title',
            'thumbnail',
            'excerpt',
            'editor',

            );
    $rewrite = array(
            'slug' => 'news',
            'with_front' => false,
            'pages' => true,
            );
    $args = array(
            'labels' => $labels,
            'supports' => $supports,
            'rewrite' => $rewrite,
            'label' => 'news',
            'public' => true,
            'publicly_queryable' => true,
            // 'excerpt' => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'show_in_admin_bar' => true,
            'show_in_menu' => true,
            'menu_position' => 10,
            'menu_icon' => 'dashicons-businessperson',
            'hierarchical' => false,
            'has_archive' => true,
            'query_var' => true,
            'taxonomies' => array( 'category_news' ),    
    );
  register_post_type('news',$args);

add_filter( 'enter_title_here', 'enter_news_name', 10, 2 );
function enter_news_name( $text, $post ) {
    if ( $post->post_type === 'case' ) {
        $text = __('News Case' );
    }
    return $text;
    }
}
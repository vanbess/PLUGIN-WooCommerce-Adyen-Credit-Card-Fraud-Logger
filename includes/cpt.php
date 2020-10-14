<?php
// custom post type to capture fraudelent checkout attempts data

// Register Custom Post Type
function sbadaf_cpt()
{

    $labels = array(
        'name'                  => _x('Fraud Logs', 'Post Type General Name', 'sbadaf'),
        'singular_name'         => _x('Fraud Log', 'Post Type Singular Name', 'sbadaf'),
        'menu_name'             => __('Fraud Logs', 'sbadaf'),
        'name_admin_bar'        => __('Fraud Log', 'sbadaf'),
        'archives'              => __('Fraud Log Archives', 'sbadaf'),
        'attributes'            => __('Fraud Log Attributes', 'sbadaf'),
        'parent_item_colon'     => __('Parent Item:', 'sbadaf'),
        'all_items'             => __('All fraud logs', 'sbadaf'),
        'add_new_item'          => __('Add New Fraud Log', 'sbadaf'),
        'add_new'               => __('Add New', 'sbadaf'),
        'new_item'              => __('New Fraud Log', 'sbadaf'),
        'edit_item'             => __('Edit Fraud Log', 'sbadaf'),
        'update_item'           => __('Update Fraud Log', 'sbadaf'),
        'view_item'             => __('View Fraud Log', 'sbadaf'),
        'view_items'            => __('View Fraud Logs', 'sbadaf'),
        'search_items'          => __('Search Fraud Log', 'sbadaf'),
        'not_found'             => __('Not found', 'sbadaf'),
        'not_found_in_trash'    => __('Not found in Trash', 'sbadaf'),
        'featured_image'        => __('Featured Image', 'sbadaf'),
        'set_featured_image'    => __('Set featured image', 'sbadaf'),
        'remove_featured_image' => __('Remove featured image', 'sbadaf'),
        'use_featured_image'    => __('Use as featured image', 'sbadaf'),
        'insert_into_item'      => __('Insert into item', 'sbadaf'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'sbadaf'),
        'items_list'            => __('Items list', 'sbadaf'),
        'items_list_navigation' => __('Items list navigation', 'sbadaf'),
        'filter_items_list'     => __('Filter items list', 'sbadaf'),
    );
    $args = array(
        'label'                 => __('Fraud Log', 'sbadaf'),
        'description'           => __('Custom post type to capture fraudulent checkout attempts using Adyen payment gateway credit cards payment method', 'sbadaf'),
        'labels'                => $labels,
        'supports'              => array('title', 'custom-fields'),
        'taxonomies'            => array('category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-superhero',
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => false,
    );
    register_post_type('fraud_log', $args);
}
add_action('init', 'sbadaf_cpt', 0);

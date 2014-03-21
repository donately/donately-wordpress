<?php
/**
 * Post Type Functions
 *
 * @package     Donately
 * @subpackage  Campaign Post Types
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers and sets up the Downloads custom post type
 *
 * @since 1.0
 * @return void
 */
function setup_dntly_campaign_post_types() {
    global $dntly_settings;
    $archives = defined( 'DNTLY_CAMPAIGNS_DISABLE_ARCHIVE' ) && DNTLY_CAMPAIGNS_DISABLE_ARCHIVE ? false : true;

    if( !empty( $dntly_settings['campaign_slug'] ) ) {
        $slug = defined( 'DNTLY_CAMPAIGNS_SLUG' ) ? DNTLY_CAMPAIGNS_SLUG : $dntly_settings['campaign_slug'];
    } else {
        $slug = defined( 'DNTLY_CAMPAIGNS_SLUG' ) ? DNTLY_CAMPAIGNS_SLUG : 'campaigns';
    }

    $rewrite  = defined( 'DNTLY_CAMPAIGNS_DISABLE_REWRITE' ) && DNTLY_CAMPAIGNS_DISABLE_REWRITE ? false : array('slug' => $slug, 'with_front' => false);

    $dntly_campaign_labels =  apply_filters( 'dntly_campaign_labels', array(
        'name'              => '%2$s',
        'singular_name'     => '%1$s',
        'add_new'           => __( 'Add New', 'dntly' ),
        'add_new_item'      => __( 'Add New %1$s', 'dntly' ),
        'edit_item'         => __( 'Edit %1$s', 'dntly' ),
        'new_item'          => __( 'New %1$s', 'dntly' ),
        'all_items'         => __( '%2$s', 'dntly' ),
        'view_item'         => __( 'View %1$s', 'dntly' ),
        'search_items'      => __( 'Search %2$s', 'dntly' ),
        'not_found'         => __( 'No %2$s found', 'dntly' ),
        'not_found_in_trash'=> __( 'No %2$s found in Trash', 'dntly' ),
        'parent_item_colon' => '',
        'menu_name'         => __( '%2$s', 'dntly' )
    ) );

    foreach ( $dntly_campaign_labels as $key => $value ) {
       $dntly_campaign_labels[ $key ] = sprintf( $value, dntly_campaigns_get_label_singular(), dntly_campaigns_get_label_plural() );
    }

    $dntly_campaigns_args = array(
        'labels'            => $dntly_campaign_labels,
        'public'            => true,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_in_menu'      => 'dntly',
        'menu_icon'         => 'dashicons-groups',
        'menu_position'     => '152.55',
        'query_var'         => true,
        'rewrite'           => $rewrite,
        'map_meta_cap'      => true,
        'has_archive'       => $archives,
        'show_in_nav_menus' => true,
        'hierarchical'      => false,
        'supports'          => apply_filters( 'dntly_campaign_supports', array( 'title', 'editor', 'thumbnail', 'excerpt' ) ),
    );
    register_post_type( 'dntly_campaigns', apply_filters( 'dntly_campaigns_post_type_args', $dntly_campaigns_args ) );
    
}
add_action( 'init', 'setup_dntly_campaign_post_types', 1 );

/**
 * Get Default Labels
 *
 * @since 1.0.8.3
 * @return array $defaults Default labels
 */
function dntly_campaigns_get_default_labels() {
    global $dntly_settings;

    if( !empty( $dntly_settings['campaigns_label_plural'] ) || !empty( $dntly_settings['campaigns_label_singular'] ) ) {
        $defaults = array(
           'singular' => $dntly_settings['campaigns_label_singular'],
           'plural' => $dntly_settings['campaigns_label_plural']
        );

    } else{
        $defaults = array(
           'singular' => __( 'Campaign', 'dntly' ),
           'plural' => __( 'Campaigns', 'dntly')
        );        
    }
    return apply_filters( 'dntly_campaigns_default_name', $defaults );
}

/**
 * Get Singular Label
 *
 * @since 1.0.8.3
 * @return string $defaults['singular'] Singular label
 */
function dntly_campaigns_get_label_singular( $lowercase = false ) {
    $defaults = dntly_campaigns_get_default_labels();
    return ($lowercase) ? strtolower( $defaults['singular'] ) : $defaults['singular'];
}

/**
 * Get Plural Label
 *
 * @since 1.0.8.3
 * @return string $defaults['plural'] Plural label
 */
function dntly_campaigns_get_label_plural( $lowercase = false ) {
    $defaults = dntly_campaigns_get_default_labels();
    return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
}

/**
 * Change default "Enter title here" input
 *
 * @since 1.4.0.2
 * @param string $title Default title placeholder text
 * @return string $title New placeholder text
 */
function dntly_campaigns_change_default_title( $title ) {
     $screen = get_current_screen();

     if  ( 'dntly_campaigns' == $screen->post_type ) {
        $label = dntly_campaigns_get_label_singular();
        $title = sprintf( __( 'Enter %s title here', 'dntly' ), $label );
     }

     return $title;
}
add_filter( 'enter_title_here', 'dntly_campaigns_change_default_title' );

/**
 * Registers the custom taxonomies for the downloads custom post type
 *
 * @since 1.0
 * @return void
*/
function dntly_campaigns_setup_taxonomies() {

    $slug     = defined( 'DNTLY_CAMPAIGNS_SLUG' ) ? DNTLY_CAMPAIGNS_SLUG : 'campaigns';

    /** Categories */
    $category_labels = array(
        'name'              => sprintf( _x( '%s Categories', 'taxonomy general name', 'dntly' ), dntly_campaigns_get_label_singular() ),
        'singular_name'     => _x( 'Category', 'taxonomy singular name', 'dntly' ),
        'search_items'      => __( 'Search Categories', 'dntly'  ),
        'all_items'         => __( 'All Categories', 'dntly'  ),
        'parent_item'       => __( 'Parent Category', 'dntly'  ),
        'parent_item_colon' => __( 'Parent Category:', 'dntly'  ),
        'edit_item'         => __( 'Edit Category', 'dntly'  ),
        'update_item'       => __( 'Update Category', 'dntly'  ),
        'add_new_item'      => __( 'Add New Category', 'dntly'  ),
        'new_item_name'     => __( 'New Category Name', 'dntly'  ),
        'menu_name'         => __( 'Categories', 'dntly'  ),
    );

    $category_args = apply_filters( 'dntly_campaigns_category_args', array(
            'hierarchical'      => true,
            'labels'            => apply_filters('dntly_campaigns_category_labels', $category_labels),
            'show_ui'           => true,
            'query_var'         => 'campaign_category',
            'rewrite'           => array('slug' => $slug . '/category', 'with_front' => false, 'hierarchical' => true ),
            'capabilities'      => array( 'manage_terms','edit_terms', 'assign_terms', 'delete_terms' ),
            'show_admin_column' => true
        )
    );
    register_taxonomy( 'campaign_category', array('dntly_campaigns'), $category_args );
    register_taxonomy_for_object_type( 'campaign_category', 'dntly_campaigns' );

    /** Tags */
    $tag_labels = array(
        'name'              => sprintf( _x( '%s Tags', 'taxonomy general name', 'dntly' ), dntly_campaigns_get_label_singular() ),
        'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'dntly' ),
        'search_items'      => __( 'Search Tags', 'dntly'  ),
        'all_items'         => __( 'All Tags', 'dntly'  ),
        'parent_item'       => __( 'Parent Tag', 'dntly'  ),
        'parent_item_colon' => __( 'Parent Tag:', 'dntly'  ),
        'edit_item'         => __( 'Edit Tag', 'dntly'  ),
        'update_item'       => __( 'Update Tag', 'dntly'  ),
        'add_new_item'      => __( 'Add New Tag', 'dntly'  ),
        'new_item_name'     => __( 'New Tag Name', 'dntly'  ),
        'menu_name'         => __( 'Tags', 'dntly'  ),
    );

    $tag_args = apply_filters( 'dntly_campaigns_tag_args', array(
            'hierarchical'  => false,
            'labels'        => apply_filters( 'dntly_campaigns_tag_labels', $tag_labels ),
            'show_ui'       => true,
            'query_var'     => 'campaigns_tag',
            'rewrite'       => array( 'slug' => $slug . '/tag', 'with_front' => false, 'hierarchical' => true  ),
            'capabilities'  => array( 'manage_terms' => 'manage_product_terms','edit_terms' => 'edit_product_terms','assign_terms' => 'assign_product_terms','delete_terms' => 'delete_product_terms' )

        )
    );
    register_taxonomy( 'campaigns_tag', array( 'dntly_campaigns' ), $tag_args );
    register_taxonomy_for_object_type( 'campaigns_tag', 'dntly_campaigns' );

}
add_action( 'init', 'dntly_campaigns_setup_taxonomies', 0 );



/**
 * Updated Messages
 *
 * Returns an array of with all updated messages.
 *
 * @since 1.0
 * @param array $messages Post updated message
 * @return array $messages New post updated messages
 */
function dntly_campaigns_updated_messages( $messages ) {
    global $post, $post_ID;

    $url1 = '<a href="' . get_permalink( $post_ID ) . '">';
    $url2 = dntly_campaigns_get_label_singular();
    $url3 = '</a>';

    $messages['dntly_campaigns'] = array(
        1 => sprintf( __( '%2$s updated. %1$sView %2$s%3$s.', 'dntly' ), $url1, $url2, $url3 ),
        4 => sprintf( __( '%2$s updated. %1$sView %2$s%3$s.', 'dntly' ), $url1, $url2, $url3 ),
        6 => sprintf( __( '%2$s published. %1$sView %2$s%3$s.', 'dntly' ), $url1, $url2, $url3 ),
        7 => sprintf( __( '%2$s saved. %1$sView %2$s%3$s.', 'dntly' ), $url1, $url2, $url3 ),
        8 => sprintf( __( '%2$s submitted. %1$sView %2$s%3$s.', 'dntly' ), $url1, $url2, $url3 )
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'dntly_campaigns_updated_messages' );

add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );
function adjust_the_wp_menu() {
  $page = remove_submenu_page( 'edit.php?post_type=dntly_campaigns', 'post-new.php?post_type=dntly_campaigns' );
  // $page[0] is the menu title
  // $page[1] is the minimum level or capability required
  // $page[2] is the URL to the item's file
}

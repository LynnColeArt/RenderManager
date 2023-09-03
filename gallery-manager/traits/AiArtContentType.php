<?php

trait AiArtContentType
{
    public function add_plugin_page(): void
    {
        add_submenu_page(
            'edit.php?post_type=ai_art_gallery',
            'Gallery Manager',
            'Gallery Manager',
            'manage_options',
            'gallery-manager',
            [$this, 'gallery_manager_page']
        );
    }

    public function register_ai_art_content_type(): void
    {
        $labels = [
            'name'               => _x('Ai Renders', 'post type general name'),
            'singular_name'      => _x('Ai Renders', 'post type singular name'),
            'add_new'            => _x('Add New', 'Ai Render'),
            'add_new_item'       => __('Add New Ai Render'),
            'edit_item'          => __('Edit Ai Render'),
            'new_item'           => __('New Ai Render'),
            'all_items'          => __('All AI Art'),
            'view_item'          => __('View Ai Renders'),
            'search_items'       => __('Search AI Art'),
            'not_found'          => __("Sorry, couldn't find it"),
            'not_found_in_trash' => __("Sorry, couldn't find it in the Trash"),
            'parent_item_colon'  => '',
            'menu_name'          => 'Ai Renders',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'menu_position'      => 5,
            'supports'           => ['title', 'editor', 'thumbnail'],
            'has_archive'        => true,
            'rewrite'            => ['slug' => 'ai-art-gallery'],
            'show_in_rest'       => true,
        ];

        register_post_type('ai_art_gallery', $args);
        
    }
}
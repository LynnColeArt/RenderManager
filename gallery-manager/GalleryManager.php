<?php
/**
 * Plugin Name: Render Manager
 * Plugin URI: hhttps://github.com/LynnColeArt/RenderManager
 * Description: This plugin helps you manage your ai art pretties in Wordpress.
 * Version: 1.0.0
 * Author: Lynn Cole
 * Author URI: https://www.lynncole.art
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: gallery-manager
 * Domain Path: /languages
 */

require ("traits/HelperFunctions.php");
require ("libs/PngReader.php");
require ("traits/ImageParser.php");
require ("traits/AiArtContentType.php");
require ("traits/AiArtPresentation.php");
require ("traits/AttachmentHandler.php");
require ("traits/FormHandler.php");
require ("traits/Webforms.php");
require ("traits/CustomBlocks.php");

class GalleryManager {
	
    use HelperFunctions;
    use ImageParser;
    use AiArtContentType;
    use AiArtPresentation;
    use AttachmentHandler;
	use FormHandler;
    use AiArtContentType;
    use Webforms;
    use CustomBlocks;

    public function enqueue_scripts(): void {
        wp_enqueue_script ('gallery-manager-script', plugin_dir_url(__FILE__) . 'functions.js', array('jquery'), '1.0', true);
      //  wp_enqueue_style ('gallery-manager-script', plugin_dir_url( __FILE__ ) . 'base.css' );
    }

    public function __construct(){
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('init', array($this, 'register_ai_art_content_type'));
        add_action('admin_post_gallery_manager', array($this, 'handle_form_submission'));
        add_action('admin_init', [$this, 'customize_columns']);
        add_action('admin_init', [$this, 'add_ai_art_gallery_meta_boxes']);
        add_filter('manage_ai_art_gallery_posts_columns', [$this, 'add_custom_columns']);
        add_action('manage_ai_art_gallery_posts_custom_column', [$this, 'render_custom_columns'], 10, 2);
        add_action('save_post', [$this, 'save_ai_art_gallery_meta']);
       // add_action( 'admin_enqueue_scripts', [$this, 'load_admin_base_css' ]);
    }
    
}

$gallery_manager = new GalleryManager();
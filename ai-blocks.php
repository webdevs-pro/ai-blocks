<?php
/**
 * Plugin Name: AI Blocks
 * Description: AI custom blocks.
 * Plugin URI:  http://web-devs.pro/
 * Version:     1.5.4
 * Author:      web-devs.pro
 * Text Domain: ai-blocks
 */


if (!defined('AI_BLOCKS_PLUGIN_DIR')) {
   define('AI_BLOCKS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}


// ADMIN
include( AI_BLOCKS_PLUGIN_DIR . 'admin/admin.php');




// BLOCKS CSS AND JS
function ai_blocks_styles() {
   wp_enqueue_style('ai-blocks-styles', plugin_dir_url( __FILE__ ) . 'assets/ai-blocks-styles.css' );
   //wp_enqueue_script( 'ai-blocks-script', plugin_dir_url( __FILE__ ) . 'assets/ai-blocks-script.js' , array(), '1.0.0', true, ['wp-blocks'] );
}
add_action( 'enqueue_block_assets', 'ai_blocks_styles' );




// GUTENBERG EDITOR TWEAKS
function ai_blocks_editor_styles() {
   wp_enqueue_style('ai-blocks-styles', plugin_dir_url( __FILE__ ) . 'assets/ai-blocks-editor.css' );
}
add_action( 'enqueue_block_editor_assets', 'ai_blocks_editor_styles' );



// AI BLOCKS CATEGORY
add_filter( 'block_categories', function( $categories, $post ) {

   return array_merge(
       $categories,
       array(
           array(
               'slug'  => 'ai-blocks',
               'title' => 'AI Blocks',
           ),
       )
   );
}, 10, 2 );



// PLUGIN UPDATES
if (is_admin()) {
   require 'admin/plugin-update-checker/plugin-update-checker.php';
   $ai_blocks_updater = Puc_v4_Factory::buildUpdateChecker(
      'https://github.com/webdevs-pro/ai-blocks',
      __FILE__,
      'ai-blocks'
   );
}


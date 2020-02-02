<?php
// IMAGES GALLERY BLOCK
// register fields 
if( function_exists('acf_add_local_field_group') ) {
   acf_add_local_field_group(array(
      'key' => 'group_ai_image_gallery_block',
      'title' => '',
      'fields' => array(
         array(
            'key' => 'field_ai_image_gallery_block',
            'label' => __('Image Gallery', 'ai-blocks'),
            'name' => 'ai_image_gallery_images',
            'type' => 'gallery',
            'instructions' => __('Add imiges to gallery', 'ai-blocks'),
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
         ),
      ),
      'location' => array(
         array(
            array(
               'param' => 'block',
               'operator' => '==',
               'value' => 'acf/ai-image-gallery-block',
            ),
         ),
      ),
   ));
}

// acf gallery field custom styling
add_action('enqueue_block_editor_assets', function() {
   echo '<style type="text/css">
      .acf-block-fields .acf-gallery {
         height: auto !important;
     }
     .acf-block-fields .acf-gallery .acf-gallery-main {
         position: relative !important;
     }
     .acf-block-fields .acf-gallery .acf-gallery-attachments {
         position: relative !important;
         min-height: 300px;
     }
     .acf-block-fields .acf-gallery .acf-gallery-toolbar {
         position: relative !important;
     } 
   </style>';
});

// register block
if( function_exists('acf_register_block_type') ) {
   add_action('acf/init', function() {
      acf_register_block_type(array(
         'name' => 'ai-image-gallery-block',
         'title' => __('AI Image Gallery', 'ai-blocks'),
         'description' => __('This block allow to you to display gallery of images.', 'ai-blocks'),
         'render_callback' => 'ai_image_gallery_block_render_callback',
         'icon' => array(
            'src' => 'format-gallery'
         ),
         //'category' => 'ai-blocks',
         'category' => 'common',
         'mode' => 'edit',
         'supports' => array(
           'align' => false,
           'mode' => false,
           'reusable' => false,
           'html' => false,
        ),
     ));
   });
}

// render block
function ai_image_gallery_block_render_callback() {
   $gallery = get_field('ai_image_gallery_images');
   if ($gallery) {
      echo "<div class='ai_image_gallery_block ai_block'>";
         echo '<div class="ai_block_container">';
            echo '<div class="ai_block_gallery">';

               $gallery_shortcode = '[gallery ids="';
               foreach( $gallery as $image ) {
                  $gallery_shortcode .= $image['id'] . ',';
               }
               $gallery_shortcode .= '"]';
               echo do_shortcode($gallery_shortcode);
            echo '</div>';

         echo '</div>';
      echo "</div>";
   }
}
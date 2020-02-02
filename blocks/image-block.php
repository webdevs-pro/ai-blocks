<?php
// IMAGE BLOCK
// register fields
if( function_exists('acf_add_local_field_group') ) {

   acf_add_local_field_group(array(
      'key' => 'group_ai_image_block',
      'title' => '',
      'fields' => array(
         array(
            'key' => 'field_ai_image_block_image',
            'label' => __('Image', 'ai-blocks'),
            'name' => 'ai_image',
            'type' => 'image',
            'instructions' => '',
            'return_format' => 'array',
            'preview_size' => 'full',
         ),
         array(
            'key' => 'field_ai_image_block_description',
            'label' => __('Image description', 'ai-blocks'),
            'name' => 'ai_image_description',
            'type' => 'text',
            'instructions' => __('Text displayed under image', 'ai-blocks'),
         ),
      ),
      'location' => array(
         array(
            array(
               'param' => 'block',
               'operator' => '==',
               'value' => 'acf/ai-image-block',
            ),
         ),
      ),
   ));
   
};


// register block
if( function_exists('acf_register_block_type') ) {
   add_action('acf/init', function() {
      acf_register_block_type(array(
         'name' => 'ai-image-block',
         'title' => __('AI Image', 'ai-blocks'),
         'description' => __('Image block with description.', 'ai-blocks'),
         'render_callback' => 'ai_image_block_render_callback',
         'icon' => array(
            'src' => 'format-image',
            'foreground' => '#000',
         ),
         //'category' => 'ai-blocks',
         'category' => '',
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
function ai_image_block_render_callback() {
   $image_arr = get_field('ai_image');
   $image_description = get_field('ai_image_description');
   if ($image_arr) {
      echo '<div class="ai_image_block ai_block">';
         echo '<div class="ai_block_container">';
            echo '<div class="ai_image_block_wrapper">';
               echo '<div class="ai_image">';
                  echo '<img src="' . $image_arr['url'] . '">';
               echo '</div>';
               echo '<div class="ai_image_description">' . $image_description . '</div>';
            echo '</div>';
         echo '</div>';
      echo '</div>';
   }
}
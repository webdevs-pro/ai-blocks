<?php
// TEXT BLOCK
// register fields
if( function_exists('acf_add_local_field_group') ) {

   acf_add_local_field_group(array(
      'key' => 'group_ai_text_block',
      'title' => '',
      'fields' => array(
         array(
            'key' => 'field_ai_text_block_heading',
            'label' => __('Text block heading', 'ai-blocks'),
            'name' => 'ai_text_heading',
            'type' => 'text',
            // 'instructions' => __('Text block heading', 'ai-blocks'),
         ),
         array(
            'key' => 'field_ai_text_block_content',
            'label' => __('Text block content', 'ai-blocks'),
            'name' => 'ai_text_content',
            'type' => 'wysiwyg',
            // 'instructions' => __('Text block content', 'ai-blocks'),
            'tabs' => 'visual',
            'toolbar' => 'basic',
            'media_upload' => 1,
            'delay' => 0,
            'wrapper' => array (
               'class' => 'autosize',
            ),
         ),
      ),
      'location' => array(
         array(
            array(
               'param' => 'block',
               'operator' => '==',
               'value' => 'acf/ai-text-block',
            ),
         ),
      ),
   ));
   
};


// register block
if( function_exists('acf_register_block_type') ) {
   add_action('acf/init', function() {
      acf_register_block_type(array(
         'name' => 'ai-text-block',
         'title' => __('AI Text + Heading', 'ai-blocks'),
         'description' => __('Text block with heading.', 'ai-blocks'),
         'render_callback' => 'ai_text_block_render_callback',
         'icon' => array(
            'src' => 'editor-alignleft',
            'foreground' => '#000',
         ),
         //'category' => 'ai-blocks',
         'category' => '',
         'mode' => 'auto',
         'supports' => array(
           'align' => false,
           'mode' => true,
           'reusable' => false,
           'html' => false,
        ),
     ));
   });
}

// render block
function ai_text_block_render_callback() {
   $title = get_field('ai_text_heading');
   $content = get_field('ai_text_content');
   if ($title || $content) {
      echo '<div class="ai_text_block ai_block">';
         echo '<div class="ai_block_container">';
            echo '<h3 class="ai_text_title">' . $title . '</h3>';
            echo '<div class="ai_text_content">' . $content . '</div>';
         echo '</div>';
      echo '</div>';
   }
}
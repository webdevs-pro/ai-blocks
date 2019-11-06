<?php
// QUOTE BLOCK
// register fields
if( function_exists('acf_add_local_field_group') ) {

   acf_add_local_field_group(array(
      'key' => 'group_ai_quote_block',
      'title' => '',
      'fields' => array(
         array(
            'key' => 'field_ai_quote_block_title',
            'label' => __('Quote title', 'ai-blocks'),
            'name' => 'ai_quote_title',
            'type' => 'text',
            'instructions' => __('Quote title or bible verse number', 'ai-blocks'),
         ),
         array(
            'key' => 'field_ai_quote_block_content',
            'label' => __('Quote content', 'ai-blocks'),
            'name' => 'ai_quote_content',
            'type' => 'textarea',
            'instructions' => __('Quote content or bible verse text', 'ai-blocks'),
            'maxlength' => '',
            'rows' => '',
            'new_lines' => 'br',
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
               'value' => 'acf/ai-quote-block',
            ),
         ),
      ),
   ));
   
};


// register block
if( function_exists('acf_register_block_type') ) {
   add_action('acf/init', function() {
      acf_register_block_type(array(
         'name' => 'ai-quote-block',
         'title' => __('Quote', 'ai-blocks'),
         'description' => __('This block allow to you to show quote or bible verse.', 'ai-blocks'),
         'render_callback' => 'ai_quote_block_render_callback',
         'icon' => array(
            'src' => 'editor-quote',
            'foreground' => '#000',
         ),
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
function ai_quote_block_render_callback() {
   $title = get_field('ai_quote_title');
   $content = get_field('ai_quote_content');
   if ($title || $content) {
      echo '<div class="ai_quote_block">';
         echo '<div class="ai_quote_title">' . $title . '</div>';
         echo '<div class="ai_quote_content">' . $content . '</div>';
      echo '</div>';
   }
}
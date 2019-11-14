<?php
// AUDIO BLOCK
// register fields
if( function_exists('acf_add_local_field_group') ) {

   acf_add_local_field_group(array(
      'key' => 'group_ai_audio_block',
      'title' => '1',
      'fields' => array(
         array(
            'key' => 'field_ai_audio_block_repeater',
            'label' => __('Audio', 'ai-blocks'),
            'name' => 'ai_audio_repeater',
            'type' => 'repeater',
            'instructions' => __('Add track to playlist', 'ai-blocks'),
            'collapsed' => '',
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => __('Add audio track', 'ai-blocks'),
            'sub_fields' => array(
               array(
                  'key' => 'field_ai_audio_block_track',
                  'label' => '',
                  'name' => 'ai_audio_track',
                  'type' => 'file',
                  'instructions' => '',
                  'return_format' => 'array',
                  'library' => 'all',
                  'mime_types' => 'mp3,m4a,ogg,wav',
               ),
            ),
         ),
      ),
      'location' => array(
         array(
            array(
               'param' => 'block',
               'operator' => '==',
               'value' => 'acf/ai-audio-block',
            ),
         ),
      ),
   ));
   
};


// register block
if( function_exists('acf_register_block_type') ) {
   add_action('acf/init', function() {
      acf_register_block_type(array(
         'name' => 'ai-audio-block',
         'title' => __('Audio', 'ai-blocks'),
         'description' => __('Single audio or music album player block with description.', 'ai-blocks'),
         'render_callback' => 'ai_audio_block_render_callback',
         'icon' => array(
            'src' => 'format-audio',
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
function ai_audio_block_render_callback() {
   $image_arr = get_field('ai_image');
   $image_description = get_field('ai_image_description');
   if ($image_arr) {
      echo '<div class="ai_audio_block ai_block">';
         echo '<div class="ai_block_container">';
         
         echo '</div>';
      echo '</div>';
   }
}
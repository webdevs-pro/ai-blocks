<?php
// YOUTUBE VIDEO BLOCK
// register fields
if( function_exists('acf_add_local_field_group') ) {
   acf_add_local_field_group(array(
      'key' => 'group_ai_yt_video_block',
      'title' => '',
      'fields' => array(
         array(
            'key' => 'field_ai_yt_video_block_url',
            'label' => __('YouTube Video', 'ai-blocks'),
            'name' => 'ai_yt_video_block_url',
            'type' => 'url',
            'instructions' => __('Paste link to YouTube video here', 'ai-blocks'),
         ),
      ),
      'location' => array(
         array(
            array(
               'param' => 'block',
               'operator' => '==',
               'value' => 'acf/ai-yt-video-block',
            ),
         ),
      ),
   ));
}

// register block
if( function_exists('acf_register_block_type') ) {
   add_action('acf/init', function() {
      acf_register_block_type(array(
         'name' => 'ai-yt-video-block',
         'title' => __('YouTube Video', 'ai-blocks'),
         'description' => __('This block allow to you to embed responsive (16:9 aspect ratio) video from YouTube.', 'ai-blocks'),
         'render_callback' => 'ai_yt_video_block_render_callback',
         'icon' => array(
            'src' => 'video-alt3',
            'foreground' => '#FF0000',
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
function ai_yt_video_block_render_callback() {
   preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', get_field('ai_yt_video_block_url'), $ai_yt_video_id);
   if( array_key_exists(1, $ai_yt_video_id) ) {
      echo '<div class="ai_video_block">';
         echo '<div class="ai_block_container ai_block">';
            echo '<div class="ai_video_wrapper" style="position: relative; padding-bottom: 56.25%;">';
               echo '<iframe style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;" width="1280" height="720" src="https://www.youtube.com/embed/' . $ai_yt_video_id[1] . '" frameborder="0" allowfullscreen></iframe>';
            echo '</div>';
         echo '</div>';   
      echo '</div>';
   }
}
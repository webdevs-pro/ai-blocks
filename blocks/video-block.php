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
            'type' => 'oembed',
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
         'title' => __('AI YouTube Video', 'ai-blocks'),
         'description' => __('This block allow to you to embed responsive (16:9 aspect ratio) video from YouTube.', 'ai-blocks'),
         'render_callback' => 'ai_yt_video_block_render_callback',
         'icon' => array(
            'src' => 'video-alt3',
            'foreground' => '#FF0000',
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
        'enqueue_assets' => function(){
            $options = get_option('ai_blocks_settings');
            if (isset($options['ai_youtube_block']['api_key']) && $options['ai_youtube_block']['api_key'] != '') {
               wp_enqueue_script( 'ai-video-block-script', plugin_dir_url( __FILE__ ) . '../assets/ai-video-block-script.js', array('jquery'), '', true );
            }
         },
     ));
   });
}



// render block
function ai_yt_video_block_render_callback($block) {

   if( $block['data']['ai_yt_video_block_url'] ) {

      $options = get_option('ai_blocks_settings');

      

      if (isset($options['ai_youtube_block']['api_key']) && $options['ai_youtube_block']['api_key'] != '') {

         $video = AIgetYouTubeVideoID($block['data']['ai_yt_video_block_url']);


         // TO DO results per page
         if (isset($video['type']) && $video['type'] == 'single') {
            $api_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=' . $video['id'] . '&key=' . $options['ai_youtube_block']['api_key'];
         } elseif (isset($video['type']) && $video['type'] == 'list' ) {
            $api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=25&playlistId='. $video['id'] . '&key=' . $options['ai_youtube_block']['api_key'];
         }

         $json_result = file_get_contents($api_url);

         if ($json_result === false) {
            goto noapi;
         }

         $data = json_decode($json_result, true);

         ?>
            <div class="ai_video_block ai_block">

               <div class="youtube-wrapper">

                  <div class="youtube_player">

                     <div class="video_wrap" id="video-<?php echo $block['id']; ?>" data-video-type="<?php echo $video['type']; ?>" data-video-id="<?php echo isset($data['items'][0]['snippet']['resourceId']['videoId']) ? $data['items'][0]['snippet']['resourceId']['videoId'] : $data['items'][0]['id']; ?>">


                        <?php
                           // get main video thumbnail
                           if (isset($data['items'][0]['snippet']['thumbnails']['maxres']['url'])) {
                              $url = $data['items'][0]['snippet']['thumbnails']['maxres']['url'];
                           } elseif (isset($data['items'][0]['snippet']['thumbnails']['standard']['url'])) {
                              $url = $data['items'][0]['snippet']['thumbnails']['standard']['url'];
                           } elseif (isset($data['items'][0]['snippet']['thumbnails']['high']['url'])) {
                              $url = $data['items'][0]['snippet']['thumbnails']['high']['url'];
                           } else {
                              $url = '';
                           }
                        ?>
               
                        <img src="<?php echo $url; ?>" id="video-thumb-img"/>
                        <div class="video-title"><?php echo $data['items'][0]['snippet']['title']; ?></div>
                        <button class="ytp-large-play-button" aria-label="Смотреть"><svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#212121" fill-opacity="0.8"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg></button>
                     </div>
                  </div>



                  <?php if (count($data['items']) > 1) { ?>

                     <div class="video-playlist">

                        <?php foreach($data['items'] as $item) { ?>

                           <div class="video-playlist-item" data-video-id="<?php echo $item['snippet']['resourceId']['videoId'] ?>" data-player-id="<?php echo $block['id']; ?>">

                              <div class="video-playlist-item-thumb">
                                 <button class="ytp-large-play-button" aria-label="Смотреть"><svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#212121" fill-opacity="0.8"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg></button>
                                 <img class="video-playlist-item-image" src="<?php echo $item['snippet']['thumbnails']['medium']['url']; ?>" />
                              </div>

                              <div class="video-playlist-item-title"><?php echo $item['snippet']['title']; ?></div>

                           </div>

                        <?php } ?>

                     </div>

                  <?php } ?>

               </div>

            </div>

         <?php

      } else {
         noapi:
         $video = AIgetYouTubeVideoID($block['data']['ai_yt_video_block_url']);
         echo '<div class="ai_video_block ai_block">';
               echo '<div class="youtube_player">';
               if ($video['type'] == 'single') {
                  echo '<iframe style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;" width="1280" height="720" src="https://www.youtube.com/embed/' . $video['id'] . '" frameborder="0" allowfullscreen></iframe>';
               } elseif ($video['type'] == 'list') {
                  echo '<iframe style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;" width="1280" height="720" src="https://www.youtube.com/embed/videoseries?list=' . $video['id'] . '" frameborder="0" allowfullscreen></iframe>';
               }
               echo '</div>';
         echo '</div>';
      }
   }
}















function AIgetYouTubeVideoID($url) {
   $queryString = parse_url($url, PHP_URL_QUERY);
   parse_str($queryString, $params);
   if (isset($params['list']) && strlen($params['list']) > 0) {
      return array(
         'id' => $params['list'],
         'type' => 'list'
      );
   } elseif (isset($params['v']) && strlen($params['v']) > 0) {
      return array(
         'id' => $params['v'],
         'type' => 'single'
      );
   } else {
      $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
      if (preg_match($pattern, $url, $match)) {
         return array(
            'id' => $match[1],
            'type' => 'single'
         );
      } else {
         return "";
      }
   }
}
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
            'min' => 0,
            'max' => '',
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
   $audio_arr = get_field('field_ai_audio_block_repeater');
   if ($audio_arr) {
      echo '<div class="ai_audio_block ai_block">';
         echo '<div class="ai_block_container">';
         $playlist_ids = array();
         foreach( $audio_arr as $audio_file ) {
            $id = $audio_file['ai_audio_track']['id'];
            if($id){
               $playlist_ids[] = $id;
            }
         }
         echo '[playlist style="dark" ids="' . implode(",", $playlist_ids) . '"]';
         echo '</div>';
      echo '</div>';
   }
}

// CUSTOM AUDIO PLAYLIST PLAYER
function wpse_141767_wp_playlist_scripts()
{
    remove_action( 'wp_footer', 'wp_underscore_playlist_templates', 0 );
    add_action( 'wp_footer', 'wpse_141767_wp_underscore_playlist_templates', 0 );
}
add_action( 'wp_playlist_scripts', 'wpse_141767_wp_playlist_scripts' );

function wpse_141767_wp_underscore_playlist_templates() {
?>
<script type="text/html" id="tmpl-wp-playlist-current-item">
    <# if ( data.image ) { #>
    <img src="{{ data.thumb.src }}"/>
    <# } #>
    <div class="wp-playlist-caption">
		<span class="wp-playlist-item-meta wp-playlist-item-title">{{ data.title }}</span>
		<# if ( data.meta.album ) { #><span class="wp-playlist-item-meta wp-playlist-item-album">{{ data.meta.album }}</span><# } #>
		<# if ( data.meta.artist ) { #><span class="wp-playlist-item-meta wp-playlist-item-artist">{{ data.meta.artist }}</span><# } #>
		<div class="audio_playlist_controls">
			<div class="ap_btn_prev"></div>
			<div class="ap_btn_play"></div>
			<div class="ap_btn_next"></div>
			</div>		
       </div>
	<script>
	jQuery(document).ready(function($) {
			setInterval(function() {
			$('.wp-audio-playlist').each(function() {
				var player_state = $(this).find('.mejs-playpause-button').hasClass('mejs-pause');
				if (player_state) {
					$(this).find('.ap_btn_play').addClass('played');
				} else {
					$(this).find('.ap_btn_play').removeClass('played');
				}
			});
		}, 1000);
	});
	</script>
</script>
<script type="text/html" id="tmpl-wp-playlist-item">
	<div class="wp-playlist-item">
		<a class="wp-playlist-caption" href="{{ data.src }}">
			{{ data.index ? ( data.index + '. ' ) : '' }}
			<# if ( data.caption ) { #>
				{{ data.caption }}
			<# } else { #>
				<span class="wp-playlist-item-title"><?php
					/* translators: playlist item title */
					printf( _x( '%s', 'playlist item title' ), '{{{ data.title }}}' );
				?></span>
				<# if ( data.artists && data.meta.artist ) { #>
				<span class="wp-playlist-item-artist"> &mdash; {{ data.meta.artist }}</span>
				<# } #>
			<# } #>
		</a>
		<# if ( data.meta.length_formatted ) { #>
		<div class="wp-playlist-item-length">{{ data.meta.length_formatted }}</div>
		<# } #>
	</div>
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var player_state;
		$(document).on('click', '.ap_btn_play', function() {
			var el = $(this);
			el.closest('.wp-audio-playlist').find('.mejs-playpause-button').click();
			setTimeout(function() {
				player_state = el.closest('.wp-audio-playlist').find('.mejs-playpause-button').hasClass('mejs-pause');
				if (player_state) {
					el.closest('.wp-audio-playlist').find('.ap_btn_play').addClass('played');
				} else {
					el.closest('.wp-audio-playlist').find('.ap_btn_play').removeClass('played');
				}
			},200);
		});

		$(document).on('click', '.ap_btn_next', function() {
			$(this).closest('.wp-audio-playlist').find('.wp-playlist-next').click();
		});	
		$(document).on('click', '.ap_btn_prev', function() {
			$(this).closest('.wp-audio-playlist').find('.wp-playlist-prev').click();
		});	

	});
</script>
<?php }
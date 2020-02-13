<?php

// plugin settings link in plugins admin page
add_filter('plugin_action_links_ai-blocks/ai-blocks.php', function ( $links ) {
	$settings_link = '<a href="' . admin_url( 'options-general.php?page=ai-blocks-options' ) . '">' . __('Settings') . '</a>';

	array_unshift( $links, $settings_link );
	return $links;
});




function ai_blocks_add_plugin_menu() {

	add_options_page( 
		'AI Blocks Settings', 
		'AI Blocks', 
		'manage_options', 
		'ai-blocks-options', 
		'ai_blocks_options'
	); 

}
add_action('admin_menu', 'ai_blocks_add_plugin_menu');

function ai_blocks_options() {
		?>
		<div class="wrap">
			<h2><?php echo __('AI Blocks Settings', 'ai-blocks'); ?></h2>
			<?php settings_errors('ai-blocks-options'); ?> 


			<form method="post" action="options.php"> 
			<?php
				settings_fields( 'ai-blocks-main-settings-group' );
				do_settings_sections( 'ai-blocks-options' );
				submit_button(); 
			?> 
			</form> 

		</div>
		<?php
}


// MAIN PLUGIN SETTINGS
function ai_blocks_initialize_main_options() {  
	register_setting(  
		'ai-blocks-main-settings-group',  
		'ai_blocks_settings'  
	);
	add_settings_section(  
		'ai_blocks_main_section', // ID used to identify this section and with which to register options  
		'AI Blocks Settings', // Title to be displayed on the administration page  
		'ai_blocks_main_section_callback', // Callback used to render the description of the section  
		'ai-blocks-options' // Page on which to add this section of options  
   );
   
   add_settings_field (   
		'enabled_ai_blocks',	// ID used to identify the field throughout the theme  
		'Enable AI Blocks',	// The label to the left of the option interface element  
		'ai_blocks_enabled_ai_blocks_callback',	// The name of the function responsible for rendering the option interface  
		'ai-blocks-options', // The page on which this option will be displayed  
		'ai_blocks_main_section' // The name of the section to which this field belongs  
	);

	add_settings_field (   
		'editor_allowed_blocks',	// ID used to identify the field throughout the theme  
		'Allowed Editor Blocks',	// The label to the left of the option interface element  
		'ai_blocks_editor_allowed_blocks_callback',	// The name of the function responsible for rendering the option interface  
		'ai-blocks-options', // The page on which this option will be displayed  
		'ai_blocks_main_section' // The name of the section to which this field belongs  
	);

	add_settings_field (   
		'ai_youtube_block',	// ID used to identify the field throughout the theme  
		'YouTube Block Settings',	// The label to the left of the option interface element  
		'ai_blocks_youtube_block_callback',	// The name of the function responsible for rendering the option interface  
		'ai-blocks-options', // The page on which this option will be displayed  
		'ai_blocks_main_section' // The name of the section to which this field belongs  
	);


	// DEFAULTS
	if ( get_option( 'ai_blocks_settings' ) === false ) {
		$defaults = array(
			'enabled_ai_blocks' => array(
				'text-block'   => '1',
				'quote-block'   => '1',
				'video-block'   => '1',
				'image-block'   => '1',
				'gallery-block'   => '1',
				'audio-block'   => '1',
         ),
         'editor_allowed_blocks' => array(
            'blocks' => implode(
               PHP_EOL,
               array(
                  'core/paragraph',
                  'core/image',
                  'core/heading',
                  'core/audio',
                  'core/quote',
                  'core/list',        
               )
            )
         ),

		);

		update_option('ai_blocks_settings', $defaults);
	}


}
add_action('admin_init', 'ai_blocks_initialize_main_options');

function ai_blocks_main_section_callback() {  
	//echo '<p>Here you can enable or disable widgets</p>';  
}

function ai_blocks_enabled_ai_blocks_callback($args) {  

   // $options = get_option('ai_blocks_settings');
   // $allowed_enable = isset( $options['editor_allowed_blocks']['enable'] ) ? $options['editor_allowed_blocks']['enable'] : '';
	?>
		<label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[enabled_ai_blocks][text-block]" value="1"<?php checked(get_option('ai_blocks_settings')['enabled_ai_blocks']['text-block'], '1'); ?> />
			<?php echo __( 'Text Block', 'ai-blocks' ); ?>
		</label>
		<br>
		<label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[enabled_ai_blocks][quote-block]" value="1"<?php checked(get_option('ai_blocks_settings')['enabled_ai_blocks']['quote-block'], '1'); ?> />
			<?php echo __( 'Quote Block', 'ai-blocks' ); ?>
		</label>
		<br>
      <label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[enabled_ai_blocks][video-block]" value="1"<?php checked(get_option('ai_blocks_settings')['enabled_ai_blocks']['video-block'], '1'); ?> />
			<?php echo __( 'Video Block', 'ai-blocks' ); ?>
		</label>
		<br>
      <label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[enabled_ai_blocks][image-block]" value="1"<?php checked(get_option('ai_blocks_settings')['enabled_ai_blocks']['image-block'], '1'); ?> />
			<?php echo __( 'Image Block', 'ai-blocks' ); ?>
		</label>
		<br>
      <label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[enabled_ai_blocks][gallery-block]" value="1"<?php checked(get_option('ai_blocks_settings')['enabled_ai_blocks']['gallery-block'], '1'); ?> />
			<?php echo __( 'Gallery Block', 'ai-blocks' ); ?>
		</label>
		<br>
      <label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[enabled_ai_blocks][audio-block]" value="1"<?php checked(get_option('ai_blocks_settings')['enabled_ai_blocks']['audio-block'], '1'); ?> />
			<?php echo __( 'Audio Block', 'ai-blocks' ); ?>
		</label>
		<br>

	
	<?php

}

function ai_blocks_editor_allowed_blocks_callback($args) {  

   $options = get_option('ai_blocks_settings');
   $allowed_enable = isset( $options['editor_allowed_blocks']['enable'] ) ? $options['editor_allowed_blocks']['enable'] : '';
	?>
		<label style="display: block">
			<input type="checkbox" name="ai_blocks_settings[editor_allowed_blocks][enable]" value="1"<?php checked($allowed_enable, '1'); ?> />
			<?php echo __( 'Allowed Blocks', 'ai-blocks' ); ?>
		</label>
		<br>
		<label style="display: block">
			<textarea spellcheck="false" name="ai_blocks_settings[editor_allowed_blocks][blocks]" cols="44" rows="10"><?php echo $options['editor_allowed_blocks']['blocks']; ?></textarea>
		</label>
	
	<?php
}

function ai_blocks_youtube_block_callback($args) {  

   $options = get_option('ai_blocks_settings');
   // $allowed_enable = isset( $options['ai_youtube_block']['api_key'] ) ? $options['editor_allowed_blocks']['enable'] : '';
	?>
		<label style="display: block">
			<div><?php echo __( 'YouTube API key', 'ai-blocks' ); ?></div><br>
			<input type="text" size="44" name="ai_blocks_settings[ai_youtube_block][api_key]" value="<?php echo $options['ai_youtube_block']['api_key']; ?>">
		</label>
		<br>
	<?php 
		$api_url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&key=' . $options['ai_youtube_block']['api_key'];
		$json_result = file_get_contents($api_url);
		if ($json_result === false) {
			echo 'No valid API key';
		} else {
			echo 'Key is valid';
		}
}
// AI BLOCKS
$options = get_option('ai_blocks_settings')['enabled_ai_blocks'];
if (isset($options['text-block'])) {
   include( AI_BLOCKS_PLUGIN_DIR . 'blocks/text-block.php');
}
if (isset($options['quote-block'])) {
   include( AI_BLOCKS_PLUGIN_DIR . 'blocks/quote-block.php');
}
if (isset($options['video-block'])) {
   include( AI_BLOCKS_PLUGIN_DIR . 'blocks/video-block.php');
}
if (isset($options['image-block'])) {
   include( AI_BLOCKS_PLUGIN_DIR . 'blocks/image-block.php');
}
if (isset($options['gallery-block'])) {
   include( AI_BLOCKS_PLUGIN_DIR . 'blocks/gallery-block.php');
}
if (isset($options['audio-block'])) {
   include( AI_BLOCKS_PLUGIN_DIR . 'blocks/audio-block.php');
}




// RESTRICT EDITOR BLOCKS
add_filter( 'allowed_block_types', function( $allowed_blocks ) {

   $options = get_option('ai_blocks_settings');
   if (isset($options['editor_allowed_blocks']['enable'])) {

	  $allowed_blocks = explode("\n", str_replace("\r", "", $options['editor_allowed_blocks']['blocks']));
      if (isset($options['enabled_ai_blocks']['text-block'])) {
         array_push($allowed_blocks,'acf/ai-text-block');
      }
      if (isset($options['enabled_ai_blocks']['quote-block'])) {
         array_push($allowed_blocks,'acf/ai-quote-block');
      }
      if (isset($options['enabled_ai_blocks']['video-block'])) {
         array_push($allowed_blocks,'acf/ai-yt-video-block');
      }
      if (isset($options['enabled_ai_blocks']['image-block'])) {
         array_push($allowed_blocks,'acf/ai-image-block');
      }
      if (isset($options['enabled_ai_blocks']['gallery-block'])) {
         array_push($allowed_blocks,'acf/ai-image-gallery-block');
      }
      if (isset($options['enabled_ai_blocks']['audio-block'])) {
         array_push($allowed_blocks,'acf/ai-audio-block');
      }
	}

	return $allowed_blocks;
});


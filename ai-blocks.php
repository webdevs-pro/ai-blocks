<?php
/**
 * Plugin Name: AI Blocks
 * Description: AI custom blocks.
 * Plugin URI:  http://web-devs.pro/
 * Version:     1.1
 * Author:      web-devs.pro
 * Text Domain: ai-blocks
 */


// BLOCKS
include( plugin_dir_path( __FILE__ ) . 'blocks/video-block.php');
include( plugin_dir_path( __FILE__ ) . 'blocks/gallery-block.php');
include( plugin_dir_path( __FILE__ ) . 'blocks/quote-block.php');
include( plugin_dir_path( __FILE__ ) . 'blocks/text-block.php');
include( plugin_dir_path( __FILE__ ) . 'blocks/image-block.php');
include( plugin_dir_path( __FILE__ ) . 'blocks/audio-block.php');


// GUTENBERG EDITOR WIDTH
add_action('enqueue_block_editor_assets', function() {
   echo '<style type="text/css">
   /* EDITOR MAX WIDTH */
   .wp-block {
      max-width: 840px !important;
   }


   /* CATEGORY LIST PRELOADER */
   .editor-post-taxonomies__hierarchical-terms-list:empty {
      height: 18px;
      width: 18px;
      position: relative;
      overflow: hidden;
      border-radius: 50%;
      background: #A1A9B1;
   }
   .editor-post-taxonomies__hierarchical-terms-list:empty:after {
      content: "";
      position: absolute;
		background-color: #fff;
		top: 3px;
		left: 3px;
		width: 4px;
		height: 4px;
		border-radius: 50%;
      transform-origin: 6px 6px;
      animation: ai-360-rotate 1s infinite linear;
   }
   @keyframes ai-360-rotate {
      from {
         transform: rotate(0deg);
      }
      to {
         transform: rotate(360deg);
      }
   }


   .components-panel__body-toggle.components-button:focus {
      outline: none !important;
   }
   </style>';
});



// SCRIPT TO AUTOHEIGHT FOR ACF WYSIWYG AND TEXTAREA EDITOR
class ACFAutosize {
	public function __construct() {
		// echo javascript
		add_action("acf/input/admin_footer", array($this, "echoJs"));
		add_action("acf/input/admin_head", array($this, "echoCss"));
	}
	public function echoJs() {
		echo "<script type=\"text/javascript\">";
		echo "ACFAutosize = {'wysiwyg':{'minHeight': ".apply_filters('acf-autosize/wysiwyg/min-height', 200)."}};";
		echo "</script>";
		echo "<script type=\"text/javascript\">";
		echo "\"use strict\";(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c=\"function\"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error(\"Cannot find module '\"+i+\"'\");throw a.code=\"MODULE_NOT_FOUND\",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u=\"function\"==typeof require&&require,i=0;i<t.length;i++){o(t[i])}return o}return r})()({1:[function(require,module,exports){(function(global,factory){if(typeof define===\"function\"&&define.amd){define([\"module\",\"exports\"],factory)}else if(typeof exports!==\"undefined\"){factory(module,exports)}else{var mod={exports:{}};factory(mod,mod.exports);global.autosize=mod.exports}})(this,function(module,exports){'use strict';var map=typeof Map===\"function\"?new Map:function(){var keys=[];var values=[];return{has:function has(key){return keys.indexOf(key)>-1},get:function get(key){return values[keys.indexOf(key)]},set:function set(key,value){if(keys.indexOf(key)===-1){keys.push(key);values.push(value)}},\"delete\":function _delete(key){var index=keys.indexOf(key);if(index>-1){keys.splice(index,1);values.splice(index,1)}}}}();var createEvent=function createEvent(name){return new Event(name,{bubbles:true})};try{new Event(\"test\")}catch(e){createEvent=function createEvent(name){var evt=document.createEvent(\"Event\");evt.initEvent(name,true,false);return evt}}function assign(ta){if(!ta||!ta.nodeName||ta.nodeName!==\"TEXTAREA\"||map.has(ta))return;var heightOffset=null;var clientWidth=null;var cachedHeight=null;function init(){var style=window.getComputedStyle(ta,null);if(style.resize===\"vertical\"){ta.style.resize=\"none\"}else if(style.resize===\"both\"){ta.style.resize=\"horizontal\"}if(style.boxSizing===\"content-box\"){heightOffset=-(parseFloat(style.paddingTop)+parseFloat(style.paddingBottom))}else{heightOffset=parseFloat(style.borderTopWidth)+parseFloat(style.borderBottomWidth)}if(isNaN(heightOffset)){heightOffset=0}update()}function changeOverflow(value){{var width=ta.style.width;ta.style.width=\"0px\";ta.offsetWidth;ta.style.width=width}ta.style.overflowY=value}function getParentOverflows(el){var arr=[];while(el&&el.parentNode&&el.parentNode instanceof Element){if(el.parentNode.scrollTop){arr.push({node:el.parentNode,scrollTop:el.parentNode.scrollTop})}el=el.parentNode}return arr}function resize(){if(ta.scrollHeight===0){return}var overflows=getParentOverflows(ta);var docTop=document.documentElement&&document.documentElement.scrollTop;ta.style.height=\"\";ta.style.height=ta.scrollHeight+heightOffset+\"px\";clientWidth=ta.clientWidth;overflows.forEach(function(el){el.node.scrollTop=el.scrollTop});if(docTop){document.documentElement.scrollTop=docTop}}function update(){resize();var styleHeight=Math.round(parseFloat(ta.style.height));var computed=window.getComputedStyle(ta,null);var actualHeight=computed.boxSizing===\"content-box\"?Math.round(parseFloat(computed.height)):ta.offsetHeight;if(actualHeight<styleHeight){if(computed.overflowY===\"hidden\"){changeOverflow(\"scroll\");resize();actualHeight=computed.boxSizing===\"content-box\"?Math.round(parseFloat(window.getComputedStyle(ta,null).height)):ta.offsetHeight}}else{if(computed.overflowY!==\"hidden\"){changeOverflow(\"hidden\");resize();actualHeight=computed.boxSizing===\"content-box\"?Math.round(parseFloat(window.getComputedStyle(ta,null).height)):ta.offsetHeight}}if(cachedHeight!==actualHeight){cachedHeight=actualHeight;var evt=createEvent(\"autosize:resized\");try{ta.dispatchEvent(evt)}catch(err){}}}var pageResize=function pageResize(){if(ta.clientWidth!==clientWidth){update()}};var destroy=function(style){window.removeEventListener(\"resize\",pageResize,false);ta.removeEventListener(\"input\",update,false);ta.removeEventListener(\"keyup\",update,false);ta.removeEventListener(\"autosize:destroy\",destroy,false);ta.removeEventListener(\"autosize:update\",update,false);Object.keys(style).forEach(function(key){ta.style[key]=style[key]});map[\"delete\"](ta)}.bind(ta,{height:ta.style.height,resize:ta.style.resize,overflowY:ta.style.overflowY,overflowX:ta.style.overflowX,wordWrap:ta.style.wordWrap});ta.addEventListener(\"autosize:destroy\",destroy,false);if(\"onpropertychange\"in ta&&\"oninput\"in ta){ta.addEventListener(\"keyup\",update,false)}window.addEventListener(\"resize\",pageResize,false);ta.addEventListener(\"input\",update,false);ta.addEventListener(\"autosize:update\",update,false);ta.style.overflowX=\"hidden\";ta.style.wordWrap=\"break-word\";map.set(ta,{destroy:destroy,update:update});init()}function destroy(ta){var methods=map.get(ta);if(methods){methods.destroy()}}function update(ta){var methods=map.get(ta);if(methods){methods.update()}}var autosize=null;if(typeof window===\"undefined\"||typeof window.getComputedStyle!==\"function\"){autosize=function autosize(el){return el};autosize.destroy=function(el){return el};autosize.update=function(el){return el}}else{autosize=function autosize(el,options){if(el){Array.prototype.forEach.call(el.length?el:[el],function(x){return assign(x,options)})}return el};autosize.destroy=function(el){if(el){Array.prototype.forEach.call(el.length?el:[el],destroy)}return el};autosize.update=function(el){if(el){Array.prototype.forEach.call(el.length?el:[el],update)}return el}}exports[\"default\"]=autosize;module.exports=exports[\"default\"]})},{}],2:[function(require,module,exports){if(typeof acf!==\"undefined\"){require(\"./textarea\");require(\"./wysiwyg\")}},{\"./textarea\":3,\"./wysiwyg\":4}],3:[function(require,module,exports){var autosize=require(\"autosize\");(function(\$){var textareas=\$(\".acf-field.autosize textarea\");autosize(textareas);\$(document).ready(function(){autosize.update(textareas)});\$(window).on(\"load\",function(){autosize.update(textareas)});acf.add_action(\"append\",function(\$el){var textarea=\$el.find(\".acf-field.autosize textarea\");autosize(textarea)})})(window.jQuery)},{\"autosize\":1}],4:[function(require,module,exports){(function(\$){function editorAutoHeight(editor){var minHeight=arguments.length>1&&arguments[1]!==undefined?arguments[1]:200;var height=\$(editor.iframeElement).contents().find(\"html\").height()||minHeight;height=height<minHeight?minHeight:height;\$(editor.iframeElement).css({height:height,minHeight:minHeight})}function addSlugAttr(\$field){var name=\$field.attr(\"data-name\");var body=\$(\"iframe\",\$field).contents().find(\"body\");body.attr(\"data-wysiwyg-slug\",name)}window.acf.add_action(\"wysiwyg_tinymce_init\",function(editor,id,options,\$field){var eventHandler=function eventHandler(){editorAutoHeight(editor,ACFAutosize.wysiwyg.minHeight)};editor.on(\"init\",function(){addSlugAttr(\$field)});var doAutosize=\$field.hasClass(\"autosize\");if(!doAutosize){return}editor.on(\"init\",eventHandler);editor.on(\"change\",eventHandler);\$(window).resize(eventHandler)})})(window.jQuery)},{}]},{},[2]);";
		echo "</script>";
	}
	public function echoCss() {
		echo "<style>";
		echo ".autosize .mce-top-part{position:-webkit-sticky;position:sticky;top:30px;}.block-editor .autosize .mce-top-part{top:0}";
		echo "</style>";
	}
}
new ACFAutosize();


// STRIP TAGS, CLASSES AND IDS ON PASTE TO WYSIWYG EDITOR
add_filter('tiny_mce_before_init', function ($in) {
$in['paste_preprocess'] = "function(plugin, args){
   // Strip all HTML tags except those we have whitelisted
   var whitelist = 'p,span,b,strong,i,em,h3,h4,h5,h6,ul,li,ol';
   var stripped = jQuery('<div>' + args.content + '</div>');
   var els = stripped.find('*').not(whitelist);
   for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
   }
   // Strip all class and id attributes
   stripped.find('*').removeAttr('id').removeAttr('class');
   // Return the clean HTML
   args.content = stripped.html();
}";
return $in;
});





// plugin updates
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/webdevs-pro/ai-blocks',
	__FILE__,
	'ai-blocks'
);







/*
// PRODUCTS BLOCK CSS AND JS
function ai_blocks_styles() {
   wp_enqueue_style('ai-blocks-styles', plugin_dir_url( __FILE__ ) . '/ai-blocks-styles.css' );
   wp_enqueue_script( 'ai-blocks-script', plugin_dir_url( __FILE__ ) . '/ai-blocks-script.js' , array(), '1.0.0', true, ['wp-blocks'] );
}
add_action( 'wp_enqueue_scripts', 'ai_blocks_styles' );
add_action( 'admin_enqueue_scripts', 'ai_blocks_styles' );

// PRODUCTS EDITOR 
add_action('admin_head', function() {
   global $current_screen;
   if( 'produkty' != $current_screen->post_type )
       return;

   echo "
      <style>
         @media screen and ( min-width: 768px ) {
            .edit-post-visual-editor .editor-post-title,
            .edit-post-visual-editor .editor-block-list__block {
               max-width: none;
            }
            .edit-post-layout__metaboxes {
               padding-left: 46px !important;
               padding-right: 46px !important;
            }
         }
         .categorychecklist-holder {
            max-height: none !important;
         }
         .edit-post-layout__metaboxes {
            border-top: none !important;
         }
         .block-editor-block-list__layout .block-editor-block-list__block.is-selected > .block-editor-block-list__block-edit:before {
            display: none !important;
            border: none !important;
         }
         .acf-block-body .acf-block-fields {
            border: none !important;
         }
         .postbox.acf-postbox > button,
         .postbox.acf-postbox > h2 {
            display: none !important;
         }
         .acf-fields > .acf-field {
            border: none !important;
            margin-top: 20px;
         }
         .block-editor-block-list__block.is-hovered > .block-editor-block-list__block-edit:before {
            box-shadow: none !important;
            border: none !important;
         }
         .block-editor-block-toolbar {
            display: none !important;
         }

      </style>

      <script>
         function replacePostFeaturedImage() { 
            return function() { 
               return wp.element.createElement( 
                  'div', 
                  {}, 
                  '' 
               ); 
            } 
         } 
         wp.hooks.addFilter( 
            'editor.PostFeaturedImage', 
            'my-plugin/replace-post-featured-image', 
            replacePostFeaturedImage
         );
      </script>
   ";
});


// SET BLOCKS TEMPLATE FOR 'PRODUKTY' POST TYPE
add_action( 'init', function(){
   $post_type_object = get_post_type_object( 'produkty' );
   $post_type_object->template = array(
       array( 'acf/product-table' ),
   );
   $post_type_object->template_lock = 'all';
});


// SET FEATURED IMAGE FROM ACF IMAGE FIELD
add_filter('acf/update_value/name=product_image', function($value, $post_id, $field){
   if($value != ''){
      update_post_meta($post_id, '_thumbnail_id', $value);
   }
   return $value;
}, 10, 3);


*/
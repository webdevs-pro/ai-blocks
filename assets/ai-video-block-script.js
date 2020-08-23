
(function($) {

   var player = [];  
   var isReady = false;
   var tag = document.createElement('script');
       tag.src = "https://www.youtube.com/iframe_api";
   var firstScriptTag = document.getElementsByTagName('script')[0];    


   $('.video_wrap').each(function(){

      var player_id = $(this).attr('id');

      $('#'+player_id).on('click', function(){

         var video_id = $(this).attr('data-video-id');

         enqueueOnYoutubeIframeAPIReady(function () {
            player[player_id] = new YT.Player(player_id, { 
               width: 600,
               height: 400,
               videoId: video_id,
               playerVars: {
                  color: 'white'
               },
               events: {
                  onReady: initialize,
                  onStateChange: stateChange
               }
            });

         });
         
      });

      function initialize(){
         player[player_id].playVideo();
      }

      function stateChange(state) {

         // -1 (unstarted)
         // 0 (ended)
         // 1 (playing)
         // 2 (paused)
         // 3 (buffering)
         // 5 (video cued)
         console.log(state);

         var player_id = state.target.f.id;

         // highlight now playing video thumb in playlist
         if(state.data === -1) {

            $('#'+player_id).parent().parent().find('.video-playlist-item.active').removeClass('active');

            var video_id = $('#'+player_id).attr('data-video-id');
            $('#'+player_id).parent().parent().find(`[data-video-id='${video_id}']`).addClass('active');

         }       

         // autoplay next video
         if(state.data === 0) {
            console.log(player[state.target.f.id].getVideoUrl());
            var total = ($('#'+player_id).parent().parent().find('.video-playlist-item')).length;
            var current = $('#'+player_id).parent().parent().find('.video-playlist-item.active').index() + 1;
            if (current < total) {
               $('#'+player_id).parent().parent().find('.video-playlist-item.active').next().trigger('click');
            }
         }

      }

   });



   $('.video-playlist-item').on('click', function(){

      var player_id = $(this).attr('data-player-id');
      var video_id = $(this).attr('data-video-id');

      $('html, body').animate({
         scrollTop: $('#video-'+player_id).offset().top - ai_yt_block.offset
      },parseInt(ai_yt_block.speed));

      if($(this).hasClass('active')) { return; }

      $('#video-'+player_id).attr('data-video-id', video_id);

      if(typeof player['video-'+player_id] === 'undefined') {
         firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
         $('#video-'+player_id).trigger('click');
      } else {
         player['video-'+player_id].cueVideoById(video_id);
         player['video-'+player_id].playVideo();
      }

   });



   // HELPERS
   (function () {
      var callbacks = [];
      window.enqueueOnYoutubeIframeAPIReady = function (callback) {
         if (isReady) {
            callback();
         } else {
            // CREATE VIDEO IFRAME
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            callbacks.push(callback);
         }
      }
      window.onYouTubeIframeAPIReady = function () {
         isReady = true;
         callbacks.forEach(function (callback) {
            callback();
         })
         callbacks.splice(0);
      }
   })()

})(jQuery)


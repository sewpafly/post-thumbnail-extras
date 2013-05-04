(function ($) {

   // supersede the default MediaFrame.Post view
   var oldMediaFrame = wp.media.view.MediaFrame.Post;
   wp.media.view.MediaFrame.Post = oldMediaFrame.extend({

      initialize: function() {
         oldMediaFrame.prototype.initialize.apply( this, arguments );
		 // the `toolbar:render:main-insert` trigger calls the function `addPTXShortcodeToolbar
		 // with the toolbar as the `view` parameter
         this.on( 'toolbar:render:main-insert', this.addPTXShortcodeToolbar, this );
      },

      addPTXShortcodeToolbar: function( view ) {
         var controller = this;

         this.selectionStatusToolbar( view );

         view.set( 'test', {
            style:    'secondary',
            priority: 70,
            text:     wp.media.view.l10n.PTXInsertShortcode,
            requires: { selection: true },

            click: function() {
               var state = controller.state(),
               selection = state.get('selection');

               controller.close();
               state.trigger( 'ptx_shortcode', selection ).reset();
            }
         });
      }

   });


   /**
    * Follows the same functionality of the editor "insert" trigger
    *
    * See wp-includes/js/media-editor.js
    */
   function createPTXShortcode( selection ) {
      workflow = this;
      state = workflow.state();

      selection = selection || state.get("selection");

      if ( ! selection )
         return;

      // Interesting convention - apply the map function to selection (backbone collection),
      // then when the mapping is complete send the arrays as text to the editor
      $.when.apply( $, selection.map( function( attachment ) {
         var display = state.display( attachment ).toJSON();
         var shortcode = {};
         shortcode.id = attachment.id;
         if (display.size != 'thumbnail')
            shortcode.size = display.size;
         if (display.link != 'none')
            shortcode.link = (display.link != 'custom') ? display.link : display.linkUrl;
         if (display.align != 'none')
            shortcode['class'] = "align" + display.align;
         // Take all the attributes and create a string of key=value pairs to pass to the
         // shortcode
         var attributes = _.map(shortcode, function(v,k){ return k + "='" + v + "'";})
         return "[pt " + attributes.join(" ") + "]";
      } ) ).done( function() {
         //}, this ) ).done( function() {
         wp.media.editor.insert( _.toArray( arguments ).join("\n\n") );
      });

   }

   $(function(){ 
      // Get the content frame - This is _the_ instance of the MediaFrame created above
      // Set a trigger to call our function when our button is pressed
      var ptx_editor_add = _.bind(wp.media.editor.add, wp.media.editor);
      wp.media.editor.add = function(id, options) {
         var frame = ptx_editor_add(id, options);
         frame.on('ptx_shortcode', _.bind(createPTXShortcode, frame));
         return frame;
      };
   });

})(jQuery)

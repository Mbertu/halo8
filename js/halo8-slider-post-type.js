(function ($) {
    "use strict";

    var file_frame, image_data;

    $(function () {
        $('#openMedia').click(function(e) {
            // Stop the anchor's default behavior
            e.preventDefault();

            // Display the media uploader
            renderMediaUploader();
        });

        $( "#halo8SlidesContainer" ).sortable({
            update: function( event, ui ) {
                reorderMedia();
            }
        });

        $( "#halo8SlidesContainer" ).disableSelection();

        $('#halo8SlidesContainer').on('click', '.deleteMedia', deleteMediaCallback);
    });

    function renderMediaUploader() {

        /**
         * If an instance of file_frame already exists, then we can open it
         * rather than creating a new instance.
         */
        if ( undefined !== file_frame ) {
            file_frame.open();
            return;
        }

        /**
         * If we're this far, then an instance does not exist, so we need to
         * create our own.
         *
         * Here, use the wp.media library to define the settings of the Media
         * Uploader. We're opting to use the 'post' frame which is a template
         * defined in WordPress core and are initializing the file frame
         * with the 'insert' state.
         *
         * We're also not allowing the user to select more than one image.
         */
        file_frame = wp.media.frames.file_frame = wp.media({
            frame:    'post',
            state:    'insert',
            multiple: true
        });

        /**
         * Setup an event handler for what to do when an image has been
         * selected.
         *
         * Since we're using the 'view' state when initializing
         * the file_frame, we need to make sure that the handler is attached
         * to the insert event.
         */
        file_frame.on( 'insert', insertCallback);

        // Now display the actual file_frame
        file_frame.open();
    }

    function insertCallback(){
        var json = file_frame.state().get( 'selection' ).toJSON();
        var ids = [];
        if(json && json.length > 0){
            for(var i = 0; i < json.length; i++){
                ids.push(json[i].id);
            }
        }

        var start = $('#halo8SlidesContainer .slider_image_container').length;
        var data = {
                'action': 'get_form_for_images',
                'ids': ids,
                'start': start
            };
        $.post( ajaxurl, data, ajaxCallback);
    }

    function ajaxCallback(responce){
        $('#halo8SlidesContainer .description').remove();
        $('#halo8SlidesContainer').append(responce);
    }

    function deleteMediaCallback(e){
        e.preventDefault();
        var button = $(this);
        var media_number = parseInt(button.attr('number'));
        $('#image_'+media_number).remove();
        reorderMedia();
    }

    function reorderMedia(){
        var totalMedia = $('#halo8SlidesContainer .slider_image_container').length;
        $.each($('#halo8SlidesContainer .slider_image_container'),function(index, value){
            changeIndex($(value), index);
        });
    }

    function changeIndex(element,newIndex){
        element.attr('id','image_'+newIndex);
        element.find('.image_number').html(newIndex+1);
        element.find('.media-id').attr('name', 'halo8_slider_input_images['+newIndex+'][ID]');
        element.find('.image_description').attr('name', 'halo8_slider_input_images['+newIndex+'][description]');
        element.find('.image_link').attr('name', 'halo8_slider_input_images['+newIndex+'][link]');
        element.find('.blank_link').attr('name', 'halo8_slider_input_images['+newIndex+'][blank]');
        element.find('.deleteMedia').attr('number',newIndex);
    }

}(jQuery));
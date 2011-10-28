//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require lib/jquery.ext.js
//= require lib/jquery.form.js
//= require core.js

(function($) {

    function initialize_crop_ui() {
        var x = parseInt($('#x').val()),
        y = parseInt($('#y').val()),
        width = parseInt($('#width').val()),
        height = parseInt($('#height').val());

        console.log('--init crop ui--');
        console.log({
            x: x,
            y: y,
            width: width,
            height: height
        });

        var api = WWO.api = $.Jcrop('#crop img', {
            aspectRatio: 0.75,
            onChange: onChange,
            onSelect: onSelect,
            boxWidth: 300,
            boxHeight: 300
        });

        api.setSelect([x, y, x + width, y + height]);

        api.selection.enableHandles();

        function set_textbox_coordinates(x, y, width, height) {
            $('#x').val(x);
            $('#y').val(y);
            $('#width').val(width);
            $('#height').val(height);
        }

        function onChange(coords) {
            set_textbox_coordinates(coords.x, coords.y, coords.w, coords.h);
            showPreview(coords);
        }

        function onSelect(coords) {
            set_textbox_coordinates(coords.x, coords.y, coords.w, coords.h);
            showPreview(coords);
        }

        function showPreview(coords) {

            if (parseInt(coords.w) > 0) {
                var smallWidth = $('#crop_preview').width();
                var smallHeight = $('#crop_preview').height();
                var largeWidth = $('#crop img').width();
                var largeHeight = $('#crop img').height();
                var rx = smallWidth / coords.w;
                var ry = smallHeight / coords.h;

                $('#crop_preview img').css({
                    width: Math.round(rx * largeWidth) + 'px',
                    height: Math.round(ry * largeHeight) + 'px',
                    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                    marginTop: '-' + Math.round(ry * coords.y) + 'px'
                });
            }

        }
    }

    function destroy_crop_ui() {
        if (WWO.api)
            WWO.api.destroy();
    }

    function reinitialize_crop_ui(pic_html, crop_box) {
        var dfd = $.Deferred();
        $(pic_html).bind('imageload', function() {
            destroy_crop_ui();

            console.log(pic_html);
            console.log(crop_box);

            //update pictures
            $('#crop, #crop_preview').html(pic_html);
            //update crop box inputs
            $('#x').val(crop_box.x);
            $('#y').val(crop_box.y);
            $('#width').val(crop_box.width);
            $('#height').val(crop_box.height);

            initialize_crop_ui();
            $('.my_pic').hideLoadMask();
            dfd.resolve();
        });
        return dfd.promise();
    }

    window.initalize_crop_ui = initalize_crop_ui;
    window.reinitialize_crop_ui = reinitialize_crop_ui;

    $.fn.hideLoadMask = function() {
        $(this).find('.mask, .load_message').remove();
        return this;
    }

    $.fn.showLoadMask = function(message) {
        $(this).hideLoadMask();
        var mask = $('<div class="mask"/>').css({
            position: 'absolute',
            top: '0px',
            left: '0px',
            background: 'black',
            opacity: 0.3,
            width: '100%',
            height: '100%',
            'z-index': 9000
        });
        var loadingMessage = $('<div class="load_message"/>').css({
            position: 'absolute',
            top: '0px',
            left: '0px',
            'z-index': 9100
        }).text(message || 'Loading');

        $(this).css('position', 'relative').append(mask).append(loadingMessage);
        var offsetTop = $(this).innerHeight() / 2 - loadingMessage.outerHeight(true) / 2;
        var offsetLeft = $(this).innerWidth() / 2 - loadingMessage.outerWidth(true) / 2;
        $(this).find('.load_message').css({
            top: offsetTop + 'px',
            left: offsetLeft + 'px'
        });
        return this;
    }

    jQuery(function($) {
        $('.my_pic').showLoadMask();
        $.getScript('/assets/js/lib/jquery.jcrop.js', function() {
            $('#crop_raw_image').bind('imageload', function() {
                reinitialize_crop_ui($('#crop_raw_image').html(), {
                    x: $('#x').val(),
                    y: $('#y').val(),
                    width: $('#width').val(),
                    height: $('#height').val()
                });
            });
        });
    });

    $('#pic_upload_input').entwine({
        onchange: function(e) {
            $('.my_pic').showLoadMask('Uploading');
            this.closest('form').ajaxSubmit({
                url: '/user/upload_pic',
                dataType: 'json',
                success: function(response) {
                    reinitialize_crop_ui(response.raw_pic, response.crop_box);
                }
            });
        }
    });

    $('#pic_use_facebook_input').entwine({
        onclick: function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.my_pic').showLoadMask();
            this.closest('form').ajaxSubmit({
                url: '/user/use_facebook_pic',
                dataType: 'json',
                success: function(response) {
                    reinitialize_crop_ui(response.raw_pic, response.crop_box);
                }
            });
        }
    });

})(jQuery);

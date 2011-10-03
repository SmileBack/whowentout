(function($) {
    if ($.browser.msie == false)
        return;

    //fileinputs in satans browser require a blur to trigger a change event
    $('input[type=file]').live('click', function(e) {
        var self = this;
        var blur = function() {
            $(self).blur();
        }
        setTimeout(blur, 0);
    });


})(jQuery);

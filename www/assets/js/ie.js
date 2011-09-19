(function($) {
    if ($.browser.msie == false)
        return;

    $('input[type=file]').live('click', function(e) {
        var self = this;
        var blur = function() {
            $(self).blur();
        }
        setTimeout(blur, 0);
    });


})(jQuery);

$(function() {

    $('.event_list :radio').live('click', function() {
        $(this).closest('form').submit();
    });

});

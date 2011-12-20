$(function () {

    $('.event_list :radio').live('click', function () {
        $(this).closest('form').submit();
    });

    function refresh_check_state(el) {
        if ($(el).is(':checked')) {
            $(el).closest('li').addClass('selected');
        }
        else {
            $(el).closest('li').removeClass('selected');
        }
    }

    $('.event_invite input[type=checkbox]').click(function () {
        refresh_check_state(this);
    });

    $('.event_invite input[type=checkbox]').each(function() {
        refresh_check_state(this);
    });


});

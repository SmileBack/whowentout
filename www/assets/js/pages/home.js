jQuery(function($) {
    $('#enter_button').bind({
       mouseenter: function() {
           $(this).addClass('hover');
       },
       mouseleave: function() {
           $(this).removeClass('hover');
       },
       mouseup: function() {
           $(this).removeClass('down');
       },
       mousedown: function() {
           $(this).addClass('down');
       }
    });
});

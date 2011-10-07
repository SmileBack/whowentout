//= require lib/jquery.js

jQuery(function($) {
    $('#enter_button').bind({
       mouseenter: function() {
           $(this).addClass('hover');
       },
       mouseleave: function() {
           $(this).removeClass('hover').removeClass('down');
       },
       mouseup: function() {
           $(this).removeClass('down');
       },
       mousedown: function() {
           $(this).addClass('down');
       }
    });
});

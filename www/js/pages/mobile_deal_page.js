//= require jquery.entwine.js

$('.mobile .deal_preview').entwine({
    onmatch: function() {
        this.css('cursor', 'pointer');
    },
    onunmatch: function() {
        this.css('cursor', '');
    },
    onclick: function() {
        $(this).closest('form').submit();
    }
});

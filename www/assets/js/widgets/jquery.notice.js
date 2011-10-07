//= require lib/jquery.js
//= require lib/jquery.entwine.js

$('#notice').entwine({
    showNotice: function(message, target, anchor) {
        this.empty().append(message).anchor(target, anchor).fadeIn(300);
        return this;
    },
    hideNotice: function() {
        this.fadeOut(300);
        return this;
    }
});

$.fn.notice = function(message, position) {
    position = position || 't';
    var anchors = {t: ['bc', 'tc'], b: ['tc', 'bc'], l: ['rc', 'lc'], r: ['lc', 'rc']};
    $('#notice').showNotice(message, $(this), anchors[position] || position);
    return this;
}

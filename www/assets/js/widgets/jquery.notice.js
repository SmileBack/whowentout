//= require lib/jquery.js
//= require lib/jquery.entwine.js

$('.notice').entwine({
    showNotice: function(message, target, anchor) {
        var anchors = {t: ['bc', 'tc'], b: ['tc', 'bc'], l: ['rc', 'lc'], r: ['lc', 'rc']};
        var anchor = anchors[anchor] || anchor;
        
        this.empty().append(message).anchor(target, anchor).fadeIn(300);
        return this;
    },
    hideNotice: function() {
        this.fadeOut(300);
        return this;
    },
    cancelHideNoticeAfter: function() {
        clearTimeout( this.data('hideNoticeAfterTimeoutId') );
    },
    hideNoticeAfter: function(ms) {
        var self = this;
        this.cancelHideNoticeAfter();
        var id = setTimeout(function() {
            self.hideNotice();
        }, ms)
        this.data('hideNoticeAfterTimeoutId', id);
    }
});

$.fn.notice = function(message, position) {
    $('#notice').showNotice(message, $(this), position || 't');
    return this;
}

//= require lib/jquery.js
//= require lib/jquery.entwine.js

$('.notice').entwine({
    showNotice: function(message, target, anchor) {
        this.cancelHideNoticeAfter();
        
        var anchors = {t: ['bc', 'tc'], b: ['tc', 'bc'], l: ['rc', 'lc'], r: ['lc', 'rc'], c: ['cc', 'cc']};
        var anchor = anchors[anchor] || anchor;
        
        this.empty().append(message).anchor(target, anchor).fadeIn(300);
        return this;
    },
    hideNotice: function() {
        this.cancelHideNoticeAfter();
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
        }, ms);
        this.data('hideNoticeAfterTimeoutId', id);
    }
});

$.fn.notice = function(message, position, showFor) {
    $('#notice').showNotice(message, $(this), position || 't');
    
    if (showFor) {
        $('#notice').hideNoticeAfter(showFor);
    }

    return this;
}

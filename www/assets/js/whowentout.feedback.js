//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require widgets/jquery.notice.js

jQuery(function() {
    $('body').append('<div class="feedback_box"></div>');
});

$('.feedback_box').entwine({
    onmatch: function() {
        this.append('<h3>Feedback  <span class="expand">[+]</span></h3>')
            .append('<div class="body"> <h4>Type feedback below:</h4> <textarea></textarea> <button>Send</button> </div>');
    },
    onunmatch: function() {
    },
    val: function() {
        return this.find('textarea').val();
    },
    expand: function() {
        this.find('.body').show();
        this.find('.expand').text('[-]');
    },
    collapse: function() {
        this.find('.body').hide();
        this.find('.expand').text('[+]');
    },
    isExpanded: function() {
        return this.find('.body').is(':visible');
    },
    toggleExpanded: function() {
        if (this.isExpanded()) {
            this.collapse();
        }
        else {
            this.expand();
        }
    },
    send: function() {
        var self = this;
        var textarea = this.find('textarea');
        textarea.notice('Sending...', 'c');
        return $.ajax({
            url: '/feedback/send',
            type: 'post',
            dataType: 'json',
            data: { feedback: this.val() },
            success: function() {
                textarea.notice('Sent!', 'c', 1000);
                setTimeout(function() {
                    textarea.val('');
                    self.collapse();
                }, 2000);
            },
            error: function() {
                textarea.notice('Send Failed :(', 'c', 1000);
            }
        });
    }
});

$('.feedback_box button').entwine({
    onclick: function() {
        this.closest('.feedback_box').send();
    }
});

$('.feedback_box h3').entwine({
    onclick: function() {
        this.closest('.feedback_box').toggleExpanded();
    }
});

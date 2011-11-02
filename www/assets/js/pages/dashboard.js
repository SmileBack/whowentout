//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require widgets/jquery.notice.js
//= require whowentout.application.js
//= require whowentout.queue.js

function jq(fn) {
    fn(jQuery);
}

jq(function($) {

    function checkin_to_party(party_id) {
        return $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/checkin/create',
            data: {party_id: party_id}
        });
    }
    
    $('.party_group input:radio').entwine({
        onchange: function() {
            var partyId = this.val();
            checkin_to_party(partyId);
        }
    });

    $('.party_group input:radio:disabled').entwine({
        onclick: function() {
            alert('disabled');
        }
    });

});

$.when(app.load()).then(function() {

    function InsertThumbnailTask(options) {
        var dfd = $.Deferred();

        var gallery = $(options.gallery);
        var user_id = options.user_id;

        var n = gallery.thumbnailCapacity() - 2;
        var oldPics = gallery.thumbnails().filter(':gt(' + n + ')');
        $.when(user(user_id)).then(function(u) {
            var t = gallery.createThumbnail(u);
            t.bind('imageload', function() {
                t.css('opacity', 0);
                t.css('position', 'fixed');
                gallery.prepend(t);

                var dim = $(this).hiddenDimensions();
                var originalWidth = dim.innerWidth;
                var originalMarginLeft = dim.margin.left;
                var originalMarginRight = dim.margin.right;

                var speed = 500;
                t.css({position: '', 'margin-left': '-' + originalWidth + 'px', 'margin-right': '0px'});

                if (oldPics.length > 0) {
                    oldPics.fadeOut(speed, function() {
                        $(this).remove();
                        t.animate({
                            'margin-left': originalMarginLeft + 'px',
                            'margin-right': originalMarginRight + 'px'
                        }, speed)
                        .animate({opacity: 1}, {
                            duration: speed,
                            complete: function() {
                                dfd.resolve();
                            }
                        });
                    });
                }
                else {
                    t.animate({
                        'margin-left': originalMarginLeft + 'px'
                    }, speed)
                    .animate({opacity: 1}, {
                        duration: speed,
                        complete: function() {
                            dfd.resolve();
                        }
                    });
                }
            });
        });

        return dfd.promise();
    }

    $('.recent_attendees.party').entwine({
        onmatch: function() {
            this._super();
            var self = this;

            this.data('queue', new WhoWentOut.Queue());

            app.channel('private-party_' + this.partyID()).bind('checkin', function(e) {
                self.insertThumbnail(e.user_id);
            });
        },
        onunmatch: function() {
            this._super();
        },
        queue: function() {
            return this.data('queue');
        },
        thumbnailCapacity: function() {
            return parseInt(this.attr('data-thumbnail-capacity'));
        },
        oncheckin: function(e) {
            this.insertThumbnail(e.user.id);
            return this;
        },
        thumbnails: function() {
            return this.find('li');
        },
        insertThumbnail: function(user_id) {
            this.queue().add(InsertThumbnailTask, {
                gallery: this,
                user_id: user_id
            });
            return this;
        },
        createThumbnail: function(user) {
            var tpl = $('<li><a><img/></a></li>');
            tpl.find('img').attr('src', user.thumbUrl());
            tpl.find('a').attr('href', '/party/' + this.partyID());
            return tpl;
        }
    });

    $('.recent_attendees.party li').entwine({
        userID: function() {
            return parseInt(this.attr('data-user-id'));
        }
    });

    $('.profile_pic').entwine({
        onmouseenter: function() {
            this.addClass('hover');
        },
        onmouseleave: function() {
            this.removeClass('hover');
        }
    });

    $('.fake_time_options').entwine({
        onchange: function() {
            var value = this.val();
            this.closest('form').find('input[name="fake_time"]').val(value);
        }
    });

});

$('.smile_help_link').entwine({
    onclick: function(e) {
        this._super();
        e.preventDefault();
        app.showSmileHelp();
    }
});

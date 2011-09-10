$('.recent_attendees').entwine({
    thumbnailCapacity: function() {
        return 5;
    },
    onmatch: function() {
        this._super();
    },
    onunmatch: function() {
        this._super();
    },
    oncheckin: function(e) {
        this.insertThumbnail(e.user.id);
        return this;
    },
    partyID: function() {
        return this.attr('data-party-id');
    },
    thumbnails: function() {
        return this.find('li');
    },
    insertThumbnail: function(user_id) {
        var self = this;
        var n = this.thumbnailCapacity() - 2;
        var oldPics = this.thumbnails().filter(':gt(' + n + ')');
        $.when(user(user_id)).then(function(u) {
            var t = self.createThumbnail(u);
            t.bind('imageload', function() {
                t.css('opacity', 0);
                t.css('position', 'fixed');
                $('.recent_attendees').prepend(t);

                var dim = $(this).hiddenDimensions();
                var originalWidth = dim.innerWidth;
                var originalMarginLeft = dim.margin.left;
                var originalMarginRight = dim.margin.right;

                var speed = 300;
                t.css({position: '', 'margin-left': '-' + originalWidth + 'px', 'margin-right': '0px'});

                if (oldPics.length > 0) {
                    oldPics.fadeOut(speed, function() {
                        $(this).remove();
                        t.animate({
                            'margin-left': originalMarginLeft + 'px',
                            'margin-right': originalMarginRight + 'px'
                        }, speed)
                                .animate({opacity: 1}, speed);
                    });
                }
                else {
                    t.animate({
                        'margin-left': originalMarginLeft + 'px'
                    }, speed)
                            .animate({opacity: 1}, speed);
                }
            });
        });

        return this;
    },
    createThumbnail: function(user) {
        var tpl = $('<li><a><img/></a></li>');
        tpl.find('img').attr('src', user.thumb_url);
        tpl.find('a').attr('href', '/party/' + this.partyID());
        return tpl;
    }
});

$('.recent_attendees li').entwine({
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

$('#parties_attended_view .notices > *').entwine({
    onmatch: function() {
        this.css('cursor', 'pointer');
    },
    onunmatch: function() {
    },
    onclick: function(e) {
        e.preventDefault();
        var link = this.closest('.party_summary').find('a');
        window.location.href = link.attr('href');
    }
});

$('#top_parties').entwine({
    onmatch: function() {
        var el = this;
        every(5 * 60, function() {
            el.update();
        });
    },
    onunmatch: function() {
    },
    update: function() {
        $.ajax({
            context: this,
            url: '/dashboard/top_parties',
            dataType: 'html',
            success: function(response) {
                var newTopParties = $(response);
                var isNew = newTopParties.partyIDs().join(',') != this.partyIDs().join(',');
                if (isNew)
                    this.replaceWith(response);
            }
        });
    },
    partyIDs: function() {
        var ids = [];
        this.find('li').each(function() {
            ids.push(parseInt($(this).attr('data-id')));
        });
        return ids;
    }
});

$('.friends.autocomplete_list .autocomplete_list_item').entwine({
    updateHTML: function() {
        this.empty()
                .append(this.getFacebookImage())
                .append('<span>' + this.object().title + '</span>');

        return this;
    },
    getFacebookImage: function() {
        return $('<img src="https://graph.facebook.com/' + this.object().id + '/picture">');
    }
});

$('.invite_friends :submit').entwine({
    onclick: function(e) {
        var form = this.closest('form');
        if (form.find('input.friends').val() == '') {
            form.notice('Please type the name of a friend.');
            e.preventDefault();
        }
        else if (form.find('.friends').selectedObject() == null) {
            if (form.find('input.friends').matchingItems().length == 1) {
                var item = form.find('input.friends').matchingItems();
                form.find('input.friends').selectItem(item);
            }
            else {
                form.notice('Please select someone from the list.');
            }
            e.preventDefault();
        }
    }
});

$('.invite_friends input.friends').live('objectselected', function(e, object) {
    var form = $(this).closest('form');
    var submitButton = form.find('.submit_button');
    var miniSubmitButton = submitButton.clone().margin({top: -3});

    var message = $('<p class="invite_notice">'
            + '<span>Click </span>'
            + '<em>Invite</em>'
            + '<span> to invite ' + object.title + '.</span>'
            + '</p>');
    message.find('em').empty().append(miniSubmitButton);

    miniSubmitButton.bind('click', function() {
        submitButton.click();
    });

    $(this).closest('form').notice(message, 't', 5);
});

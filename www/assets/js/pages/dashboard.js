//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require widgets/jquery.notice.js
//= require whowentout.application.js
//= require whowentout.queue.js

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

    $('.party_summary .link_to_party').entwine({
        onmatch: function() {
            this._super();
            this.css('cursor', 'pointer');
        },
        onunmatch: function() {
            this._super();
        },
        onclick: function(e) {
            e.preventDefault();
            var link = this.closest('.party_summary').find('a');
            window.location.href = link.attr('href') + '?src=smiles';
        }
    });

    $('.fake_time_options').entwine({
        onchange: function() {
            var value = this.val();
            this.closest('form').find('input[name="fake_time"]').val(value);
        }
    });

});


$('.checkin_form').entwine({
    selectedPlace: function() {
        return {
            id: this.find('option:selected').attr('value'),
            name: this.find('option:selected').text()
        };
    },
    doorsOpenTime: function() {
        return new Date(this.attr('doors_opening_time') * 1000);
    },
    doorsCloseTime: function() {
        return new Date(this.attr('doors_closing_time') * 1000);
    }
});

$('.checkin_form select.empty').entwine({
    onmouseenter: function(e) {
        this.notice('Parties to checkin to will be listed here', 'b');
    },
    onmouseleave: function(e) {
        $('#notice').hideNotice();
    }
});

$('.checkin_form :submit').entwine({
    form: function() {
        return this.closest('form');
    },
    onclick: function(e) {
        e.preventDefault();

        var doorsOpen = (this.closest('form').attr('doors_open') == 1);
        var place = this.form().selectedPlace();
        var doorsOpenTime = this.closest('form').doorsOpenTime().format('h tt');

        if (doorsOpen) {
            var date = yesterday_time().format('mmmm dS');
            WWO.dialog.title('Confirm Checkin')
            .message('<p>You are about to checkin to <em>' + place.name + '</em> for the night of ' + date + '.<p>'
            + '<p>This will let you see others who have checked in as well.</p>')
            .setButtons('yesno')
            .refreshPosition()
            .showDialog('confirm_checkin');
        }
        else {
            WWO.dialog.title("Can't Checkin")
            .message(
            '<p>Doors have not yet opened for checkin.</p>'
            + '<p>You will be able to checkin to ' + place.name + ' at ' + doorsOpenTime + '.</p>'
            )
            .setButtons('ok')
            .refreshPosition()
            .showDialog('cant_checkin');
        }
    }
});

$('.smile_help_link').entwine({
    onclick: function(e) {
        this._super();
        e.preventDefault();
        app.showSmileHelp();
    }
});

$('.confirm_checkin.dialog').live('button_click', function(e, button) {
    if (button.hasClass('y')) {
        $('#dashboard_page .checkin_form').ajaxSubmit({
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log('--checkin--');
                console.log(response);
                app.loadChannels(response.channels);
                var party = response.party;
                var partySummary = $('.party_summary[data-party-date=' + party.date + ']');
                var newPartySummary = $(response.party_summary_view).hide();
                $('.user_command_notice').replaceWith(response.user_command_notice);
                partySummary.fadeOut(400, function() {
                    partySummary.replaceWith(newPartySummary);
                    newPartySummary.fadeIn(400, function() {

                        var checkinForm = $(response.checkin_form);
                        checkinForm.hide();
                        $('.parties_attended').prepend(checkinForm);
                        var height = checkinForm.whenShown(function() {
                            return this.outerHeight(true);
                        });
                        checkinForm.css({
                            'margin-top': '-' + height + 'px',
                            'z-index': 5
                        })
                        .show().delay(1000).animate({'margin-top': '0px'}, function() {
                            $(this).css('z-index', '');
                            Actions.ShowPartyGalleryTip();
                        });
                    });
                });
            }
        });
    }
});


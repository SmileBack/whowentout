$.when(app.load()).then(function() {

    $('.recent_attendees.party').entwine({
        onmatch: function() {
            this._super();
            var self = this;
            app.channel('party_' + this.partyID()).bind('checkin', function(e) {
                self.insertThumbnail(e.user.id);
            });
        },
        onunmatch: function() {
            this._super();
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
            var self = this;
            var n = this.thumbnailCapacity() - 2;
            var oldPics = this.thumbnails().filter(':gt(' + n + ')');
            $.when(user(user_id)).then(function(u) {
                var t = self.createThumbnail(u);
                t.bind('imageload', function() {
                    t.css('opacity', 0);
                    t.css('position', 'fixed');
                    self.prepend(t);

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

    $('.party_summary.checkin').entwine({
        onmatch: function() {
            var notice = this.find('.user_command').html();
            $('.user_command_notice').html(notice);
        },
        onunmatch: function() {
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
                console.log('--checkin--');
                console.log(response);
                app.loadChannels(response.channels);
                var party = response.party;
                var partySummary = $('.party_summary[data-party-date=' + party.date + ']');
                var newPartySummary = $(response.party_summary_view).hide();
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

                            app.showPartyGalleryTip();
                        });
                    });
                });
            }
        });
    }
});


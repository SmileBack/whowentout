//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require widgets/jquery.notice.js
//= require whowentout.application.js
//= require whowentout.queue.js

$.when(app.load()).then(function() {

    app.channel('current_user')
    .bind('smile_received', function(e) {
        var partyID = e.party.id;
        $('.party_notices').attrEq('for', partyID).replaceWith(e.party_notices_view);
    })
    .bind('smile_match', function(e) {
        var partyID = e.party.id;
        $('.party_notices').attrEq('for', partyID).replaceWith(e.party_notices_view);
    })
    .bind('time_faked', function(e) {
        window.location.reload(true);
    });

    function InsertAttendeeTask(options) {
        var gallery = $(options.gallery);
        var party_id = gallery.partyID();
        var user_id = options.user_id;

        var dfd = $.Deferred();

        var attendeeResult = $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/user/party_attendee_view',
            data: { user_id: user_id, party_id: party_id }
        });
        
        attendeeResult.done(function(result) {
            var insertPosition = result.insert_positions[ gallery.sorting() ];
            var el = $('<li>' + result.party_attendee_view + '</li>');
            el.addClass('new').css('display', 'inline-block').css('opacity', 0);

            el.bind('imageload', function() {
                if (insertPosition == 'first') {
                    gallery.find('> ul').prepend(el);
                }
                else {
                    gallery.attendee(insertPosition).closest('li').after(el);
                }
                el.animate({opacity: 1}, {
                    complete: function() {
                        dfd.resolve();
                    }
                });
            });
        });

        return dfd.promise();
    }

    $('.gallery').entwine({
        onmatch: function() {
            this._super();
            var self = this;
            
            this.data('queue', new WhoWentOut.Queue());
            
            app.channel('private-party_' + this.partyID()).bind('checkin', function(e) {
                var user_id = e.user_id;
                var party_id = e.party_id;
                console.log('--checkin--');
                console.log(user_id + ' checked into ' + party_id);
                self.insertAttendee(e.user_id, e.party_id);
            });
        },
        queue: function() {
            return this.data('queue');
        },
        onunmatch: function() {
            this._super();
        },
        sorting: function() {
            return this.attr('data-sort');
        },
        smilesLeft: function() {
            return parseInt(this.attr('data-smiles-left'));
        },
        oncheckin: function(e) { //server generated event
            this.insertAttendee(e.party_attendee_view, e.insert_positions);
        },
        insertAttendee: function(user_id) {
            this.queue().add(InsertAttendeeTask, {
                gallery: this,
                party_id: this.partyID(),
                user_id: user_id
            });
        },
        attendee: function(user_id) {
            return this.find('#party_attendee_' + user_id);
        },
        partyID: function() {
            return parseInt(this.attr('data-party-id'));
        },
        chatIsOpen: function() {
            return this.attr('party-chat-is-open') == 'y';
        },
        chatIsClosed: function() {
            return !this.chatIsOpen();
        },
        chatCloseTime: function() {
            return new Date(parseInt(this.attr('party-chat-close-time')) * 1000);
        },
        count: function() {
            return parseInt(this.attr('data-count'));
        }
    });

    $('.smile_form :submit').live('click', function(e) {
        e.preventDefault();
        var action = $(this).attr('value');
        var form = $(this).closest('form');
        var canSmile = $(this).hasClass('can');
        if (canSmile) {
            var senderGender = app.currentUser().otherGender();
            var message = senderGender == 'M'
            ? '<p>You are about to ' + action + '.</p>'
            + '<p>He will know that someone has smiled at him, but he will <strong>not</strong> know it was you unless he smiles at you as well.</p>'

            : '<p>You are about to ' + action + '.</p>'
            + '<p>She will know that someone has smiled at her, but she will <strong>not</strong> know it was you unless she smiles at you as well.</p>';

            WWO.dialog.title('Confirm Smile')
            .message(message)
            .setButtons('yesno')
            .refreshPosition()
            .showDialog('confirm_smile', form);
        }
        else {
            action = action.substring(0, 1).toLowerCase() + action.substring(1);
            WWO.dialog.title("Can't Smile")
            .message("You can't " + action + " because you have already used up your smiles.")
            .setButtons('ok')
            .refreshPosition()
            .showDialog('cant_smile');
        }

    });

    $('.confirm_smile.dialog').live('button_click', function(e, button, form) {
        if (button.hasClass('y')) {
            form.submit();
        }
    });

    $('.user.can_chat img, .user.can_chat .full_name, .user.can_chat .click_to_chat').entwine({
        onmatch: function() {
            this.addClass('clickable');
        },
        onunmatch: function() {
            this.removeClass('clickable');
        },
        onmouseenter: function(e) {
            this.closest('.user').find('.click_to_chat').show();
        },
        onmouseleave: function(e) {
            this.closest('.user').find('.click_to_chat').hide();
        },
        onclick: function(e) {
            var userID = this.closest('.user').userID();
            $('#chatbar').openChat(userID);
        }
    });

    $('.gallery .party_attendee').entwine({
        onmatch: function() {
            this._super()
            var smileButtonClass = this.closest('.gallery').smilesLeft() > 0 ? 'can' : 'cant';
            this.find('.smile_form .submit_button').addClass(smileButtonClass);
        },
        onunmatch: function() {
            this._super()
        }
    });

    $('.gallery .user.online').entwine({
        onmatch: function() {
            this._super();
            if (this.closest('.gallery').chatIsOpen())
                this.addClass('can_chat');
        },
        onunmatch: function() {
            this._super();
            this.removeClass('can_chat');
        }
    });

    $('.help').entwine({
        onmouseenter: function() {
            this.notice(this.helpMessage(), 'r');
        },
        onmouseleave: function() {
            $('#notice').hideNotice();
        },
        helpMessage: function() {
            return '<p>Here is a placeholder help message.</p>';
        }
    });

    $('.smile_help.help').entwine({
        helpMessage: function() {
            return '<p style="width: 400px;">You have 3 smiles to give at each party. '
            + ' The people you smile at will know that someone has smiled at them,'
            + ' but they will <strong>not</strong> know it was you unless they smile at you as well.</p>';
        }
    });

    $('.smiles_received_help.help').entwine({
        helpMessage: function() {
            var otherGender = app.currentUser().get('other_gender');
            var fullGenders = {M: 'guys', F: 'girls'};

            return '<p style="width: 400px;">You will see the number of '
            + fullGenders[otherGender]
            + ' who have smiled at you. However, you will not be informed of their identity unless you have smiled at them as well</p>';
        }
    });

    $('.mutual_smiles_help.help').entwine({
        helpMessage: function() {
            var otherGender = app.currentUser().get('other_gender');
            var fullGenders = {M: 'guy', F: 'girl'};

            return '<p style="width: 400px;">If you and a '
            + fullGenders[otherGender]
            + ' happen to smile at each other, you will be informed of their identity here</p>';
        }
    });

    $('.who_can_chat.help').entwine({
        helpMessage: function() {
            return '<p>Select who can send you chat messages!</p>'
            + '<p>Users who are online will have a green circle to the right of their name.</p>';
        }
    });

    $('.chat_has_closed.help').entwine({
        helpMessage: function() {
            return '<p>chat has closed help</p>';
        }
    });
});

(function($) {

    $('.show_mutual_friends').entwine({
        onclick: function(e) {
            e.preventDefault();
            var path = $(this).attr('href');
            $('#wwo').showMutualFriendsDialog(path);
        }
    });

})(jQuery);

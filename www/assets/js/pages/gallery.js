$('#current_user')
.live('user_came_online', function(e) {
    console.log('user came online ' + e.user.id);
    WhoWentOut.User.get(e.user.id).isOnline(true);
})
.live('user_went_offline', function(e) {
    console.log('user went offline ' + e.user.id);
    WhoWentOut.User.get(e.user.id).isOnline(false);
})
.live('smile_received', function(e) {
    var partyID = e.party.id;
    $('.party_notices').attrEq('for', partyID).replaceWith(e.party_notices_view);
})
.live('smile_match', function(e) {
    var partyID = e.party.id;
    $('.party_notices').attrEq('for', partyID).replaceWith(e.party_notices_view);
})
.live('time_faked', function(e) {
    window.location.reload(true);
});

WhoWentOut.User.all().bind('itemchange', function(e) {
   if (e.key == 'is_online') {
       if (e.value == true) {
           $('.user_' + e.item.id()).addClass('online');
       }
       else {
           $('.user_' + e.item.id()).removeClass('online');
       }
   }
});

$('.gallery').entwine({
    onmatch: function() {
        this._super();
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
    insertAttendee: function(attendeeHTML, positions) {
        var insertPosition = positions[ this.sorting() ];
        var el = $('<li>' + attendeeHTML + '</li>');
        var gallery = $(this);
        el.addClass('new').css('display', 'inline-block').css('opacity', 0);

        el.bind('imageload', function() {
            if (insertPosition == 'first') {
                gallery.find('> ul').prepend(el);
            }
            else {
                gallery.attendee(insertPosition).closest('li').after(el);
            }
            el.animate({opacity: 1});
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
        return new Date( parseInt(this.attr('party-chat-close-time')) * 1000 );
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
        var senderGender = app.currentUser().other_gender;
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

$('.show_mutual_friends').entwine({
    onclick: function(e) {
        e.preventDefault();
        var path = $(this).attr('href');
        $('#wwo').showMutualFriendsDialog(path);
    }
});

$('.gallery .open_chat').entwine({
    onmouseenter: function(e) {
        this.notice('Click to chat', 't');
    },
    onmouseleave: function(e) {
        $('#notice').hideNotice();
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

$('.party_attendee.online').entwine({
    onmatch: function() {
        this._super();
        if (this.closest('.gallery').chatIsOpen())
            this.find('.full_name').addClass('open_chat');
    },
    onunmatch: function() {
        this._super();
        this.find('.full_name').removeClass('open_chat');
    }
});

$('.smile.help').entwine({
    onmouseenter: function() {
        var message = '<p style="width: 400px;">You have 3 smiles to give at each party. '
        + ' The people you smile at will know that someone has smiled at them,'
        + ' but they will <strong>not</strong> know it was you unless they smile at you as well.</p>'
        this.notice(message, 'r');
    },
    onmouseleave: function() {
        $('#notice').hideNotice();
    }
});

$('#current_user')
        .live('user_came_online', function(e) {
            $('#party_attendee_' + e.user.id).addClass('online');
        })
        .live('user_went_offline', function(e) {
            $('#party_attendee_' + e.user.id).removeClass('online');
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
        el.addClass('new').css('display', 'inline-block');
        el.hide();

        el.bind('imageload', function() {
            if (insertPosition == 'first') {
                gallery.find('> ul').prepend(el);
            }
            else {
                gallery.attendee(insertPosition).closest('li').after(el);
            }
            el.fadeIn();
        });
    },
    attendee: function(user_id) {
        return this.find('#party_attendee_' + user_id);
    },
    partyID: function() {
        return parseInt(this.attr('data-party-id'));
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
        var senderGender = current_user().other_gender;
        var message = senderGender == 'M'
                ? '<p>You are about to ' + action + '.</p>'
                + '<p>He will know that someone has smiled at him, but he will <em>not</em> know it was you unless he smiles at you as well.</p>'

                : '<p>You are about to ' + action + '.</p>'
                + '<p>She will know that someone has smiled at her, but she will <em>not</em> know it was you unless she smiles back at you.</p>';

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
        this._super()
        this.find('.full_name').addClass('open_chat');
    },
    onunmatch: function() {
        this._super()
        this.find('.full_name').removeClass('open_chat');
    }
});

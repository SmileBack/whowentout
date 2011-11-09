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

    $('.party_group').entwine({
        phase: function() {
            return this.attr('data-phase');
        },
        selectedPartyID: function() {
            var attr = this.attr('data-selected-party-id');
            if (attr == '')
                return null;
            else
                return parseInt(attr);
        }
    });

    var PartyGroupPhase = {
        EarlyCheckin: 'EarlyCheckin',
        Checkin: 'Checkin',
        CheckinsClosed: 'CheckinsClosed'
    };

    $('.party_group label, .party_group input:radio').entwine({
        onclick: function(e) {
            e.preventDefault();
            e.stopPropagation();

            var partyGroup = this.closest('.party_group');
            var radio = this.is('input:radio') ? this : this.find('input:radio');
            var phase = partyGroup.phase();
            var selectedPartyID = partyGroup.selectedPartyID();

            if (phase == PartyGroupPhase.EarlyCheckin) {
                checkin_to_party(radio.val());
            }
            else if (phase == PartyGroupPhase.Checkin && !selectedPartyID) {
                WhoWentOut.Dialog.Show({
                    title: 'Confirm Check-in',
                    body: "<p>You are about to check in to <b>" + radio.attr('data-party-name') + "</b>.</p>"
                    + "<p>You can only check in to one party per night.</p>",
                    buttons: 'confirmcancel',
                    actions: {
                        confirm: function() {
                            checkin_to_party(radio.val());
                            userLogger.log('user_checkin_dialog_confirm', {
                                party_id: radio.val()
                            });
                        },
                        cancel: function() {
                            userLogger.log('user_checkin_dialog_cancel', {
                                party_id: radio.val()
                            });
                        }
                    }
                });
            }
            else if (phase == PartyGroupPhase.Checkin && selectedPartyID) {
                userLogger.log('user_attempt_checkin', {
                    party_id: radio.val()
                });
                WhoWentOut.Dialog.Show({
                    title: "Can't Change",
                    body: "You can't change your selection after the party.",
                    buttons: 'ok'
                });
            }
        }
    });

    $('.go_to_party_gallery :submit').entwine({
        onclick: function(e) {
            e.preventDefault();

            var partyGroup = this.closest('.party_group');
            if (partyGroup.phase() == PartyGroupPhase.EarlyCheckin) {
                userLogger.log('user_attempt_gallery_view');
                WhoWentOut.Dialog.Show({
                    title: "Hang In There",
                    body: "The party gallery will open after the party",
                    buttons: 'ok'
                });
            }
            else {
                this.closest('form').submit();
            }
        }
    });

});

$.when(app.load()).then(function() {
    
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

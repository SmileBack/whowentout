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
                        });
                    });
                });
            }
        });
    }
});


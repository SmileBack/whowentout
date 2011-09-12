$('#checkin_form').entwine({
    selectedPlace: function() {
        return {
            id: this.find('option:selected').attr('value'),
            name: this.find('option:selected').text()
        };
    },
    doorsOpenTime: function() {
        return new Date( this.attr('doors_opening_time') * 1000 );
    },
    doorsCloseTime: function() {
        return new Date( this.attr('doors_closing_time') * 1000 );
    }
});

$('#checkin_form :submit').entwine({
    form: function() {
        return this.closest('form');
    },
    onclick: function(e) {
        e.preventDefault();

        var doorsOpen = $('#wwo').doorsOpen();
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
        $('#checkin_form').submit();
    }
});


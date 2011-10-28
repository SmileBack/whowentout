//= require lib/jquery.js
//= require lib/jquery.entwine.js

if (window.console === undefined) {
    window.console = {
        log: function() {

        }
    };
}

$.ajaxSetup({
    cache: false
});

$('a').live('click', function() {
    if ($(this).hasClass('js'))
        return;

    $(window).data('isFormOrLink', true);
});
$('form').live('submit', function() {
    $(window).data('isFormOrLink', true);
});
$(window).bind('beforeunload', function() {
    if ($(window).data('isFormOrLink')) {
        $(window).trigger('beforechangepage');
    }
    else {
        $(window).trigger('leave');
    }
});

var WWO = null;
jQuery(function() {
    WWO = $('#wwo');
});

$('#wwo').entwine({
    onmatch: function() {
        var data = $.parseJSON(this.text());
        for (var k in data) {
            this.data(k, data[k]);
        }
        this._calculateTimeDelta();
    },
    onunmatch: function() {
    },
    timeDelta: function() {
        return this.data('timedelta');
    },
    doorsOpen: function() {
        return this.data('doorsOpen');
    },
    doorsClosed: function() {
        return ! this.doorsOpen();
    },
    showMutualFriendsDialog: function(path) {
        WWO.dialog.title('Mutual Friends').message('loading...')
        .setButtons('close').showDialog('friends_popup');
        WWO.dialog.refreshPosition();
        WWO.dialog.find('.dialog_body').load(path, function() {
            var count = WWO.dialog.find('.mutual_friends').attr('count') || 0;
            WWO.dialog.title(WWO.dialog.title() + ' (' + count + ')');
            WWO.dialog.refreshPosition();
        });
    },
    _calculateTimeDelta: function() {
        var serverUnixTs = parseInt($('#wwo').data('currentTime'));
        //Unix timestamp uses seconds while JS Date uses milliseconds
        var serverTime = new Date(serverUnixTs * 1000);
        var browserTime = new Date();
        var delta = (serverTime - browserTime);
        this.data('timedelta', delta);
    }
});

function cancelEvery(id) {
    clearInterval(id);
}

function every(seconds, fn) {
    return setInterval(fn, seconds * 1000);
}

function cancelAfter(id) {
    clearTimeout(id);
}

function after(seconds, fn) {
    return setTimeout(fn, seconds * 1000);
}

function current_time() {
    var time = new Date();
    var tzOffset = 0;//-50400;
    time.setMilliseconds(time.getMilliseconds() + $('#wwo').timeDelta() + tzOffset);
    return time;
}

function doors_closing_time() {
    var unixTs = $('#wwo').data('doorsClosingTime');
    //Unix timestamp uses seconds while JS Date uses milliseconds
    return new Date(unixTs * 1000);
}

function doors_opening_time() {
    var unixTs = $('#wwo').data('doorsOpeningTime');
    //Unix timestamp uses seconds while JS Date uses milliseconds
    return new Date(unixTs * 1000);
}

function yesterday_time() {
    var unixTs = parseInt($('#wwo').data('yesterdayTime'));
    //Unix timestamp uses seconds while JS Date uses milliseconds
    return new Date(unixTs * 1000);
}

function tomorrow_time() {
    var unixTs = parseInt($('#wwo').data('tomorrowTime'));
    //Unix timestamp uses seconds while JS Date uses milliseconds
    return new Date(unixTs * 1000);
}

$.fn.flash = function(count, speed) {
    var onAnimateComplete = function() {
    }
    var n = count || 2;
    speed = speed || 250;

    for (var i = 0; i < n - 1; i++) {
        $(this).animate({opacity: 0.5}, speed, 'swing').animate({opacity: 1}, speed, 'swing');
    }
    $(this).animate({opacity: 0.5}, speed, 'swing').animate({opacity: 1}, speed, 'swing', function() {
        onAnimateComplete.call(this);
    });
    return this;
}

$('a.scroll').entwine({
    onclick: function(e) {
        e.preventDefault();
        var flashSpotlight = parseInt(this.attr('data-flash-spotlight'));
        $(this.attr('href')).scrollTo(flashSpotlight);
    }
});

function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)')
    .exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

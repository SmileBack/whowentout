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

jQuery(function() {
    every(5, function() {
        $('#wwo').pingServer();
    });
    $('#wwo').pingServer();

    every(10, function() {
        $('#wwo').updateOfflineUsers();
    });

    $(window).bind('leave', function() {
        $('#wwo').pingLeavingServer();
    });
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
    user: function(id, object) {
        var self = this;
        
        if (id == null)
            return;

        if (id == 'current')
            id = this.currentUserID();

        if (this.data('users') == null)
            this.data('users', {});

        if (object === undefined) { //get

            var u = null;

            if (this.data('users')[id] != null) { //already present in user cache
                return this.data('users')[id];
            }
            else if (this.data('users')[id] == null && $('#user_json_' + id).length > 0) { //user data is present in html dom
                u = $.parseJSON( $('#user_json_' + id).text() );
                this.user(id, u);
                return u;
            }
            else {
                // Last resort: send an ajax request.
                // Returns a deferred object so you have to use $.when(..).then(fn)
                var dfd = $.Deferred();
                $.getJSON('/user/info/' + id, function(response) {
                    if (response.success) {
                        self.user(id, response.user);
                        dfd.resolve(response.user);
                    }
                });
                return dfd.promise();
            }
        }
        else { //set
            var users = this.data('users') || {};
            users[id] = object;
            this.data('users', users);
        }
    },
    pingServer: function() {
        $.ajax({
            url: '/user/ping',
            type: 'get',
            success: function(response) {
                //console.log('pinged server!');
            }
        });
    },
    pingLeavingServer: function() {
        $.ajax({
            url: '/user/ping_leaving',
            type: 'get',
            async: false,
            success: function(response) {
                //console.log('pinged leaving!');
            }
        });
    },
    updateOfflineUsers: function() {
        $.ajax({
            url: '/college/update_offline_users',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                //console.log('updated offline users');
                //console.log(response);
            }
        });
    },
    showMutualFriendsDialog: function(path) {
        WWO.dialog.title('Mutual Friends').message('loading...')
                .setButtons('close').showDialog('friends_popup');
        WWO.dialog.refreshPosition();
        WWO.dialog.find('.dialog_body').load(path, function() {
            WWO.dialog.refreshPosition();
        });
    },
    currentUser: function() {
        return this.user('current');
    },
    currentUserID: function() {
        return this.data('currentUserID');
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

function current_user() {
    return $('#wwo').currentUser();
}

function user(id, object) {
    return $('#wwo').user(id, object);
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

jQuery.event.special.imageload = {
    setup: function(data, namespaces) {
        var self = $(this);
        var images = $(this).is('img') ? $(this) : $(this).find('img');
        var numImages = images.length;
        var numLoaded = 0;

        images.each(function() {
            var src = $(this).attr('src');
            var img = new Image();
            img.onload = function() {
                numLoaded++;
                if (numLoaded >= numImages)
                    self.trigger('imageload');
            };
            img.src = src;
        });
    },
    teardown: function(namespaces) {
    },
    handler: function(event) {
    }
};

$.fn.whenShown = function(fn) {
    var props = { position: 'absolute', visibility: 'hidden', display: 'block' },
            hiddenParents = this.parents().andSelf().not(':visible');

    //set style for hidden elements that allows computing
    var oldProps = [];
    hiddenParents.each(function() {
        var old = {};

        for (var name in props) {
            old[ name ] = this.style[ name ];
            this.style[ name ] = props[ name ];
        }

        oldProps.push(old);
    });

    var result = fn.call($(this));

    //reset styles
    hiddenParents.each(function(i) {
        var old = oldProps[i];
        for (var name in props) {
            this.style[ name ] = old[ name ];
        }
    });

    return result;
}

//Optional parameter includeMargin is used when calculating outer dimensions
$.fn.hiddenDimensions = function(includeMargin) {
    return this.whenShown(function() {
        return {
            width: this.width(),
            outerWidth: this.outerWidth(),
            innerWidth: this.innerWidth(),
            height: this.height(),
            innerHeight: this.innerHeight(),
            outerHeight: this.outerHeight(),
            margin: $.fn.margin ? this.margin() : null,
            padding: $.fn.padding ? this.padding() : null,
            border: $.fn.border ? this.border() : null
        };
    });
}

function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)')
            .exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

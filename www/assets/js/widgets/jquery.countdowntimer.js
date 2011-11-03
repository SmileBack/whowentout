//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require lib/timeinterval.js
//= require whowentout.queue.js

(function($) {

    function pad(number, length) {

        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }

        return str;

    }

//    var frameWidth = 53;
//    var topFrameHeight = 38;
//    var bottomFrameHeight = 38;
//    var topFrameImage = '/assets/images/digits_top.png';
//    var bottomFrameImage = '/assets/images/digits_bottom.png';

    var frameWidth = 26.5;
    var topFrameHeight = 19;
    var bottomFrameHeight = 19;
    var topFrameImage = '/assets/images/digits_top_small.png';
    var bottomFrameImage = '/assets/images/digits_bottom_small.png';

    var numFramesTop = 3;
    var numFramesBottom = 4;

    $('.time_counter').entwine({
        onmatch: function() {
            console.log('time_counter :: onmatch');
            this._super();

            var self = this;

            function update() {
                self.updateTimer();
            }

            var interval = setInterval(update, 1000);
            this.data('interval', interval);
        },
        onunmatch: function() {
            this._super();
            console.log('time_counter :: onUNmatch');

            var interval = $(this).data('interval');
            clearInterval(interval);
        },
        targetTime: function() {
            var target = this.attr('data-target');
            if (target == null || target == '')
                return null;

            return new Date(parseInt(target) * 1000);
        },
        getCurrentTime: function() {
            return new Date();
        },
        updateTimer: function() {
            if (this.targetTime() == null)
                return;

            var timeLeft = this.getCurrentTime().timeUntil(this.targetTime());
            
            if (!timeLeft.isNegative())
                $('.time_counter').flipTo(timeLeft);
        },
        flipTo: function(timeInterval) {
            this.find('.days').flipTo(timeInterval.get('d'));
            this.find('.hours').flipTo(timeInterval.get('h'));
            this.find('.minutes').flipTo(timeInterval.get('m'));
            this.find('.seconds').flipTo(timeInterval.get('s'));
        }
    });

    $('.counter').entwine({
        onmatch: function() {
            console.log('.counter || onmatch');
            this._super();
            for (var i = 0; i < this.numDigits(); i++) {
                this.append('<div class="digit"/>');
            }
        },
        onunmatch: function() {
            console.log('.counter || onUNmatch');
            this._super();
        },
        flipTo: function(number) {
            var number = pad(number, this.numDigits());
            var digit;
            for (var i = 0; i < this.numDigits(); i++) {
                var digitEl = this.find('.digit:eq(' + i + ')');
                digit = parseInt(number.substring(i, i + 1));
                digitEl.flipTo(digit);
            }
        },
        numDigits: function() {
            return parseInt(this.attr('data-length'));
        }
    });

    function FlipToTask(options) {
        var el = options.el,
        endDigit = options.endDigit;

        var dfd = $.Deferred();
        var n = 0;
        var startDigit = $(el).getDigit();

        if (startDigit == endDigit) {
            dfd.resolve();
            return dfd.promise();
        }

        var id = setInterval(function() {
            $(el).setTransitionFrame(startDigit, endDigit, n);
            n++;

            if (n == 6) {
                clearInterval(id);
                $(el).setDigit(endDigit);
                dfd.resolve();
            }
        }, 75);
        return dfd.promise();
    }

    $('.digit').entwine({
        onmatch: function() {
            console.log('.digit :: onmatch');
            this.css({
                width: frameWidth + 'px',
                height: (topFrameHeight + bottomFrameHeight) + 'px'
            });

            this.append('<div class="top"/>');
            this.append('<div class="bottom"/>');

            var queue = new WhoWentOut.Queue();
            this.data('queue', queue);

            this.setDigit(0);

            this._super();
        },
        onunmatch: function() {
            this._super();
            
            console.log('.digit :: onUNmatch');
            console.log(this);
            console.log($(this).queue());
            $(this).queue().clear();
            console.log('.digit :: queue clear');
            $(this).removeData('queue');
            console.log('.digit :: removed data queue');
        },
        queue: function() {
            return this.data('queue');
        },
        flipTo: function(endDigit) {
            this.queue().add(FlipToTask, {
                el: this,
                endDigit: endDigit
            });
            while (this.queue().count() > 2)
                this.queue().drop();
        },
        getDigit: function() {
            return this.data('val') || 0;
        },
        setDigit: function(d) {
            this.topFrame().setDigitFrame(d);
            this.bottomFrame().setDigitFrame(d);
            this.data('val', d);
        },
        setTransitionFrame: function(startDigit, endDigit, frame) {
            if (frame < 3) {
                this.topFrame().setDigitFrame(startDigit, frame);
            }
            else {
                this.topFrame().setDigitFrame(endDigit, 0);
            }

            if (frame <= 1) {
                this.bottomFrame().setDigitFrame(startDigit, frame);
            }
            else if (frame >= 2 && frame <= 3) {
                this.bottomFrame().setDigitFrame(endDigit, frame);
            }
            else {
                this.bottomFrame().setDigitFrame(endDigit, 0);
            }
        },
        topFrame: function() {
            return this.find('.top');
        },
        bottomFrame: function() {
            return this.find('.bottom');
        }
    });

    $('.digit .top').entwine({
        onmatch: function() {
            this._super();
            this.css({
                'background-image': 'url(' + topFrameImage + ')',
                'background-repeat': 'no-repeat',
                'width': frameWidth + 'px',
                'height': topFrameHeight + 'px'
            });
            this.setDigitFrame(0, 0);
        },
        onunmatch: function() {
            this._super();
        },
        setDigitFrame: function(d, frame) {
            frame = frame || 0;

            var top = d * topFrameHeight;
            var left = frame * frameWidth;

            this.css('background-position', '-' + left + 'px -' + top + 'px');
        }
    });

    $('.digit .bottom').entwine({
        onmatch: function() {
            this._super();
            this.css({
                'background-image': 'url(' + bottomFrameImage + ')',
                'background-repeat': 'no-repeat',
                'width': frameWidth + 'px',
                'height': bottomFrameHeight + 'px'
            });
            this.setDigitFrame(0, 0);
        },
        onunmatch: function() {
            this._super();
        },
        setDigitFrame: function(d, frame) {
            frame = frame || 0;

            var top = d * bottomFrameHeight;
            var left = frame * frameWidth;

            this.css('background-position', '-' + left + 'px -' + top + 'px');
        }
    });

})
(jQuery);

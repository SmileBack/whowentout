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

    var frameWidth = 27;
    var topFrameHeight = 19;
    var bottomFrameHeight = 19;
    var topFrameImage = '/assets/images/digits_top_small.png';
    var bottomFrameImage = '/assets/images/digits_bottom_small.png';

    var numFramesTop = 3;
    var numFramesBottom = 4;

    $('.time_counter').entwine({
        onmatch: function() {
            this._super();

            var self = this;

            function update() {
                self.updateTimer();
            }

            var interval = setInterval(update, 1000);
            this.attr('data-interval', interval);

            setTimeout(function() {
                self.updateTimer(true);
            }, 200);
        },
        onunmatch: function() {
            this._super();
            var interval = parseInt( this.attr('data-interval') );
            clearInterval(interval);
        },
        targetTime: function() {
            var target = this.attr('data-target');
            if (target == null || target == '')
                return null;

            return new Date(parseInt(target) * 1000);
        },
        getCurrentTime: function() {
            if (window.current_time)
                return window.current_time();
            else
                return new Date();
        },
        updateTimer: function(instant) {
            if (this.targetTime() == null)
                return;

            var timeLeft = this.getCurrentTime().timeUntil(this.targetTime());

            if (!timeLeft.isNegative())
                this.flipTo(timeLeft, instant);
        },
        flipTo: function(timeInterval, instant) {
            this.find('.days').flipTo(timeInterval.get('d'), instant);
            this.find('.hours').flipTo(timeInterval.get('h'), instant);
            this.find('.minutes').flipTo(timeInterval.get('m'), instant);
            this.find('.seconds').flipTo(timeInterval.get('s'), instant);
        }
    });

    $('.counter').entwine({
        onmatch: function() {
            this._super();
            for (var i = 0; i < this.numDigits(); i++) {
                this.append('<div class="digit"/>');
            }
            $(document).trigger('DOMMaybeChanged');
        },
        onunmatch: function() {
            this._super();
        },
        flipTo: function(number, instant) {
            var number = pad(number, this.numDigits());
            var digit;
            for (var i = 0; i < this.numDigits(); i++) {
                var digitEl = this._getDigitEl(i);
                digit = parseInt(number.substring(i, i + 1));
                digitEl.flipTo(digit, instant);
            }
        },
        val: function(v) {
            var numDigits = this.numDigits();
            if (v === undefined) {
                var value = 0;
                this.eachDigit(function(index, place) {
                    value += Math.pow(10, place) * this.val();
                });
                return value;
            }
            else {
                var number = pad(v, this.numDigits());
                this.eachDigit(function(index) {
                    var digitValue = parseInt(number.substring(index, index + 1));
                    this.val(digitValue);
                });
            }
        },
        eachDigit: function(fn) {
            var place, numDigits = this.numDigits();
            for (var i = 0; i < numDigits; i++) {
                place = numDigits - i - 1;
                fn.call(this._getDigitEl(i), i, place);
            }
        },
        _getDigitEl: function(index) {
            return this.find('.digit:eq(' + index + ')');
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
        var startDigit = $(el).val();

        if (startDigit == endDigit) {
            dfd.resolve();
            return dfd.promise();
        }

        var id = setInterval(function() {
            $(el).setTransitionFrame(startDigit, endDigit, n);
            n++;

            if (n == 6) {
                clearInterval(id);
                $(el).val(endDigit);
                dfd.resolve();
            }
        }, 75);
        return dfd.promise();
    }

    $('.digit').entwine({
        onmatch: function() {
            this.css({
                width: frameWidth + 'px',
                height: (topFrameHeight + bottomFrameHeight) + 'px'
            });

            this.append('<div class="top"/>');
            this.append('<div class="bottom"/>');
            $(document).trigger('DOMMaybeChanged');

            var queue = new WhoWentOut.Queue();
            this.data('queue', queue);

            this.val(0);

            this._super();
        },
        onunmatch: function() {
            this._super();

            var queue = this.queue();
            queue.clear();
            this.removeData('queue');
        },
        queue: function() {
            return this.data('queue');
        },
        flipTo: function(endDigit, instant) {
            if (instant) {
                this.val(endDigit);
            }
            else {
                this.queue().add(FlipToTask, {
                    el: this,
                    endDigit: endDigit
                });
                while (this.queue().count() > 2)
                    this.queue().drop();
            }
        },
        val: function(v) {
            if (v === undefined) {
                return this.data('val') || 0;
            }
            else {
                this.topFrame().setDigitFrame(v);
                this.bottomFrame().setDigitFrame(v);
                this.data('val', v);
            }
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

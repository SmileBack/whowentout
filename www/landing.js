(function($) {

    function pad(number, length) {

        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }

        return str;

    }

    var frameWidth = 53;
    var topFrameHeight = 39;
    var bottomFrameHeight = 64;

    var numFramesTop = 3;
    var numFramesBottom = 4;

    $('.time_counter').entwine({
        flipTo: function(timeInterval) {
            this.find('.days').flipTo(timeInterval.get('d'));
            this.find('.hours').flipTo(timeInterval.get('h'));
            this.find('.minutes').flipTo(timeInterval.get('m'));
            this.find('.seconds').flipTo(timeInterval.get('s'));
        }
    });

    $('.counter').entwine({
        onmatch: function() {
            for (var i = 0; i < this.numDigits(); i++) {
                this.append('<div class="digit"></div>');
            }
        },
        onunmatch: function() {
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

    $('.digit').entwine({
        onmatch: function() {
            this.css({
                width: frameWidth + 'px',
                height: (topFrameHeight + bottomFrameHeight) + 'px'
            });

            this.append('<div class="top"/>');
            this.append('<div class="bottom"/>');

            var queue = new WhoWentOut.Queue();
            this.data('queue', queue);

            this.setDigit(0);
        },
        onummatch: function() {
        },
        queue: function() {
            return this.data('queue');
        },
        flipTo: function(endDigit) {
            var animateTask = function(el, endDigit) {
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

            var task = _.bind(animateTask, {}, this, endDigit);
            this.queue().add(task);
            
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
            this.css({
                'background-image': 'url(digits.png)',
                'background-repeat': 'no-repeat',
                'width': frameWidth + 'px',
                'height': topFrameHeight + 'px'
            });
            this.setDigitFrame(0, 0);
        },
        onunmatch: function() {
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
            this.css({
                'background-image': 'url(digits.png)',
                'background-repeat': 'no-repeat',
                'width': frameWidth + 'px',
                'height': 38 + 'px'
            });
            this.setDigitFrame(0, 0);
        },
        onunmatch: function() {
        },
        setDigitFrame: function(d, frame) {
            frame = frame || 0;

            var top = 10 * topFrameHeight + d * bottomFrameHeight;
            var left = frame * frameWidth;

            this.css('background-position', '-' + left + 'px -' + top + 'px');
        }
    });

})
(jQuery);

jQuery(function($) {
    var target = new Date("October 6, 2011 22:13:00");

    function updateCounter() {
        var currentTime = new Date();
        var timeLeft = currentTime.timeUntil(target);
        $('.time_counter').flipTo(timeLeft);
    }

    setInterval(updateCounter, 1000);

    $('#countdown').delay(1200).fadeIn(500);

});

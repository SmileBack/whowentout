(function() {
    $.Class.extend('WhoWentOut.Landing.Digit', {}, {
        init: function(el) {
            this._el = $(el);
            this._el.css({
                'background-image': 'url(digits.png)',
                'width': digitWidth + 'px',
                'height': digitHeight + 'px'
            });
            this._el.data('digit-value', 0);
        },
        setFrame: function(frame) {
            this._el.css('background-position', '0 -' + (digitHeight * frame) + 'px');
            this._el.data('digit-value', Math.floor(frame / digitImages));
        },
        animateTo: function() {
            var dfd = $.Deferred();
            var delay = 50;
            var startFrame = el.data('digit-value') * digitImages;
            var endFrame = value * digitImages + 1;
            var curFrame = startFrame;

            var id = setInterval(function() {
                setDigitFrame(el, curFrame);
                curFrame = (curFrame + 1) % totalFrames;

                if (curFrame == endFrame) {
                    clearInterval(id);
                    dfd.resolve();
                }
            }, delay);

            return dfd.promise();
        }
    });
})();

jQuery(function($) {
    var digitImages = 6,
    digitWidth = 53,
    digitHeight = 77,
    totalFrames = digitImages * 10;

    function setDigitFrame(el, frame) {
        $(el).css('background-position', '0 -' + (digitHeight * frame) + 'px');
        $(el).data('digit-value', Math.floor(frame / digitImages));
    }

    function animateDigitTo(el, value) {
        el = $(el);

        var dfd = $.Deferred();
        var delay = 50;
        var startFrame = el.data('digit-value') * digitImages;
        var endFrame = value * digitImages + 1;
        var curFrame = startFrame;

        var id = setInterval(function() {
            setDigitFrame(el, curFrame);
            curFrame = (curFrame + 1) % totalFrames;

            if (curFrame == endFrame) {
                clearInterval(id);
                dfd.resolve();
            }
        }, delay);

        return dfd.promise();
    }

    function initDigit(digit) {

    }

    var digit = $('#countdown .digit:eq(0)');

    initDigit(digit);
    setDigitFrame(digit, 0);
    animateDigitTo(digit, 3);

    window.to = function(i) {
        animateDigitTo(digit, i);
    }
});

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

//= require jquery.js
//= require jquery.entwine.js
//= require jquery.ext.js

$('.filter_table').entwine({
    onmatch: function() {
        var self = this;
        head.css('/css/jquery.datatables.css');
        head.js('/js/jquery.datatables.js', function () {
            self.dataTable({
                iDisplayLength: 100,
                aaSorting: []
            });
        });
    },
    onunmatch: function() {}
});

$('#flash_message').entwine({
    onmatch:function () {
        var flashMessage = this;
        setTimeout(function () {
            flashMessage.fadeOut();
        }, 7000);
    },
    onunmatch:function () {
    }
});

$('form.confirm').entwine({
    onsubmit: function(e) {
        return confirm("Are you sure?");
    }
});


//= require jquery.js
//= require jquery.entwine.js
//= require jquery.ext.js

$('.filter_table').entwine({
    onmatch: function() {
        var self = this;
        head.css('/css/jquery.datatables.css');
        head.js('/js/jquery.datatables.js', function () {
            self.dataTable({
                iDisplayLength: 50
            });
        });
    },
    onunmatch: function() {}
});

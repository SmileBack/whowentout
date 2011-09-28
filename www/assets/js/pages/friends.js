(function($) {

    $('.friends_breakdown').entwine({
        onmatch: function() {
            this.loadGalleries();
        },
        onunmatch: function() {
        },
        date: function() {
            return this.attr('data-date');
        },
        selectParty: function(party_id) {
            this.find('.friends_at_party').hide();
            this.find('.friends_at_party[data-party-id=' + party_id + ']').show();
        },
        loadGalleries: function() {
            var self = this;
            $.when(this.loadData()).then(function(data) {
                self.find('.friend_galleries').html(data.friend_galleries_view);
                var largestGallery = self.largestGallery();
                if (largestGallery)
                    largestGallery.show();
            });
        },
        hideLoadingMessage: function() {
            this.find('.loading_message').hide();
        },
        largestGallery: function() {
            var max = -1;
            var largestGallery = null;
            this.find('.friends_at_party').each(function() {
                if ($(this).count() > max) {
                    largestGallery = $(this);
                    max = $(this).count();
                }
            });
            return largestGallery;
        },
        loadData: function() {
            if (this.data('friendsData'))
                return this.data('friendsData');

            var dfd = $.Deferred();
            var self = this;
            $.ajax({
                url: '/dashboard/where_friends_went_data',
                type: 'post',
                dataType: 'json',
                data: {date: this.date() },
                success: function(response) {
                    self.hideLoadingMessage();
                    self.data('friendsData', response);
                    dfd.resolve(response);
                }
            });

            return dfd.promise();
        }
    });

    $('.friends_breakdown .piechart').entwine({
        onmatch: function() {
            var self = this;
            $.when(this.closest('.friends_breakdown').loadData()).then(function(data) {
                self.displayChart(data.breakdown);
            });
        },
        onummatch: function() {
        },
        friendsBreakdown: function() {
            return this.closest('.friends_breakdown');
        },
        date: function() {
            return this.friendsBreakdown().date();
        },
        displayChart: function(breakdown) {
            var self = this;
            this.data('breakdown', breakdown);

            

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Party');
            data.addColumn('number', 'Attendees');
            data.addColumn('number', 'party_id');
            data.addRows(breakdown);

            var width = this.width();
            var height = this.height();

            // Set chart options
            var options = {width: width, height: height, pieSliceText: 'value', backgroundColor: 'transparent'};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(this.get(0));
            chart.draw(data, options);

            google.visualization.events.addListener(chart, 'select', function() {
                var sel = chart.getSelection();

                if (sel.length == 0)
                    return;

                var rowId = sel[0].row;
                var row = breakdown[rowId];
                var obj = {id: row[2]};
                self.trigger({type: 'select', party: obj});
            });
        }
    });

    $('.friends_breakdown .friends_at_party').entwine({
       partyID: function() {
           return parseInt( this.attr('data-party-id') );
       },
       count: function() {
           return parseInt( this.attr('data-count') );
       }
    });

})(jQuery);

(function($) {

    $('.friends_breakdown .piechart').live('select', function(e) {
        $(this).friendsBreakdown().selectParty(e.party.id);
    });

    

})(jQuery);

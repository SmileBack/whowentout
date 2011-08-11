var GoogleChartsLoadedDfd = $.Deferred();
var GoogleChartsLoaded = function() {
  return GoogleChartsLoadedDfd.promise();
}
google.load('visualization', '1', {'packages':['corechart']});
google.setOnLoadCallback(GoogleChartsLoadedDfd.resolve);

$('.friendschart').live('select', function(e, obj) {
  $(this).deselectParty().selectParty(obj.partyID);
});

$('.tabs').entwine({
  onmatch: function() {
    this.find('> li').hide();
    this.find('> li:first').addClass('selected').show();
  },
  onunmatch: function() {},
  tab: function(id) {
    return this.find('> li[val=' + id + ']');
  },
  selectTab: function(id) {
    this.find('> li.selected').removeClass('.selected').hide();
    this.tab(id).addClass('selected').show();
  }
});

$('.friendschart').entwine({
  onmatch: function() {
    var self = this;
    $.when(GoogleChartsLoaded())
    .then(function() {
      self.initChart();
    });
  },
  onunmatch: function() {},
  tabs: function() {
    return this.closest('section').find('.tabs');
  },
  date: function() {
    return this.attr('date');
  },
  deselectParty: function() {
    return this;
  },
  selectParty: function(partyID) {
    this.tabs().selectTab(partyID);
    return this;
  },
  whereFriendsWentData: function() {
    return $.ajax({
      url: '/dashboard/where_friends_went_data',
      type: 'post',
      dataType: 'json',
      data: {date: this.date() },
      context: this
    });
  },
  onselect: function() {
  },
  initChart: function() {
    var self = this;
    var data = this.whereFriendsWentData();
    
    data.success(function(rows) {
      self._rows = rows;
      if (self._rows.length == 0) {
        self.setEmptyHtml();
        return;
      }

      self._data = new google.visualization.DataTable();
      self._data.addColumn('string', 'Party');
      self._data.addColumn('number', 'User');
      self._data.addColumn('number', 'party_id');
      self._data.addRows(self._rows);
      
      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart( this.get(0) );
      self.data('chart', chart);
      chart.draw(self._data, {width: 500, height: 300, pieSliceText: 'value'});

      google.visualization.events.addListener(chart, 'select', function() {
        var sel = chart.getSelection();

        if (sel.length == 0)
          return;

        var rowId = sel[0].row;
        var row = self._rows[rowId];
        var obj = {partyName: row[0], userFullName: row[1], partyID: row[2]};
        self.trigger('select', [ obj ]);
      });
    });
  },
  setEmptyHtml: function() {
    this.html(this.emptyHtml());
  },
  emptyHtml: function() {
    return '<div class="chartArea">'
    + '<svg class="chart" width="500" height="300">'
    + '<defs class="defs"></defs>'
    + '  <g>'
    + '    <ellipse cx="191.5" cy="151.5" rx="92" ry="92" stroke="#ffffff" stroke-width="1" fill="#3366cc"></ellipse>'
    + '    <text text-anchor="middle" x="191.5" y="155" font-family="Arial" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">empty</text>'
    + '  </g>'
    + '</svg>'
    + '</div>';
  }
});

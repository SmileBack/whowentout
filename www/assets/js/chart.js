// Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

// // Callback that creates and populates a data table, 
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
  $('#friendschart').initChart();
}

$('#friendschart').live('select', function(e, obj) {
  $('.party_tab.selected').removeClass('selected');
  $('.party_tab' + obj.partyID).addClass('selected');
});

$('#friendschart').entwine({
  onmatch: function() {
  },
  onunmatch: function() {},
  initChart: function() {
    var self = this;
    // Create our data table.
    this._data = new google.visualization.DataTable();
    this._rows = $('#wwo').whereFriendsWentData();
    this._data.addColumn('string', 'Party');
    this._data.addColumn('number', 'User');
    this._data.addColumn('number', 'party_id');
    this._data.addRows(this._rows);

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart( this.get(0) );
    $(this).data('chart', chart);
    chart.draw(this._data, {width: 500, height: 300, pieSliceText: 'value'});

    google.visualization.events.addListener(chart, 'select', function() {
      var sel = chart.getSelection();
      
      if (sel.length == 0)
        return;
      
      var rowId = sel[0].row;
      var row = self._rows[rowId];
      var obj = {partyName: row[0], userFullName: row[1], partyID: row[2]};
      self.trigger('select', [ obj ]);
    });
    
//    google.visualization.events.addListener(chart, 'onmouseover', function(e) {
//      e.preventDefault();
//      e.stopPropagation();
//    });
    
  },
  onselect: function() {
  }
});

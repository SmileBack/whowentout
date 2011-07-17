// Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table, 
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
  // Create our data table.
  var data = new google.visualization.DataTable();
  var rows = $('#wwo').whereFriendsWentData();
  data.addColumn('string', 'Party');
  data.addColumn('number', 'User');
  data.addRows(rows);

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart( document.getElementById('pie') );
  chart.draw(data, {width: 400, height: 240});
}

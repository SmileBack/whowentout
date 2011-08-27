
<?= load_view('party_list_view', array(
  'date' => college()->party_day(+1),
)) ?>

<?= load_view('party_list_view', array(
  'date' => college()->party_day(+2),
)) ?>

<?= load_view('party_list_view', array(
  'date' => college()->party_day(+3),
)) ?>

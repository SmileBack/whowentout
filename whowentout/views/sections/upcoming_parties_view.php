
<?= load_view('party_list_view', array(
  'date' => college()->day(+1),
)) ?>

<?= load_view('party_list_view', array(
  'date' => college()->day(+2),
)) ?>

<?= load_view('party_list_view', array(
  'date' => college()->day(+3),
)) ?>

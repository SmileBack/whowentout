<div class="parties_this_week">
    <?= load_view('party_list_view', array(
      'date' => college()->this_week_party_day(0),
    )) ?>

    <?= load_view('party_list_view', array(
      'date' => college()->this_week_party_day(1),
    )) ?>

    <?= load_view('party_list_view', array(
      'date' => college()->this_week_party_day(2),
    )) ?>
</div>
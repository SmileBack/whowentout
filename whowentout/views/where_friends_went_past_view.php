<?php
  $dates = array(
    college()->party_day(-1, TRUE),
    college()->party_day(-2, TRUE),
    college()->party_day(-3, TRUE),
  );
?>
<?php foreach ($dates as $date): ?>
<section class="friends_view">
  <h1><?= $date->format('l, F jS') ?></h1>
  <div class="section_body">
    <?= load_view('where_friends_went_view', array('date' => $date->modify('+1 day'))) ?>
  </div>
</section>
<?php endforeach; ?>

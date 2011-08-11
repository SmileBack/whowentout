<?php
  $dates = array(
    college()->party_day(-1, TRUE),
    college()->party_day(-2, TRUE),
    college()->party_day(-3, TRUE),
  );
?>
<?php foreach ($dates as $date): ?>
<section class="where_friends_went_section">
  <h1><?= $date->format('l, F jS') ?></h1>
  <div class="section_content">
    <?= load_view('where_friends_went_view', array('date' => $date->modify('+1 day'))) ?>
  </div>
</section>
<?php endforeach; ?>

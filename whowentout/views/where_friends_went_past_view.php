<?php foreach (college()->recent_dates as $date): ?>
<section class="where_friends_went_section">
  <h1><?= $date->format('l, F jS') ?></h1>
  <div class="section_content">
    <?= load_view('where_friends_went_view', array('date' => $date->modify('+1 day'))) ?>
  </div>
</section>
<?php endforeach; ?>

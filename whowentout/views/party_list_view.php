<div class="party_list">
  <h2><?= $date->format('l, M. jS') ?></h2>
  <div class="body">
    <?php foreach (college()->parties_on($date) as $party): ?>
    <div><?= $party->place->name ?></div>
    <?php endforeach; ?>
  </div>
</div>
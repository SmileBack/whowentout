<div class="party_list">
  <h1><?= $date->format('D, F jS') ?></h1>
  <ul>
    <?php foreach (college()->parties_on($date) as $party): ?>
    <li><?= $party->place->name ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<div id="top_parties">
  <h2>This is a list of last night's most popular parties.</h2>
  <h2>The list will be continually updated throughout the day as more people check in, and will be finalized at 11pm.</h2>
  <ol>
    <?php foreach ($college->top_parties() as $party): ?>
      <li data-id="<?= $party->id ?>">
        <?= $party->place->name; ?>
      </li>
    <?php endforeach; ?>
  </ol>
  <p>You can click <?= anchor ('top_parties', 'here')?> to see top parties from past nights.</p>
</div>
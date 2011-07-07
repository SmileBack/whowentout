<div id="top_parties">
  <p>This is a list of last night's most popular parties. The list will be continually updated throughout the day as more people check in, and will be finalized at 11pm.</p>
  <ol>
    <?php foreach ($top_parties as $party): ?>
      <li>
        <?= $party->place->name; ?>
      </li>
    <?php endforeach; ?>
  </ol>
  <p>You can click <?= anchor ('top_parties', 'here')?> to see top parties from past nights.</p>
</div>
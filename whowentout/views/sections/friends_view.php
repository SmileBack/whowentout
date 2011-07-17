<ul>
  <?php foreach (current_user()->where_friends_went() as $party_id => $user_ids): ?>
    <?php $party = party($party_id); ?>
    <li>
      <h3><?= $party->place->name ?></h3>
      <ul>
        <?php foreach ($user_ids as $user_id): ?>
        <?php $user = user($user_id); ?>
          <li><?= $user->full_name ?></li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<div id="pie"></div>
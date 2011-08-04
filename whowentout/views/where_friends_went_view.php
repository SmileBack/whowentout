<ul class="where_friends_went">
  <li class="empty_message party_tab selected">
    Here is an empty message.
  </li>
  <?php foreach (current_user()->where_friends_went( $date ) as $party_id => $user_ids): ?>
    <?php $party = party($party_id); ?>
    <li class="party_tab <?= 'party_tab' . $party->id ?>">
      <h3><?= $party->place->name ?></h3>
      <ul class="clearfix">
        <?php foreach ($user_ids as $user_id): ?>
        <?php $user = user($user_id); ?>
          <li>
            <div class="thumb">
              <?= $user->thumb ?>
            </div>
            <div class="full_name">
              <?= $user->full_name ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<div class="friendschart" date="<?= $date->format('Y-m-d') ?>"></div>
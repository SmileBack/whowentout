<ul class="where_friends_went">
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

<?php if (isset($past_link) && $past_link): ?>
<div class="where_friends_went_past" style="float: left; clear: both;">
  <?= anchor('dashboard/where_friends_went', 'Where Friends Went in the Past') ?>
</div>
<?php endif; ?>
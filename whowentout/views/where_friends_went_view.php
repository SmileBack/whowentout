<h2>Click on the circle below to see where your friends went out last night.</h2>

<ul class="tabs">
  <?php foreach (current_user()->where_friends_went( $date ) as $party_id => $user_ids): ?>
    <?php $party = party($party_id); ?>
    <li val="<?= $party->id ?>">
      <h3><?= $party->place->name ?></h3>
      <ul class="people">
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
    You can 
    <?= anchor('dashboard/where_friends_went', 'click here') ?>
    to see where your friends went in the past.
  </div>

  <?= form_open('user/invite', array('class' => 'invite_friends')) ?>
    <input class="friends autocomplete" name="friend_facebook_id" />
    <input class="submit_button" type="submit" value="Invite" />
  <?= form_close() ?>
    
<?php endif; ?>
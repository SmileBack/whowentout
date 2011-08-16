<h3>Click on the circle below to see where your friends went out last night.</h3>

<ul class="tabs">
  <?php foreach (current_user()->where_friends_went( $date ) as $party_id => $user_ids): ?>
    <?php $party = party($party_id); ?>
    <li val="<?= $party->id ?>">
      <h2><?= $party->place->name ?></h2>
      <div class="people">
        <ul>
          <?php foreach ($user_ids as $user_id): ?>
          <?php $user = user($user_id); ?>
            <li>
              <div class="thumb">
                <?= $user->thumb ?>
              </div>
              <div class="full_name">
                <span class="first_name"><?= $user->first_name ?></span>
                <span class="last_name"><?= $user->last_name ?></span>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
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
    <input class="friends autocomplete" extra_class="friends" name="friend_facebook_id" source="/user/friends"/>
    <input class="submit_button" type="submit" value="Invite" />
  <?= form_close() ?>
    
<?php endif; ?>
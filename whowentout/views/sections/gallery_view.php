
<p><?= $user->smiles_received_message($party->id) ?></p>
<p><?= $user->smiles_left_message($party->id) ?></p>

<? foreach ($user->matches($party->id) as $match): ?>
  <p>
    You and <?= $match->full_name; ?>  have smiled at each other!
    <?= $match->anchor_facebook_message() ?>
  </p>
<? endforeach; ?>

<h3>Sorting</h3>
<ul class="sortbar">
  <li class="sort_checkin_time <?= $sort == 'checkin_time' ? 'selected' : '' ?>">
    <?= anchor("party/$party->id?sort=checkin_time", "Checkin Time") ?>
  </li>
  <li class="sort_first_name <?= $sort == 'name' ? 'selected' : '' ?>">
    <?= anchor("party/$party->id?sort=name", "Name") ?>
  </li>
  <li class="sort_gender <?= $sort == 'gender' ? 'selected' : '' ?>">
    <?= anchor("party/$party->id?sort=gender", "Gender") ?>
  </li>
</ul>

<ul class="gallery" data-sort="<?= $sort ?>">
  
  <?php foreach ($party_attendees as $key => $attendee): ?>
  <li>
    
    <?= $attendee->pic; ?>
    
    <div class="caption">
      <p><?= $attendee->first_name; ?> <?= $attendee->last_name ?></p>
      <p><?= $attendee->college->name; ?> <?= $attendee->grad_year; ?></p>
      <p>&nbsp;<?= $attendee->hometown ?>&nbsp;</p>
      <p><?= anchor("user/mutual_friends/$attendee->id", 'Mutual Friends', array('class' => 'show_mutual_friends')) ?></p>
      <p>
        <?php if ($attendee->gender != $user->gender): ?>
          <?php if ($attendee->was_smiled_at($user->id, $party->id)): ?>
            <input type="submit" class="smiled_at" disabled="disabled" value="Smiled at <?= $attendee->first_name ?>"></button>
          <?php else: ?>
            <?= form_open('user/smile', array('class' => 'smile_form'), array('party_id' => $party->id, 'receiver_id' => $attendee->id)); ?>
              <input type="submit" value="<?= 'Smile at ' . $attendee->first_name ?>" class="<?= $smiles_left == 0 ? 'cant' : 'can' ?>" />
            <?= form_close(); ?>
          <?php endif ?>
        <?php else: ?>
          &nbsp;
        <?php endif; ?>
      </p>
    </div>

  </li>
  <?php endforeach; ?>

</ul>

<?php if ($party->admin): ?>
  <p id="party_admin">Hosted by: <?= $party->admin->first_name; ?> <?= $party->admin->last_name; ?></p>
<?php endif;?>


<p><?= $user->smiles_received_message($party->id) ?></p>
<p><?= $user->smiles_left_message($party->id) ?></p>

<? foreach ($user->matches($party->id) as $match): ?>
  <p>You and <?= $match->first_name; ?>  have smiled at each other!</p>
<? endforeach; ?>

<ul class="gallery">

  <?php foreach ($party_attendees as $key => $attendee): ?>
  <li>
    
    <?= $attendee->pic; ?>
    
    <div class="caption">
      <p><?= $attendee->first_name; ?> <?= $attendee->last_name ?></p>
      <p><?= $attendee->college->name; ?> <?= $attendee->grad_year; ?></p>
      <p>&nbsp;<?= $attendee->hometown ?>&nbsp;</p>
      <p>Mutual friends: <?= anchor('list_mutual_friends', 8); ?></p>
      <p>
        <?php if ($attendee->was_smiled_at($user->id, $party->id)): ?>
          <button class="smiled_at" disabled="disabled">Smiled at <?= $attendee->first_name ?></button>
        <?php else: ?>
          <?= form_open('user/smile', array('class' => 'smile_form'), array('party_id' => $party->id, 'receiver_id' => $attendee->id)); ?>
            <input class="blue button" type="submit" value="<?= 'Smile at ' . $attendee->first_name ?>" />
          <?= form_close(); ?>
        <?php endif ?>
      </p>
    </div>

  </li>
  <?php endforeach; ?>

</ul>

<?php if ($party->admin): ?>
  <p id="party_admin">Hosted by: <?= $party->admin->first_name; ?> <?= $party->admin->last_name; ?></p>
<?php endif;?>
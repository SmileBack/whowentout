
<p><?= $user->smiles_received_message($party->id) ?></p>
<p><?= $user->smiles_left_message($party->id) ?></p>

<? foreach ($user->matches($party->id) as $match): ?>
  <p>You and <?= $match->full_name; ?>  have smiled at each other!</p>
<? endforeach; ?>

<ul class="gallery">

  <?php foreach ($party_attendees as $key => $attendee): ?>
  <li>
    
    <?= $attendee->pic; ?>
    
    <div class="caption">
      <p><?= $attendee->first_name; ?> <?= $attendee->last_name ?></p>
      <p><?= $attendee->college->name; ?> <?= $attendee->grad_year; ?></p>
      <p>&nbsp;<?= $attendee->hometown ?>&nbsp;</p>
      <p><?= anchor("user/mutual_friends/$attendee->id", 'Mutual Friends', array('class' => 'mutual_friends')) ?></p>
      <p>
        <?php if ($attendee->was_smiled_at($user->id, $party->id)): ?>
          <input type="submit" class="smiled_at" disabled="disabled" value="Smiled at <?= $attendee->first_name ?>"></button>
        <?php else: ?>
          <?= form_open('user/smile', array('class' => 'smile_form'), array('party_id' => $party->id, 'receiver_id' => $attendee->id)); ?>
            <input type="submit" value="<?= 'Smile at ' . $attendee->first_name ?>" class="<?= $smiles_left == 0 ? 'cant' : 'can' ?>" />
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
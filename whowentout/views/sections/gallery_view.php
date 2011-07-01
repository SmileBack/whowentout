
<p><?= $party->smile_info['smiles_received']; ?> girls have smiled at you :)</p>
<p>You have <?= $party->smile_info['smiles_remaining']; ?> smiles left to give</p>

<? foreach ($party->smile_info['matches'] as $match): ?>
  <p>You and <?= $match->first_name; ?>  have smiled at each other!</p>
<? endforeach; ?>


<ul class="gallery">

  <?php foreach ($party_attendees as $key => $attendee): ?>
  <li>

    <?php print img(array(
      'src' => $attendee->profile_pic,
      'width' => $profile_pic_size['width'],
      'height' => $profile_pic_size['height'],
      'alt' => '',
      'class' => '',
    ));?>

    <div class="caption">
      <p><?= $attendee->first_name; ?> <?= $attendee->last_name ?></p>
      <p><?= $attendee->college_name; ?> <?= $attendee->grad_year; ?></p>
      <p>Boca Raton, FL</p>
      <p>Mutual friends: <?= anchor('list_mutual_friends', 8); ?></p>
      <p>
        <?php if ($attendee->was_smiled_at): ?>
          <div class="smiled_at">Smiled at <?= $attendee->first_name ?></div>
        <?php else: ?>
          <?= form_open('user/smile', '', array('party_id' => $party->id, 'receiver_id' => $attendee->id)); ?>
            <input type="submit" value="<?= 'Smile at ' . $attendee->first_name ?>" />
          <?= form_close(); ?>
        <?php endif ?>
      </p>
    </div>

  </li>
  <?php endforeach; ?>

</ul>

<?php if ($party->admin_first_name): ?>
  <p id="party_admin">Hosted by: <?= $party->admin_first_name; ?> <?= $party->admin_last_name; ?></p>
<?php endif;?>
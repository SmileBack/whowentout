<p class="where_did_you_go">Where Did You Go Out Last Night?</p> 

<?php if ($has_attended_party): ?>
  
  You attended <?= anchor("party/$party->id", $party->place->name) ?>. Here are the most recent checkins.
  <ul class="recent_attendees" data-party-id="<?= $party->id ?>">
    <?php foreach ($party->recent_attendees($user->other_gender) as $attendee): ?>
    <li data-user-id="<?= $attendee->id ?>"><?= $attendee->thumb ?></li>
    <?php endforeach; ?>
  </ul>
  
<?php else: ?>
<?= form_open('checkin', array('id' => 'checkin_form')); ?>
  <?= $parties_dropdown; ?>
  <button type="submit">enter</button>
<?= form_close(); ?>
  <?= $closing_time; ?>
<?php endif; ?>

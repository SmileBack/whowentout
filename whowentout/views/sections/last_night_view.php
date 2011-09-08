<h2>Where Did You Go Out Last Night?</h2>

<?php if ($has_attended_party): ?>

You attended <?= anchor("party/$party->id", $party->place->name) ?>. Here are the most recent checkins.
<ul class="recent_attendees serverevents"
    channel-id="<?= 'party_' . $party->id ?>"
    channel-url="<?= serverchannel_url('party', $party->id) ?>"
    data-party-id="<?= $party->id ?>">
    <?php foreach ($party->recent_attendees() as $attendee): ?>
    <li data-user-id="<?= $attendee->id ?>"><?= $attendee->thumb ?></li>
    <?php endforeach; ?>
</ul>

<?php elseif ($parties_dropdown): ?>
    <?= form_open('checkin', array('id' => 'checkin_form'))
    ; ?>
    <?= $parties_dropdown
    ; ?>
<button type="submit">enter</button>
    <?= form_close()
    ; ?>
    <?= $closing_time
    ; ?>
<?php  else: ?>
<h3>There are currently no parties to checkin to.</h3>
<?php endif; ?>

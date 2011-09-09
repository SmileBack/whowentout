<h2>Where Did You Go Out Last Night?</h2>

<?php if ($has_attended_party): ?>

You attended <?= anchor("party/$party->id", $party->place->name) ?>. Here are the most recent checkins.
<ul class="recent_attendees serverevents"
    channel-id="<?= 'party_' . $party->id ?>"
    channel-url="<?= serverchannel_url('party', $party->id) ?>"
    frequency="10"
    data-party-id="<?= $party->id ?>">
    <?php foreach ($party->recent_attendees() as $attendee): ?>
    <li>
        <a href="<?= "/party/$party->id" ?>">
            <?= $attendee->thumb ?>
        </a>
    </li>
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
    <?= form_open('checkin', array('id' => 'checkin_form')) ?>
        <select style="width: 80px;">
            <option></option>
        </select>
    <?= form_close() ?>
    <h3 style="display: inline-block; ">There are currently no parties to checkin to.</h3>
<?php endif; ?>

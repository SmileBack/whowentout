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

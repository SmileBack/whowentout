<ul class="recent_attendees party"
    data-party-id="<?= $party->id ?>">
    <?php foreach ($party->recent_attendees() as $attendee): ?>
    <li>
        <a href="<?= "/party/$party->id" ?>">
            <?= $attendee->thumb ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>

<?php if (!isset($count)) $count = 5; ?>
<ul class="recent_attendees party"
    data-party-id="<?= $party->id ?>"
    data-thumbnail-capacity="<?= $count ?>">
    <?php foreach ($party->recent_attendees($count) as $attendee): ?>
    <li>
        <a href="<?= "/party/$party->id" ?>">
            <?= $attendee->thumb ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>

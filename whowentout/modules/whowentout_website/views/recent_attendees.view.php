<?php if (!isset($count)) $count = 5; ?>

<?php if (isset($party)): ?>
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

<?php else: ?>
        
    <ul class="recent_attendees"
        data-thumbnail-capacity="<?= $count ?>">

        <?php foreach (range(1, $count) as $i): ?>
            <li>
                <a>
                    <img src="/assets/images/empty_picture.png" />
                </a>
            </li>
        <?php endforeach; ?>

    </ul>

<?php endif; ?>
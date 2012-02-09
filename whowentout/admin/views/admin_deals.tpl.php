<ul>
    <?php foreach ($checkins as $checkin): ?>
        <?php if (!$checkin->event->deal) continue; ?>
        <li>
            <dl>
                <dt>Name</dt>
                <dd><?= $checkin->user->first_name . ' ' . $checkin->user->last_name ?></dd>

                <dt>Event</dt>
                <dd><?= $checkin->event->deal ?></dd>

                <dt>Phone</dt>
                <dd><?= $checkin->user->cell_phone_number ? $checkin->user->cell_phone_number : '?' ?></dd>

                <dt>Deal</dt>
                <dd><?= r::deal_image(array('event' => $checkin->event, 'user' => $checkin->user)); ?></dd>
            </dl>
        </li>
    <?php endforeach; ?>
</ul>

<?php $events = db()->table('events')->where('date', $date); ?>
<?php $n = 0; ?>

<form method="post" action="/checkins/create" class="event_list">
    <fieldset>
        <legend>Check-in to claim your deal and see who else is going!</legend>
        <ul>
            <?php foreach ($events as $k => $event): ?>
            <li class="<?= $n++ == 0 ? 'first' : '' ?>">
                <?=
                r::event_option(array(
                                         'event' => $event,
                                         'selected' => $selected_event == $event,
                                ))
                ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</form>
    
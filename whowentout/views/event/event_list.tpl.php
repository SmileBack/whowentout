<?php $events = db()->table('events')->where('date', $date); ?>
<?php $n = 0; ?>

<fieldset class="event_list">
    <legend>Check-in to claim your deal and see who else is going!</legend>
    <ul>
    <?php foreach ($events as $k => $event): ?>
        <li class="<?= $n++ == 0 ? 'first' : '' ?>">
            <?= r::event_option(array(
                                    'name' => $event->name,
                                    'deal' => $event->deal,
                                )) ?>
        </li>
    <?php endforeach; ?>
    </ul>
</fieldset>

<?php $events = db()->table('events')->where('date', $date); ?>
<?php $n = 0; ?>

<div class="event_list_wrapper">
    <form method="post" action="/checkins/create" class="event_list">
        <h1>Check-in. See where everyone's going. Claim your deal.</h1>
        <fieldset>
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
</div>
    
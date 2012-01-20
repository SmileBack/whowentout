<?php $events = db()->table('events')->where('date', $date); ?>
<?php $n = 0; ?>

<div class="event_list_wrapper">
    <form method="post" action="/checkin" class="event_list">
        <h1>
            <span>Check-in.</span>
            <span>See where everyone's going.</span>
            <span>Claim your deal.</span>
        </h1>
        <fieldset>
            <ul>
                <?php foreach ($events as $k => $event): ?>
                <?php $selected = ($selected_event == $event); ?>
                <li class="<?= $selected ? 'selected' : '' ?>">
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

    <?php if ($selected_event): ?>
        <?php $show_deal_link = "events/$selected_event->id/deal"; ?>
        <?= a($show_deal_link, "View Your Deal", array('class' => 'action show_deal_link')) ?>
    <?php endif; ?>

</div>

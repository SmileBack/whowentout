<?php $events = db()->table('events')->where('date', $date); ?>
<?php $n = 0; ?>

<div class="event_list_wrapper">
    <form method="post" action="/checkins/create" class="event_list">
        <h1>Check-in. See where everyone's going. Claim your deal.</h1>
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
        <?php $show_deal_link = app()->event_link($selected_event) . "/deal/$selected_event->id?show=true" ?>
        <?= a($show_deal_link, "View Your Deal", array('class' => 'show_deal_link')) ?>
    <?php endif; ?>

</div>

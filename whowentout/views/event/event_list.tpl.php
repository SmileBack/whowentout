<?php $events = db()->table('events')->where('date', $date); ?>
<?php $n = 0; ?>

<div class="event_list_wrapper">

    <?php if (browser::is_mobile()): ?>
    <?= r::show_deal_link(array('event' => $selected_event, 'class' => 'mobile', 'title' => 'view your deal')); ?>
    <?php endif; ?>

    <form method="post" action="/checkin" class="event_list <?= $selected_event ? 'event_selected' : '' ?>">
        <input type="hidden" name="date" value="<?= $date->format('Y-m-d') ?>" />
        <h1>
            <span>Check-in.</span>
            <span>See where everyone's going.</span>
            <span>Claim your deal.</span>
        </h1>

        <fieldset>
            <ul>

                <li>
                    <?= r::event_add_form(array('date' => $date)); ?>
                </li>

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

</div>

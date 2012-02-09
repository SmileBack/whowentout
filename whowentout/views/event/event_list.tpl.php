<div class="event_list_wrapper">

    <?php if (browser::is_mobile()): ?>
    <?= r::show_deal_link(array('event' => $selected_event, 'class' => 'mobile', 'title' => 'claim your deal'))
    ; ?>
    <?php endif; ?>

    <form method="post" action="/checkin" class="event_list <?= $selected_event ? 'event_selected' : '' ?>">
        <input type="hidden" name="date" value="<?= $date->format('Y-m-d') ?>"/>

        <h1>
            <?php if (browser::is_desktop()): ?>
            Where are YOU going out?
            <?php else: ?>
            Where are you going out?
            <?php endif; ?>
            <div>
                <span>Check-in.</span>
                <span>See where everyone's going.</span>
                <span>Claim your deal.</span>
            </div>
        </h1>

        <fieldset>
            <ul class="events">

                <li>
                    <?= r::event_add_form(array('date' => $date)); ?>
                </li>

                <?php foreach ($events as $k => $event): ?>
                <?php $selected = ($selected_event == $event); ?>
                <li class="<?= $selected ? 'selected' : '' ?>">
                    <?php benchmark::start('event_option'); ?>
                    <?=
                    r::event_option(array(
                        'event' => $event,
                        'is_selected' => $selected_event == $event,
                        'selected_event' => $selected_event,
                    ))
                    ?>
                    <?php benchmark::end('event_option'); ?>
                </li>
                <?php endforeach; ?>

            </ul>
        </fieldset>

    </form>

</div>

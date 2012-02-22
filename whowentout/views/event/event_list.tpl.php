<div class="event_list_wrapper">

    <div class="event_list <?= $selected_event ? 'event_selected collapsed' : '' ?>">
        <h1>
            <div>Where are YOU doing tonight?</div>
            <?php if (!$selected_event): ?>
            <div>
                <span>Check-in.</span>
                <span>See where everyone's going.</span>
                <span>Claim your deal.</span>
            </div>
            <?php endif; ?>
        </h1>

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

        <div class="expander">
            <?php if ($selected_event): ?>
                <span>&darr;</span>
                <a href="#switch" class="switch expander">switch party</a>
                <span>&darr;</span>
            <?php endif; ?>
        </div>

    </div>

</div>
<div class="event_list <?= $selected_event ? 'event_selected collapsed' : '' ?>">
    <ul class="events">

        <li>
            <?=
            r::event_add_form(array(
                    'date' => $date,
                    'base_type' => $type,
                ));
            ?>
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

</div>

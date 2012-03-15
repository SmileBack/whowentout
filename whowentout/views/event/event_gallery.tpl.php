<ul class="gallery">
    <?php $n = 1; ?>

    <?php foreach ($checkins as $checkin): ?>
    <li>

        <div class="going_to">
            <?php if ($selected_event): ?>
                <?= $checkin->event ? $checkin->event->name : 'Undeclared' ?>
            <?php else: ?>
                Going to ?
            <?php endif; ?>
        </div>

        <?=
        r::profile_small(array(
            'user' => $checkin->user,
            'link_to_profile' => true,
            'show_networks' => true,
            'hidden' => false,
            'badge' => $checkin->connection,
            'class' => ($checkin->event ? "checkin_event_{$checkin->event->id}" : '')
                     . " checkin_user_{$checkin->user->id}",
            'defer_load' => $n++ > 8,
        ))
        ?>

        <?php if ($selected_event && $selected_event->place->type != 'undecided base'): ?>
        <?= r::invite_to_form(array('user' => $checkin->user, 'event' => $selected_event)) ?>
        <?php endif; ?>

    </li>
    <?php endforeach; ?>

</ul>

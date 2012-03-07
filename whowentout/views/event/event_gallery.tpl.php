<ul class="gallery">
    <?php $n = 1; ?>
    <?php foreach ($checkins as $checkin): ?>

    <li>
        <div class="going_to">
            <div><?= $checkin->event ? $checkin->event->name : 'Not Sure Yet' ?></div>
        </div>

        <?=
        r::profile_small(array(
            'user' => $checkin->user,
            'link_to_profile' => true,
            'show_networks' => true,
            'hidden' => false,
            'is_friend' => $checkin->is_friend,
            'class' => ($checkin->event ? "checkin_event_{$checkin->event->id}" : '')
                     . " checkin_user_{$checkin->user->id}",
            'defer_load' => $n++ > 8,
        ))
        ?>

        <?php if ($selected_event->place->type != 'undecided base'): ?>
        <?= r::invite_to_form(array('user' => $checkin->user, 'event' => $selected_event)) ?>
        <?php endif; ?>

    </li>
    <?php endforeach; ?>

</ul>

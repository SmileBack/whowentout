<ul class="event_gallery">
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
            'class' => $checkin->event ? "checkin_event_{$checkin->event->id}" : '',
            'show_pic' => false,
        ))
        ?>

    </li>
    <?php endforeach; ?>

</ul>
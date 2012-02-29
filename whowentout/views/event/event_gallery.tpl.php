<ul class="event_gallery">
    <?php foreach ($checkins as $checkin): ?>

    <li>
        <div class="going_to">
            <div><?= $checkin->event->name ?></div>
        </div>

        <?=
        r::profile_small(array(
            'user' => $checkin->user,
            'link_to_profile' => true,
            'show_networks' => true,
            'hidden' => false,
            'is_friend' => isset($friends[$checkin->user->id]),
            'class' => "checkin_event_{$checkin->event->id}",
        ))
        ?>

    </li>
    <?php endforeach; ?>

</ul>
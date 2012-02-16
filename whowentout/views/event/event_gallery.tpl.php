<?php
/**
 * @var $date DateTime
 * @var $user DatabaseRow
 * @var $checkin DatabaseRow|null the checkin the user has for the date (if any)
 * @var $checkins DatabaseRow[]
 */
?>
<div class="event_gallery">

    <?php if (!$checkin): ?>
    <img class="event_gallery_message" src="/images/event_gallery_message.png" align="checkin to see who's going out" />
    <?php endif; ?>

    <?php if (false): ?>
    <h1>Where your friends are going out:</h1>
    <ul>
        <?php foreach ($friend_checkins as $checkin): ?>
        <?php if (!isset($friends[$checkin->user->id])) continue; //skip over non-friends ?>

        <li>
            <?php benchmark::start('profile_small'); ?>
            <?=
            r::profile_small(array(
                'user' => $checkin->user,
                'link_to_profile' => true,
                'show_networks' => true,
                'hidden' => false,
                'is_friend' => isset($friends[$checkin->user->id]),
            ))
            ?>
            <?php benchmark::end('profile_small'); ?>

            <?php benchmark::start('going_to'); ?>
            <div class="going_to">
                <div>Going to:</div>
                <div><?= $checkin->event->name ?></div>
            </div>
            <?php benchmark::end('going_to'); ?>

        </li>
        <?php endforeach; ?>

    </ul>
    <?php endif; ?>

    <h1>Where is EVERYONE going out?</h1>

    <?php if ($checkin): ?>
    <?= r::event_links(array('date' => $date)) ?>
    <?php endif; ?>

    <ul class="everyone_gallery">

        <?php if ($hidden): ?>
        <?php for ($n = 0; $n < 4 * 3; $n++): ?>
            <li>
                <?= r::profile_anonymous() ?>
            </li>
        <?php endfor; ?>

        <?php else: ?>
        <?php foreach ($checkins as $checkin): ?>
            <li>
                <?php benchmark::start('profile_small'); ?>
                <?=
                r::profile_small(array(
                    'user' => $checkin->user,
                    'link_to_profile' => true,
                    'show_networks' => true,
                    'hidden' => false,
                    'is_friend' => isset($friends[$checkin->user->id]),
                    'class' => "checkin_event_" . $checkin->event->id,
                ))
                ?>
                <?php benchmark::end('profile_small'); ?>

                <?php benchmark::start('going_to'); ?>
                <div class="going_to">
                    <div>Going to:</div>
                    <div><?= $checkin->event->name ?></div>
                </div>
                <?php benchmark::end('going_to'); ?>

            </li>
            <?php endforeach; ?>
        <?php endif; ?>

    </ul>
</div>

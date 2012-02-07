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
    <img class="event_gallery_message" src="/images/event_gallery_message.png" align="checkin to see who's going out"/>
    <?php endif; ?>

    <ul>

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
                ))
                ?>
                <?php benchmark::end('profile_small'); ?>

                <div class="going_to">
                    <div>Going to:</div>
                    <div><?= $checkin->event->name ?></div>
                </div>

            </li>
            <?php endforeach; ?>
        <?php endif; ?>

    </ul>
</div>

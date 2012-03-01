<?php
/**
 * @var $date DateTime
 * @var $user DatabaseRow
 */
?>
<div class="event_gallery tab_panel">

    <ul class="tabs">
        <li><a href="#everyone" class="selected">Everyone</a></li>
        <li><a href="#friends">Friends</a></li>
    </ul>


    <div class="friends pane">
        <?php if ($checkin): ?>
        <h1>What are YOUR FRIENDS doing <?= format::night_of($date) ?>?</h1>
            <div class="event_gallery load" data-url="<?= '/day/' . $date->format('Ymd') . '/gallery/friends' ?>"></div>
        <?php endif; ?>
    </div>

    <div class="everyone pane">
        <h1>What is EVERYONE doing <?= format::night_of($date) ?>?</h1>

        <?php if (!$checkin): ?>
            <img class="event_gallery_message" src="/images/event_gallery_message.png" align="checkin to see who's going out" />
            <ul class="event_gallery">
            <?php for ($n = 0; $n < 4 * 3; $n++): ?>
                <li>
                    <?= r::profile_anonymous() ?>
                </li>
            <?php endfor; ?>
            </ul>
        <?php endif; ?>

        <?php if ($checkin): ?>
            <?= r::event_links(array('date' => $date)) ?>
            <div class="event_gallery load" data-url="<?= '/day/' . $date->format('Ymd') . '/gallery/everyone' ?>"></div>
        <?php endif; ?>

    </div>

</div>

<?php
/**
 * @var $date DateTime
 * @var $user DatabaseRow
 */
?>
<div class="event_day_summary">

    <h1>What is EVERYONE doing <?= format::night_of($date) ?>?</h1>

    <?php if (!$checkin): ?>
        <img class="event_gallery_message" src="/images/event_gallery_message.png" align="checkin to see who's going out" />
    <?php endif; ?>

    <?= r::event_gallery_toolbar(array('date' => $date)) ?>
    <div class="load" data-url="<?= '/day/' . $date->format('Ymd') . '/gallery/everyone' ?>"></div>

</div>

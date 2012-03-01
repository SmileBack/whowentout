<?php $have_number = $user->cell_phone_number != null; ?>
<form class="deal_popup" method="post" action="/deal/confirm">

    <input type="hidden" name="event_id" value="<?= $event->id ?>"/>

    <h1>Your deal has been emailed to <?= $user->email ?>.</h1>

    <?php if (browser::is_mobile()): ?>
        <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'portrait')) ?>
        <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'landscape')) ?>
    <?php else: ?>
        <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'landscape')) ?>
    <?php endif; ?>

    <?php if (browser::is_desktop()): ?>
        <h3>You can also get to your deal from your phone's web browser.</h3>
    <?php endif; ?>

    <div class="buttons">
        <input type="submit" value="Continue"/>
    </div>

</form>

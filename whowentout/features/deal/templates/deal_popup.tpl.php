<?php $have_number = $user->cell_phone_number != null; ?>
<form class="deal_popup" method="post" action="/deal/confirm">

    <input type="hidden" name="event_id" value="<?= $event->id ?>"/>

    <h1>1. You can view the deal on your smartphone:</h1>

    <?php if (browser::is_desktop()): ?>
        <img src="/images/claim_deal_screenshot.png" class="claim_deal_screenshot" alt="claim deal"/>
    <?php endif; ?>

    <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'portrait')) ?>
    <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'landscape')) ?>

    <?php if (browser::is_desktop()): ?>
    <h1 class="or">
        <span class="text">OR</span>
        <div class="line"></div>
    </h1>
    <h1>2. We can text it to your phone on <?= $event->date->format('l') ?> evening:</h1>
    <div class="phone_number_field <?= $have_number ? 'have_number' : 'missing_number' ?>">
        <?php if (!$have_number): ?>
            <input type="text" class="cell_phone_number" name="user[cell_phone_number]"
                   value="<?= $user->cell_phone_number ?>" autocomplete="off" />
        <?php else: ?>
            <input type="text" class="cell_phone_number inline" name="user[cell_phone_number]"
                   value="<?= $user->cell_phone_number ?>" autocomplete="off" />
            <a href="#edit" class="edit_cell_phone_number">change</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="buttons">
        <input type="submit" value="Continue"/>
    </div>

</form>

<?php $have_number = $user->cell_phone_number != null; ?>
<form class="deal_popup" method="post" action="/deal/confirm">

    <input type="hidden" name="event_id" value="<?= $event->id ?>"/>

    <?php if (!browser::is_mobile()): ?>
        <div class="phone_number_field <?= $have_number ? 'have_number' : 'missing_number' ?>">
            <?php if (!$have_number): ?>

                <h2>
                    Type your number below so we can send you the deal.
                </h2>

                <input type="text" class="cell_phone_number" name="user[cell_phone_number]"
                       value="<?= $user->cell_phone_number ?>" autocomplete="off" />

            <?php else: ?>

                <h2>The deal will be sent to</h2>

                <input type="text" class="cell_phone_number inline" name="user[cell_phone_number]"
                       value="<?= $user->cell_phone_number ?>" autocomplete="off" />

                <a href="#edit" class="edit_cell_phone_number">change</a>

            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'portrait')) ?>
    <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'landscape')) ?>

    <?php if (!browser::is_mobile()): ?>
        <p>(You can also access this deal by going to whowentout.com on your phone.)</p>
    <?php endif; ?>

    <input type="submit" value="Continue"/>

</form>

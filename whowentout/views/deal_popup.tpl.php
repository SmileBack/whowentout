<form class="deal_popup" method="post" action="/deal/confirm">

    <input type="hidden" name="event_id" value="<?= $event->id ?>"/>

    <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'portrait')) ?>
    <?= r::deal_preview(array('user' => $user, 'event' => $event, 'orientation' => 'landscape')) ?>

    <?php if (!browser::is_mobile()): ?>

        <?php if ($user->cell_phone_number == null): ?>

        <label>
            Type your number below so we can send you the
            deal on <?= $event->date->format('l') ?> night.</h1>
        </label>

        <div class="phone_number_field">
            <input type="text" class="cell_phone_number" name="user[cell_phone_number]"
                   value="<?= $user->cell_phone_number ?>"/>
        </div>

        <?php else: ?>

            <label>The deal will be sent to</label>

            <div class="phone_number_field">
                <input type="text" class="cell_phone_number inline" name="user[cell_phone_number]"
                       value="<?= $user->cell_phone_number ?>"/>
                <a href="#edit" class="edit_cell_phone_number">change</a>
            </div>

        <?php endif; ?>

    <p>(You can also access this deal by going to whowentout.com on your phone.)</p>

    <?php endif; ?>

    <input type="submit" value="Continue"/>

</form>

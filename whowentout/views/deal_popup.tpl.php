<form class="deal_popup" method="post" action="/events/deal_confirm">
    <h1>The deal will be sent to your phone on <?= $event->date->format('l') ?>!</h1>
    <h4><span style="font-size: 20px;">&darr;</span> Show this to the bartender <span
            style="font-size: 20px;">&darr;</span></h4>
    <input type="hidden" name="event_id" value="<?= $event->id ?>"/>

    <?= r::deal_preview(array('user' => $user, 'event' => $event)) ?>

    <div class="phone_number_field">
        <?php if ($user->cell_phone_number == null): ?>
        <label>
            Enter your number so we can send you the deal:
        </label>

        <input type="text" class="cell_phone_number" name="user[cell_phone_number]"
               value="<?= $user->cell_phone_number ?>"/>
        <?php else: ?>
        <h3>
            The deal will be sent to
            <input type="text" class="cell_phone_number inline" name="user[cell_phone_number]"
                   value="<?= $user->cell_phone_number ?>"/>
            <a href="#edit" class="edit_cell_phone_number">change</a>
        </h3>
        <?php endif; ?>
    </div>

    <input type="submit" value="Continue"/>

</form>
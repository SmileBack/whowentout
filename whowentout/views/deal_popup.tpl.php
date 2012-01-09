<form class="deal_popup" method="post" action="/events/deal_confirm">
    <input type="hidden" name="event_id" value="<?= $event_id ?>" />

    <img alt="sample deal" src="/images/deal_popup.png"/>

    <div>
        <?php if ($user->cell_phone_number == null): ?>
        <label>
            Enter your cell phone number so we can send you deal
        </label>

        <input type="text" class="cell_phone_number" name="user[cell_phone_number]" value="<?= $user->cell_phone_number ?>"/>
        <?php else: ?>
            <h3>
            The deal will be sent to
                <input type="text" class="cell_phone_number inline" name="user[cell_phone_number]" value="<?= $user->cell_phone_number ?>" />
                <a href="#edit" class="edit_cell_phone_number">change</a>
            </h3>
        <?php endif; ?>
    </div>

    <input type="submit" value="OK" />

</form>

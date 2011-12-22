<form class="deal_popup" method="post" action="/events/deal_confirm">

    <h2>The deal will be sent to your phone</h2>

    <img alt="sample deal" src="/images/deal_popup.png"/>

    <div>
        <label>
            Give us your cell phone number so we can send the deal
        </label>
        <input type="text" name="user[cell_phone_number]" value="<?= $user->cell_phone_number ?>"/>
    </div>

    <input type="submit" value="OK" />

</form>
<div class="undecided pane">
    <h2>Not sure what you're doing yet? No worries!</h2>
    <h2>See what others are doing and decide later.</h2>

    <form method="post" class="checkin_form" action="/checkin/undecided">
        <?= form::hidden('date', $date->format('Y-m-d')) ?>
        <input type="submit" class="checkin_badge" value="join" />
    </form>

</div>
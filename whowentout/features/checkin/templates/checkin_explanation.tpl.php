<form method="post" action="/checkin/explanation/confirm">

    <h2>You have checked into <?= $event->name ?>.</h2>

    <?php if ($event->deal): ?>
    <h2>You can now see where everyone's going out and claim your deal.</h2>
    <?php endif; ?>

    <?php if (!$event->deal): ?>
    <h2>You can now see where everyone's going out.</h2>
    <?php endif; ?>

    <input type="hidden" name="event_id" value="<?= $event->id ?>" />

    <div class="buttons" style="margin-top: 10px;">
        <input type="submit" value="Continue" />
    </div>

</form>

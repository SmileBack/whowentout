<form method="post" action="/checkin" class="event_option new_event all other">

    <?= form::hidden('event_id', 'new') ?>

    <div class="place">
        <?= form::hidden('event[date]', $date->format('Y-m-d')) ?>

        <?=
        form::input('event[name]', '', array(
            'class' => 'inline_label',
            'autocomplete' => 'off',
            'title' => 'Doing something else?',
        ))
        ?>

    </div>

    <div class="badge">
        <input type="submit" name="op" class="add_event" value="add" />
    </div>

</form>

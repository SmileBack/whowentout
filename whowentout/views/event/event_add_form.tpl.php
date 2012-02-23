<form method="post" action="/checkin" class="event_option new_event all other">

    <input type="hidden" name="event_id" value="new" />

    <div class="place">
        <input type="hidden" name="event[date]" value="<?= $date->format('Y-m-d') ?>" />
        <input type="text" class="inline_label" name="event[name]" value="" autocomplete="off"
               title="Doing something else?" />
    </div>

    <div class="badge">
        <input type="submit" name="op" class="add_event" value="add" />
    </div>

</form>

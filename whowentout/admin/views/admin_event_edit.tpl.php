<?php $places = db()->table('places')->all(); ?>

<form action="/admin/events/update" method="post">
    <fieldset>
        <legend>Create Event</legend>

        <input type="hidden" name="event[id]" value="<?= $event->id ?>" />

        <div>
            <label>Event name</label>
            <input type="text" name="event[name]" autocomplete="off" value="<?= $event->name ?>" />
        </div>

        <div>
            <label>Event date</label>
            <input type="text" name="event[date]" autocomplete="off"
                   value="<?= $event->date ? $event->date->format('Y-m-d') : '' ?>" />
        </div>
        
        <div>
            <label>Place</label>
            <select name="event[place_id]">
                <?php foreach ($places as $place): ?>
                    <?= html_element('option', array('value' => $place->id), $place->name) ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Deal</label>
            <textarea rows="4" cols="100" name="event[deal]"><?= $event->deal ?></textarea>
        </div>

        <div>
            <label>Deal Ticket</label>
            <textarea rows="4" cols="100" name="event[deal_ticket]"><?= $event->deal_ticket ?></textarea>
        </div>

        <div>
            <label>Deal Type</label>
            <select name="event[deal_type]">
                <option value="bar">bar</option>
                <option value="door">door</option>
            </select>
        </div>

        <div>
            <label>Priority</label>
            <input type="text" name="event[priority]" value="<?= $event->priority ?>" />
        </div>
        
        <input type="submit" name="op" value="Save"/>
    </fieldset>
</form>

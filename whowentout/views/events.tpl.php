<form action="/admin_events/create" method="post">
    <fieldset>
        <legend>Create Event</legend>

        <div>
            <label>Event name</label>
            <input type="text" name="event[name]" autocomplete="off" />
        </div>

        <div>
            <label>Event date</label>
            <input type="text" name="event[date]" autocomplete="off"/>
        </div>
        
        <div>
            <label>Place</label>
            <select name="event[place_id]">
                <?php foreach ($places as $place_id => $place): ?>
                    <?= html_element('option', array('value' => $place_id), $place->name) ?>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="submit" name="op" value="Create"/>
    </fieldset>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date</th>
        <th>Place</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($events as $event): ?>
    <tr>
        <td><?= $event->id ?></td>
        <td><?= $event->name ?></td>
        <td><?= $event->date->format('D. M j, Y') ?></td>
        <td><?= $event->place->name ?></td>
        <td>
            <?= a("admin_events/destroy/$event->id", 'destroy') ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

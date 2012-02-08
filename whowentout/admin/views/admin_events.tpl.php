<form action="/admin/events/create" method="post">
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

        <div>
            <label>Deal</label>
            <textarea rows="4" cols="100" name="event[deal]"></textarea>
        </div>

        <div>
            <label>Deal Type</label>
            <select name="event[deal_type]">
                <option value="bar">bar</option>
                <option value="door">door</option>
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
        <th>Deal</th>
        <th>Deal Type</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($events as $event): ?>
    <tr>
        <td><?= $event->id ?></td>
        <td><?= $event->name ?></td>
        <td><?= $event->date->format('D. M j, Y') ?></td>
        <td><?= $event->place->name ?></td>
        <td><?= $event->deal ?></td>
        <td><?= $event->deal_type ?></td>
        <td>
            <?= a("admin/events/$event->id/edit", 'edit') ?>
            <?= a("admin/events/$event->id/destroy", 'destroy') ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

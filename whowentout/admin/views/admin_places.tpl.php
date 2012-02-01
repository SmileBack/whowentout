<form action="/admin/places/create" method="post">
    <fieldset>
        <legend>Create Place</legend>

        <div>
            <label>Place name</label>
            <input type="text" name="place[name]" />
        </div>

        <div>
            <label>Place type</label>
            <input type="text" name="place[type]" />
        </div>

        <div>
            <input type="submit" name="op" value="Create"/>
        </div>
    </fieldset>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($places as $place): ?>
    <tr>
        <td><?= $place->id ?></td>
        <td><?= $place->name ?></td>
        <td><?= $place->type ?></td>
        <td>
            <?= a("admin/places/$place->id/destroy", 'destroy') ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
    
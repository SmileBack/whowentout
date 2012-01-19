<form action="/admin/places/create" method="post">
    <fieldset>
        <legend>Create Place</legend>

        <label>Place name</label>
        <input type="text" name="place[name]"/>
        <input type="submit" name="op" value="Create"/>
    </fieldset>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($places as $place): ?>
    <tr>
        <td><?= $place->id ?></td>
        <td><?= $place->name ?></td>
        <td>
            <?= a("admin/places/destroy/$place->id", 'destroy') ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
    
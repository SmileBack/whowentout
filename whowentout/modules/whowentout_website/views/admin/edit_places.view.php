<section>
  <h1>Places</h1>
  <div class="section_content">
    <?= form_open('admin/add_place') ?>
      <label>Place</label>
      <input name="place_name" />
      <input type="submit" value="Add" />
    <?= form_close() ?>

    <table>
      <thead>
        <th>ID</th>
        <th>Place</th>
        <th>Parties</th>
        <th>Delete</th>
      </thead>
      <tbody>
        <?php foreach (college()->places as $place): ?>
        <tr>
          <td><?= $place->id ?></td>
          <td><?= $place->name ?></td>
          <td><?= $place->num_parties ?></td>
          <td><?=
            anchor("admin/delete_place/$place->id",
                   'Delete',
                   array(
                     'class' => 'confirm',
                     'action' => "delete the place $place->name",
                   )) 
              ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
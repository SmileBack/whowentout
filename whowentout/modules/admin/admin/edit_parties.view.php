<section>
  <h1>Parties</h1>
  <div class="section_content">
    <?= form_open('admin/add_party') ?>

      <label>Date</label>
      <input name="date" />

      <label>Place</label>
      <?= places_dropdown( college()->places ); ?>

      <input type="submit" value="Add" />

    <?= form_close() ?>

    <table>
      <thead>
        <th>ID</th>
        <th>Place</th>
        <th>Date</th>
        <th>Females</th>
        <th>Males</th>
        <th>Checkin Random</th>
        <th>Delete</th>
      </thead>
      <tbody>
        <?php foreach (college()->parties(100, 'desc') as $party): ?>
        <tr>
          <td><?= $party->id ?></td>
          <td><?= $party->place->name ?></td>
          <td><?= $party->date ?></td>
          <td><?= $party->female_count ?> girls</td>
          <td><?= $party->male_count ?> guys</td>
          <td><?= anchor("admin/random_checkin/$party->id", 'Random Checkin', array('class' => 'confirm')) ?></td>
          <td><?=
            anchor("admin/delete_party/$party->id",
                   'Delete',
                   array(
                     'class' => 'confirm',
                     'action' => "delete the party at {$party->place->name} on $party->date",
                   ))
               ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
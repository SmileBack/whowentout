
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
  </thead>
  <tbody>
    <?php foreach (college()->parties as $party): ?>
    <tr>
      <td><?= $party->id ?></td>
      <td><?= $party->place->name ?></td>
      <td><?= $party->date ?></td>
      <td><?= $party->female_count ?> girls</td>
      <td><?= $party->male_count ?> guys</td>
      <td><?= anchor("admin/random_checkin/$party->id", 'Random Checkin') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

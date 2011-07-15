<h1>Users view</h1>

<table>
  
  <thead>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Destroy</th>
    </tr>
  </thead>
  
  <tbody>
    <?php foreach (college()->students as $student): ?>
    <tr>
      <td><?= $student->id ?></td>
      <td><?= $student->full_name ?></td>
      <td><?= anchor("admin/destroy_user/$student->id", 'Destroy') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  
</table>

<section>
  <h1>Users view</h1>
  <div class="section_content">
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
          <td>
            <?= 
              anchor("admin/destroy_user/$student->id",
                     'Destroy',
                     array(
                       'class' => 'confirm',
                       'action' => "destroy $student->full_name",
                     ))
             ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
</section>
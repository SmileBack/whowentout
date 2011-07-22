<h3><?= anchor('test', 'Back to Tests') ?></h3>
<?php foreach ($report as $group_name => $group): ?>
  <h1><?= $group_name ?></h1>
  <h3><?= $group['passed'] ?>/<?= $group['total'] ?> passed</h3>
  <table class="test_report">
    <thead>
      <tr>
        <th>Passed</th>
        <th>Test</th>
        <th>Expected</th>
        <th>Actual</th>
        <th>Line</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($group['tests'] as $test): ?>
      <tr>
        <td><?= $test['passed'] ? 'Y' : 'N' ?></td>
        <td><?= $test['name'] ?></td>
        <td><?= isset($test['expected']) ? $test['expected'] : '' ?></td>
        <td><?= isset($test['actual']) ? $test['actual'] : '' ?></td>
        <td><?= isset($test['line']) ? $test['line'] : '' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>

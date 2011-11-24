<h3><?= a('test', 'Back to Tests') ?></h3>
<?php foreach ($report as $group_name => $group): ?>
  <h1><?= $group_name ?></h1>
  <h3><?= $group['passed'] ?>/<?= $group['total'] ?> passed</h3>
  <table class="test_report">
    <thead>
      <tr>
        <th>Passed</th>
        <th>Assertions</th>
        <th>Test</th>
        <th>Expected</th>
        <th>Actual</th>
        <th>Line</th>
        <th>Message</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($group['tests'] as $test): ?>
      <tr>
        <td><?= $test['passed'] ? 'Y' : 'N' ?></td>
        <td><?= $test['assertion_pass_count'] ?>/<?= $test['assertion_count'] ?></td>
        <td><?= $test['name'] ?></td>
        <td><?= isset($test['expected']) ? krumo::dump($test['expected']) : '' ?></td>
        <td><?= isset($test['actual']) ? krumo::dump($test['actual']) : '' ?></td>
        <td><?= isset($test['line']) ? $test['line'] : '' ?></td>
        <td><?= isset($test['message']) ? $test['message'] : '' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>
<h4>Ran tests at <?= date('Y-m-d H:i:s') ?></h4>
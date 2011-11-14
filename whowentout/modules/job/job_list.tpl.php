<section class="job_list">
    <h1>Pending Jobs (<?= count($jobs) ?>)</h1>
    <div class="section_body">
        <table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created</th>
                <th>Parameters</th>
                <th>Run</th>
            </tr>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?= substr($job->id, 0, 4) ?></td>
                    <td><?= $job->type ?></td>
                    <td><?= $job->status ?></td>
                    <td><?= date('Y-m-d H:i:s', $job->created) ?></td>
                    <td><?php krumo::dump(unserialize($job->args)) ?></td>
                    <td><?= anchor("job/admin_run/$job->id", 'run') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>
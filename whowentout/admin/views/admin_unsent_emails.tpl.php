<?php
$jobs = db()->table('jobs')
        ->where('type', 'SendEmailJob')
        ->where('status', array('pending', 'running'));
$job_count = $jobs->count();
?>
<style type="text/css">
    .admin_unsent_emails {
        margin: 25px;
    }

    .admin_unsent_emails dd {
        margin-left: 15px;
    }

    .admin_unsent_emails li {
        padding: 12px;
        border: 1px solid grey;
    }
</style>
<div class="admin_unsent_emails">
    <h1>Unsent Email Jobs (<?= $job_count ?>)</h1>
    <table class="filter_table">
        <thead>
            <tr>
                <th>id</th>
                <th>status</th>
                <th>user</th>
                <th>facebook id</th>
                <th>networks</th>
                <th>email</th>
                <th>subject</th>
                <th>run job</th>
                <th>delete job</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $job): ?>
        <?php
        $hash = substr($job->id, 0, 5);
        $opts = (object)unserialize($job->options);

        $user = isset($opts->user_id) ? db()->table('users')->row($opts->user_id) : null;

        $subject = $opts->subject;
        $body = $opts->body;
        $email = isset($opts->email) ? $opts->email : null;
        ?>

            <tr>
                <td><?= $hash ?></td>
                <td><?= $job->status ?></td>

                <td>
                    <?= $user ? "$user->first_name $user->last_name" : '-' ?>
                </td>

                <td>
                    <?= $user ? $user->facebook_id : '-' ?>
                </td>

                <td>
                    <?php if ($user): ?>
                    <?php foreach ($user->networks->where('type', 'college') as $network): ?>
                        <span><?= $network->name ?></span>
                    <?php endforeach; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($user): ?>
                    <form method="post" action="/admin/emails/update">
                        <input type="hidden" name="user[id]" value="<?= $user->id ?>" />
                        <input type="text" name="user[email]" value="<?= $user->email ?>" />

                        <input type="submit" name="op" value="update" />
                        <input type="submit" name="op" value="lookup" />
                    </form>
                    <?php elseif (isset($opts->email)): ?>
                        <?= $opts->email ?>
                    <?php endif; ?>
                </td>

                <td><?= $subject ?></td>

                <td>

                    <form method="post" action="/admin/emails/run_job">
                        <input type="hidden" name="job_id" value="<?= $job->id ?>" />
                        <input type="submit" name="op" value="run job" />
                    </form>

                </td>

                <td>
                    <form method="post" action="/admin/emails/delete_job" class="confirm" title="delete this job">
                        <input type="hidden" name="job_id" value="<?= $job->id ?>" />
                        <input type="submit" name="op" value="delete job" />
                    </form>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>